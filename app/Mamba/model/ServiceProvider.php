<?php

namespace Mamba\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="serviceprovider")
 */
class ServiceProvider extends DatabaseModel
{
    /**
     * @Column (columnName="serviceproviderid", id=true)
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