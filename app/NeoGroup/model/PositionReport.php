<?php

namespace NeoGroup\model;

class PositionReport extends Report
{
    /**
     * @Column (columnName="data", dataIndex=0)
     */
    protected $longitude;
    
    /**
     * @Column (columnName="data", dataIndex=1)
     */
    protected $latitude;
    
    /**
     * @Column (columnName="data", dataIndex=2)
     */
    protected $altitude;
    
    /**
     * @Column (columnName="data", dataIndex=3)
     */
    protected $speed;
    
    /**
     * @Column (columnName="data", dataIndex=4)
     */
    protected $course;
    
    /**
     * @Column (columnName="data", dataIndex=5)
     */
    protected $location;
    
    public function __construct($classType=Report::CLASSTYPE_POSITION)
    {
        parent::__construct($classType);
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
}