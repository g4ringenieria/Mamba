<?php

namespace NeoGroup\models;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="reporttype")
 */
class ReportType extends DatabaseModel
{
    const REPORTTYPE_POLL = 1;
    const REPORTTYPE_TIMEREPORT = 2;
    const REPORTTYPE_DISTANCEREPORT = 3;
    
    /**
     * @Column (columnName="reporttypeid", id=true)
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
    
    public function getId ()
    {
        return $this->id;
    }

    public function setId ($id)
    {
        $this->id = $id;
    }
    
    public function getDescription ()
    {
        return $this->description;
    }

    public function setDescription ($description)
    {
        $this->description = $description;
    }
    
    public function __toString() 
    {
        return strval($this->description);
    }
}

?>