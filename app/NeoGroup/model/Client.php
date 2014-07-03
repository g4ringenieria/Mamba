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
    
    /**
     * @Column (columnName="clienttypeid", relatedTableName="clienttype")
     */
    private $clientType;
    
    /**
     * @Column (columnName="clientstateid", relatedTableName="clientstate")
     */
    private $clientState;

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
    
    public function getClientType()
    {
        return $this->clientType;
    }

    public function setClientType(ClientType $clientType)
    {
        $this->clientType = $clientType;
    }

    public function getClientState()
    {
        return $this->clientState;
    }

    public function setClientState(ClientState $clientState)
    {
        $this->clientState = $clientState;
    }
}

?>
