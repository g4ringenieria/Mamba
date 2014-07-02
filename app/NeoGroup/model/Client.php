<?php

namespace NeoGroup\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="client")
 */
class Client extends DatabaseModel
{
    /**
     * @Column (columnName="clientid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="name")
     */
    private $name;

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

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }
}

?>
