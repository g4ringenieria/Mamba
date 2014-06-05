<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="client")
 */
class Client extends Model
{
    /**
     * @Column (columnName="clientid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;

    public function __construct($id=null) 
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
}

?>
