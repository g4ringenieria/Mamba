<?php

namespace NeoGroup\models;

class PositionReport extends Report
{
    /**
     * @Column (columnName="latitude")
     */
    private $latitude;
    
    /**
     * @Column (columnName="longitude")
     */
    private $longitude;
    
    /**
     * @Column (columnName="altitude")
     */
    private $altitude;
    
    /**
     * @Column (columnName="location")
     */
    private $location;
    
    /**
     * @Column (columnName="course")
     */
    private $course;
    
    /**
     * @Column (columnName="speed")
     */
    private $speed;
    
    /**
     * @Column (columnName="odometer")
     */
    private $odometer;
    
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
