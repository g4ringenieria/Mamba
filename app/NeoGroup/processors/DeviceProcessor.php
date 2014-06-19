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
            $doReport = $database->getDataObject ("report");
            $doReport->date = $report->getDate();
            $doReport->inputDate = $report->getInputDate();
            $doReport->deviceid = $report->getDevice()->getId();
            if ($report->getHolder() != null)
                $doReport->holderid = $report->getHolder()->getId();
            $doReport->reporttypeid = $report->getReportType()->getId();
            
            if ($report instanceof PositionReport)
            {
                $doReport->reportclasstypeid = Report::CLASSTYPE_POSITION;
                $doReport->longitude = $report->getLongitude();
                $doReport->latitude = $report->getLatitude();
                $doReport->speed = $report->getSpeed();
                $doReport->course = $report->getCourse();
                $location = $report->getLocation(); 
                if (!empty($location))
                    $doReport->location = $location;
                $doReport->odometer = $report->getOdometer();
            }
            
            $doReport->insert();
            $reportId = intval($database->getLastInsertedId("report_reportid_seq"));
            if (empty($reportId))
                throw new Exception ("Id for new report inserted could not be retrieved");
            
            if ($report->getHolder() != null)
            {   
                $doHolderStatus = $database->getDataObject ("holderstatus");
                $doHolderStatus->reportid = $reportId;
                $doHolderStatus->addWhereCondition("holderid = " . $report->getHolder()->getId());
                $affectedRows = $doHolderStatus->update();
                if ($affectedRows == 0)
                {
                    $doHolderStatus->resetSqlData ();
                    $doHolderStatus->reportid = $reportId;
                    $doHolderStatus->holderid = $report->getHolder()->getId();
                    $doHolderStatus->insert();
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