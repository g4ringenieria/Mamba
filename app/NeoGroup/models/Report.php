<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="report")
 */
class Report extends Model
{
    /**
     * @Column (columnName="reportid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="eventid", relatedTableName="event")
     */
    private $event;
    
    /**
     * @Column (columnName="inputdate")
     */
    private $inputDate;
    
    /**
     * @Column (columnName="date")
     */
    private $date;
    
    /**
     * @Column (columnName="holderid", relatedTableName="holder")
     */
    private $holder;
    
    /**
     * @Column (columnName="deviceid", relatedTableName="device")
     */
    private $device;
    
    /**
     * @Column (columnName="latitude")
     */
    private $latitude;
    
    /**
     * @Column (columnName="longitude")
     */
    private $longitude;
    
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

    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }

    public function getEvent ()
    {
        return $this->event;
    }

    public function setEvent (Event $event)
    {
        $this->event = $event;
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

?>
