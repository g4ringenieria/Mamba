<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="event")
 */
class Event extends Model
{
    /**
     * @Column (columnName="eventid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
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
    
    public function __toString() 
    {
        return strval($this->description);
    }
}

?>
