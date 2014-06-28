<?php

namespace NeoGroup\models;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="tool")
 */
class Tool extends DatabaseModel
{
    /**
     * @Column (columnName="toolid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
    /**
     * @Column (columnName="action")
     */
    private $action;
    
    /**
     * @Column (columnName="path")
     */
    private $path;
    
    /**
     * @Column (columnName="icon")
     */
    private $icon;
    
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

    public function getAction() 
    {
        return $this->action;
    }

    public function setAction($action) 
    {
        $this->action = $action;
    }
    
    public function getPath() 
    {
        return $this->path;
    }

    public function setPath($path) 
    {
        $this->path = $path;
    }
    
    public function getIcon()
    {
        return $this->icon;
    }
    
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
}

?>
