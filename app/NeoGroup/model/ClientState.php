<?php

namespace NeoGroup\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="clientstate")
 */
class ClientState extends DatabaseModel
{
    /**
     * @Column (columnName="clientstateid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
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