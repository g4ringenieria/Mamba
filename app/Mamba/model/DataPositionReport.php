<?php

namespace Mamba\model;

class DataPositionReport extends PositionReport
{
    /**
     * @Column (columnName="data", dataIndex=10)
     */
    private $odometer;
    
    public function __construct($classType=Report::CLASSTYPE_DATAPOSITION)
    {
        parent::__construct($classType);
    }
    
    public function getOdometer ()
    {
        return $this->odometer;
    }

    public function setOdometer ($odometer)
    {
        $this->odometer = $odometer;
    }
}

?>