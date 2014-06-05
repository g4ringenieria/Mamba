<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="device")
 */
class Device extends Model
{
    /**
     * @Column (columnName="deviceid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="holderid", relatedTableName"holder")
     */
    private $holder;
    
    /**
     * @Column (columnName="active")
     */
    private $active;
    
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
    
    public function getHolder() 
    {
        return $this->holder;
    }

    public function setHolder(Holder $holder) 
    {
        $this->holder = $holder;
    }

    public function isActive() 
    {
        return $this->active;
    }

    public function setActive($active) 
    {
        $this->active = $active;
    }
}

?>
