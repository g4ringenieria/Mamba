<?php

namespace Mamba\model;

use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class DevicePeer extends DatabaseModel
{
    public static function getDevices ($clientId)
    {
        $devices = array();
        $doDevice = self::getDataObject("device");
        $doHolder = self::getDataObject("holder");
        $doClientHolder = self::getDataObject ("clientholder");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doDevice->addJoin ($doHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doDevice->addSelectField("device.*");
        $doDevice->addSelectField("holder.*");
        $doDevice->addWhereCondition ("clientholder.clientid = {$clientId}");
        $doDevice->find();
        while ($doDevice->fetch())
        {
            $holder = new Holder();
            $holder->setFieldValues($doDevice->getFields());
            
            $device = new Device();
            $device->setFieldValues($doDevice->getFields());
            $device->setHolder($holder);
            
            $devices[] = $device;
        }
        
        return $devices;
    }
}

?>