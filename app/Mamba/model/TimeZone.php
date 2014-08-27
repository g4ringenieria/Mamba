<?php

namespace Mamba\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="timezone")
 */
class TimeZone extends DatabaseModel
{
    /**
     * @Column (columnName="timezoneid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
    /**
     * @Column (columnName="timezone")
     */
    private $timezone;
    
    function __construct($id=null)
    {
        $this->id = $id;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;
    }

    public function getTimezone() 
    {
        return $this->timezone;
    }

    public function setTimezone($timezone) 
    {
        $this->timezone = $timezone;
    }
}

?>
