<?php

namespace NeoGroup\model;

use NeoPHP\mvc\DatabaseModel;

/**
 * @Table (tableName="contact")
 */
class Contact extends DatabaseModel
{
    /**
     * @Column (columnName="contactid", id=true)
     */
    private $id;
    
    /**
     * @Column (columnName="userid", relatedTableName="user")
     */
    private $user;
    
    /**
     * @Column (columnName="contacttypeid", relatedTableName="contacttype")
     */
    private $contactType;
    
    /**
     * @Column (columnName="value")
     */
    private $value;
    
    
    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getContactType()
    {
        return $this->contactType;
    }
    
    public function setContactType(ContactType $contactType)
    {
        $this->contactType = $contactType;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}

?>