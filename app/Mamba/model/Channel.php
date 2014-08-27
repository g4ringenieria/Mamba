<?php

namespace Mamba\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="channel")
 */
class Channel extends DatabaseModel
{
    /**
     * @Column (columnName="channelid", id=true)
     */
    protected $id;
    
    /**
     * @Column (columnName="description")
     */
    protected $description;
    
    public function __construct($id = null)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}

?>