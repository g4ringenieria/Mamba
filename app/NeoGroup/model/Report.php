<?php

namespace NeoGroup\model;

use DateTime;
use Exception;
use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="report")
 */
abstract class Report extends DatabaseModel
{
    const CLASSTYPE_POSITION = 1;
    
    /**
     * @Column (columnName="reportid", id=true)
     */
    protected $id;
    
    /**
     * @Column (columnName="reporttypeid", relatedTableName="reporttype")
     */
    protected $reportType;
    
    /**
     * @Column (columnName="inputdate")
     */
    protected $inputDate;
    
    /**
     * @Column (columnName="date")
     */
    protected $date;
    
    /**
     * @Column (columnName="holderid", relatedTableName="holder")
     */
    protected $holder;
    
    /**
     * @Column (columnName="deviceid", relatedTableName="device")
     */
    protected $device;
    
    /**
     * @Column (columnName="reportclasstypeid")
     */
    protected $reportClassType;
    
    public function __construct($reportClassType)
    {
        $this->reportClassType = $reportClassType;
    }
    
    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getReportType ()
    {
        return $this->reportType;
    }

    public function setReportType (ReportType $reportType)
    {
        $this->reportType = $reportType;
    }
    
    public function setReportClassType ($reportClassType)
    {
        $this->reportClassType = $reportClassType;
    }
    
    public function getReportClassType ()
    {
        return $this->reportClassType;
    }
    
    public function getInputDate ()
    {
        return $this->inputDate;
    }

    public function setInputDate ($inputDate)
    {
        $this->inputDate = $inputDate;
    }

    public function getDate ()
    {
        return $this->date;
    }

    public function setDate ($date)
    {
        $this->date = $date;
    }

    public function getHolder ()
    {
        return $this->holder;
    }

    public function setHolder (Holder $holder)
    {
        $this->holder = $holder;
    }

    public function getDevice ()
    {
        return $this->device;
    }

    public function setDevice (Device $device)
    {
        $this->device = $device;
    }
    
    public function insert()
    {
        if ($this->getHolder() == null && $this->getDevice() != null)
        {
            if ($this->getDevice()->getHolder() == null)
                $this->getDevice()->find();
            $this->setHolder ($this->getDevice()->getHolder());
        }
        
        self::getDatabase()->beginTransaction();
        try 
        {
            $this->setInputDate(new DateTime());
            parent::insert();
            $reportId = intval(self::getDatabase()->getLastInsertedId("report_reportid_seq"));
            if (empty($reportId))
                throw new Exception ("Id for new report inserted could not be retrieved");
            
            if ($this->getHolder() != null)
            {   
                $fieldValues = $this->getFieldValues();
                $fieldValues["reportid"] = $reportId;
                $doLastReport = self::getDataObject ("lastreport");
                foreach ($fieldValues as $field=>$value)
                    $doLastReport->$field = $value;
                $doLastReport->addWhereCondition("holderid = " . $this->getHolder()->getId());
                $doLastReport->addWhereCondition("reportclasstypeid = " . $this->getReportClassType());
                $affectedRows = $doLastReport->update();
                if ($affectedRows == 0)
                {
                    $doLastReport->resetSqlData ();
                    $doLastReport->holderid = $this->getHolder()->getId();
                    $doLastReport->reportclasstypeid = $this->getReportClassType();
                    $doLastReport->insert();
                }
            }
            self::getDatabase()->commitTransaction();
        }
        catch (Exception $ex)
        {
            self::getDatabase()->rollbackTransaction();
            throw $ex;
        }
    }
}

?>