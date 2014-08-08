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
     * @Column (columnName="userid")
     */
    private $userId;
    
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
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function setUserId($userId)
    {
        $this->userId = $userId;
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