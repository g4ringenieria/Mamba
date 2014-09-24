<?php

namespace Mamba\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="line")
 */
class Line extends DatabaseModel
{
    /**
     * @Column (columnName="lineid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="serviceproviderid", relatedTableName="serviceprovider")
     */
    private $serviceProvider;
    
    /**
     * @Column (columnName="number")
     */
    private $number;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
    /**
     * @Column (columnName="deviceid", relatedTableName="device")
     */
    private $device;
    
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
    
    public function getServiceProvider ()
    {
        return $this->serviceProvider;
    }

    public function setServiceProvider ( ServiceProvider $serviceProvider )
    {
        $this->serviceProvider = $serviceProvider;
    }
    
    public function getNumber ()
    {
        return $this->number;
    }

    public function setNumber ( $number )
    {
        $this->number = $number;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;
    }
    
    public function getDevice ()
    {
        return $this->device;
    }

    public function setDevice ( Device $device )
    {
        $this->device = $device;
    }
}

?>
