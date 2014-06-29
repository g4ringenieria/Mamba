<?php

namespace NeoGroup\models;

class PositionReport extends Report
{
    /**
     * @Column (columnName="data", dataIndex=0)
     */
    private $longitude;
    
    /**
     * @Column (columnName="data", dataIndex=1)
     */
    private $latitude;
    
    /**
     * @Column (columnName="data", dataIndex=2)
     */
    private $altitude;
    
    /**
     * @Column (columnName="data", dataIndex=3)
     */
    private $speed;
    
    /**
     * @Column (columnName="data", dataIndex=4)
     */
    private $course;
    
    /**
     * @Column (columnName="data", dataIndex=5)
     */
    private $location;
    
    /**
     * @Column (columnName="data", dataIndex=6)
     */
    private $odometer;
    
    public function __construct()
    {
        parent::__construct(Report::CLASSTYPE_POSITION);
    }
    
    public function getLatitude ()
    {
        return $this->latitude;
    }

    public function setLatitude ($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLongitude ()
    {
        return $this->longitude;
    }

    public function setLongitude ($longitude)
    {
        $this->longitude = $longitude;
    }
    
    public function getAltitude ()
    {
        return $this->altitude;
    }
    
    public function setAltitude ($altitude)
    {
        $this->altitude = $altitude;
    }

    public function getLocation ()
    {
        return $this->location;
    }

    public function setLocation ($location)
    {
        $this->location = $location;
    }

    public function getCourse ()
    {
        return $this->course;
    }

    public function setCourse ($course)
    {
        $this->course = $course;
    }

    public function getSpeed ()
    {
        return $this->speed;
    }

    public function setSpeed ($speed)
    {
        $this->speed = $speed;
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
