<?php

namespace NeoGroup\models;

use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class UserPeer extends DatabaseModel
{
    public static function getUserForUsernameAndPassword ($username, $password)
    {
        $user = null;
        $doUser = self::getDataObject ("user");
        $doUser->addWhereCondition("username = '" . $username . "'");
        $doUser->addWhereCondition("password = '" . $password . "'");
        $doProfile = self::getDataObject ("profile");
        $doUser->addJoin ($doProfile);
        $doTimezone = self::getDataObject ("timezone");
        $doUser->addJoin ($doTimezone);
        $doUser->addSelectField("\"user\".*");
        $doUser->addSelectFields(array("timezoneid", "description", "timezone"), "timezone_%s", "timezone");
        
        if ($doUser->find(true))
        {
            $user = new User();
            $user->setFieldValues($doUser->getFields());
            $doTool = self::getDataObject ("tool");
            $doProfileTool = self::getDataObject ("profiletool");
            $doTool->addJoin ($doProfileTool, DataObject::JOINTYPE_INNER, "toolid");
            $doTool->addWhereCondition("profileid = " . $user->getProfile()->getId());
            $doTool->find();
            while ($doTool->fetch ()) 
            {
                $tool = new Tool();
                $tool->setFieldValues($doTool->getFields());
                $user->getProfile()->addTool($tool);
            }
        }
        return $user;
    }
}

?>
