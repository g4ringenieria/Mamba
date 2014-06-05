<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="holder")
 */
class Holder extends Model
{
    /**
     * @Column (columnName="holderid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="domain")
     */
    private $domain;
    
    /**
     * @Column (columnName="name")
     */
    private $name;
    
    /**
     * @Column (columnName="description")
     */
    private $description;
    
    /**
     * @Column (columnName="ownerid", relatedTableName="client")
     */
    private $owner;
    
    /**
     * @Column (columnName="deviceid", relatedTableName="device")
     */
    private $device;
    
    /**
     * @Column (relatedTableName="holderstatus")
     */
    private $status;
    
    /**
     * @Column (columnName="active")
     */
    private $active;
    
    /**
     * @Column (columnName="image")
     */
    private $image;

    public function __construct($id=null)
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

    public function getDomain ()
    {
        return $this->domain;
    }

    public function setDomain ($domain)
    {
        $this->domain = $domain;
    }

    public function getName ()
    {
        return $this->name;
    }

    public function setName ($name)
    {
        $this->name = $name;
    }

    public function getDescription ()
    {
        return $this->description;
    }

    public function setDescription ($description)
    {
        $this->description = $description;
    }

    public function getActive ()
    {
        return $this->active;
    }

    public function setActive ($active)
    {
        $this->active = $active;
    }

    public function getImage ()
    {
        return $this->image;
    }

    public function setImage ($image)
    {
        $this->image = $image;
    }
    
    public function setOwner (Client $owner)
    {
        $this->owner = $owner;
    }
    
    public function getOwner ()
    {
        return $this->owner;
    }
    
    public function setDevice (Device $device)
    {
        $this->device = $device;
    }
    
    public function getDevice ()
    {
        return $this->device;
    }
    
    public function setStatus (HolderStatus $status)
    {
        $this->status = $status;
    }
    
    public function getStatus ()
    {
        return $this->status;
    }
    
    public function __toString() 
    {
        return $this->name . " - " . $this->domain;
    }
}

?>
