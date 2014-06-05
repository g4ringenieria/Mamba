<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="profile")
 */
class Profile extends Model
{
    /**
     * @Column (columnName="profileid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
    private $tools;
    
    public function __construct() 
    {
        $this->tools = array();
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
    
    public function getTools() 
    {
        return $this->tools;
    }

    public function clearTools()
    {
        $this->tools = array();
    }
    
    public function addTool (Tool $tool)
    {
        $this->tools[] = $tool;
    }
}

?>
