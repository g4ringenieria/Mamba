<?php

namespace Mamba\model;

use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

abstract class ContactPeer extends DatabaseModel
{
    public static function getContactsForUserId ($userId)
    {
        $contacts = array();
        $doContact = self::getDataObject ("contact");
        $doContactType = self::getDataObject ("contacttype");
        $doContact->addJoin ($doContactType, DataObject::JOINTYPE_INNER);
        $doContact->addSelectFields(array("contactid", "userid", "value"), "%s", "contact");
        $doContact->addSelectFields(array("contacttypeid", "description"), "contacttype_%s", "contacttype");
        $doContact->addWhereCondition("userid = $userId");
        $doContact->find();
        while ($doContact->fetch())
        {
            $contact = new Contact();
            $contact->setFieldValues($doContact->getFields());
            $contacts[] = $contact;
        }
        return $contacts;
    }
    
    public static function deleteUserContacts ($userId)
    {
        $doContact = self::getDataObject ("contact");
        $doContact->addWhereCondition("userid = $userId");
        $doContact->delete();
    }
}

?>
