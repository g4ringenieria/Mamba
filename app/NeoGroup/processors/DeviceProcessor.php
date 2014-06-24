<?php

namespace NeoGroup\processors;

use Exception;
use NeoGroup\models\Holder;
use NeoGroup\models\PositionReport;
use NeoGroup\models\Report;
use NeoPHP\app\Processor;
use NeoPHP\connection\Connection;
use NeoPHP\connection\ConnectionListener;

abstract class DeviceProcessor extends Processor implements ConnectionListener
{
    private static $connectionProcessor = array();
    
    public function start()
    {
        $this->getApplication()->getConnectionManager()->addConnectionListener ($this);
    }

    public function stop()
    {
        $this->getApplication()->getConnectionManager()->removeConnectionListener ($this);
    }

    public function onConnectionAdded(Connection $connection) {}
    public function onConnectionDataSent(Connection $connection, $dataSent) {}
    
    public function onConnectionDataReceived(Connection $connection, $dataReceived)
    {
        $connectionId = $connection->getId();
        if (!isset($this->connectionProcessor[$connectionId]))
        {
            if ($this->checkDatagramValidity($dataReceived))
            {
                $this->processDatagram ($connection, $dataReceived);
                $this->connectionProcessor[$connectionId] = $this;
            }
        }
        else if ($this->connectionProcessor[$connectionId] === $this)
        {
            $this->processDatagram ($connection, $dataReceived);
        }    
    }
    
    public function onConnectionRemoved(Connection $connection) 
    {
        $connectionId = $connection->getId();
        if (!empty($connectionId))
            unset($this->connectionProcessor[$connectionId]);
    }
    
    private function processDatagram (Connection $connection, $datagram)
    {
        try
        {
            $this->datagramReceived ($connection, $datagram);   
        }
        catch (Exception $ex)
        {
            $this->getLogger()->error ("Error with incoming package (" . bin2hex($datagram) . "). Message: " . $ex->getMessage());
        }
    }
    
    public function insertReport (Report $report)
    {
        $database = $this->getApplication()->getDefaultDatabase();
        if ($report->getHolder() == null)
        {
            $doDevice = $database->getDataObject ("device");
            $doDevice->addSelectField("holderid");
            $doDevice->addWhereCondition("deviceid = " . $report->getDevice()->getId());
            if ($doDevice->find(true))
                $report->setHolder(new Holder($doDevice->holderid));
        }
        
        $database->beginTransaction();
        try 
        {
            $reportClassType = 0;
            $reportData = array();
            if ($report instanceof PositionReport)
            {
                $location = $report->getLocation(); 
                $altitude = $report->getAltitude();
                $reportClassType = Report::CLASSTYPE_POSITION;
                $reportData = array(
                    strval($report->getLongitude()), 
                    strval($report->getLatitude()), 
                    (!empty($altitude))? strval($altitude) : "",
                    strval($report->getSpeed()),
                    strval($report->getCourse()),
                    (!empty($location))? strval($location) : "",
                    strval($report->getOdometer())
                );
            }
            
            $doReport = $database->getDataObject ("report");
            $doReport->date = $report->getDate();
            $doReport->inputDate = $report->getInputDate();
            $doReport->deviceid = $report->getDevice()->getId();
            if ($report->getHolder() != null)
                $doReport->holderid = $report->getHolder()->getId();
            $doReport->reporttypeid = $report->getReportType()->getId();
            $doReport->reportclasstypeid = $reportClassType;
            $deReport->data = $reportData;
            $doReport->insert();
            
            $reportId = intval($database->getLastInsertedId("report_reportid_seq"));
            if (empty($reportId))
                throw new Exception ("Id for new report inserted could not be retrieved");
            
            if ($report->getHolder() != null)
            {   
                $doLastReport = $database->getDataObject ("lastreport");
                $doLastReport->date = $doReport->date;
                $doLastReport->inputDate = $doReport->inputDate;
                $doLastReport->deviceid = $doReport->deviceid;
                $doLastReport->holderid = $doReport->holderid;
                $doLastReport->reporttypeid = $doReport->reporttypeid;
                $doLastReport->reportclasstypeid = $doReport->reportclasstypeid;
                $doLastReport->data = $doReport->data;
                $doLastReport->addWhereCondition("holderid = " . $doReport->holderid);
                $doLastReport->addWhereCondition("reportclasstypeid = " . $doReport->reportclasstypeid);
                $affectedRows = $doLastReport->update();
                if ($affectedRows == 0)
                {
                    $doLastReport->resetSqlData ();
                    $doLastReport->holderid = $doReport->holderid;
                    $doLastReport->reportclasstypeid = $doReport->reportclasstypeid;
                    $doLastReport->insert();
                }
            }
            $database->commitTransaction();
        }
        catch (Exception $ex)
        {
            $database->rollbackTransaction();
            throw $ex;
        }
    }
    
    protected function sendDatagram (Connection $connection, $datagram)
    {
        $connection->send($datagram);
    }
    
    protected abstract function checkDatagramValidity ($datagram);
    protected abstract function datagramReceived (Connection $connection, $datagram);
}

?>