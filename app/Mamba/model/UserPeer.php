<?php

namespace Mamba\model;

use NeoPHP\mvc\DatabaseModel;

class UserPeer extends DatabaseModel
{
    public static function getUserForUsernameAndPassword ($username, $password)
    {
        $user = null;
        $doUser = self::getDataObject ("user");
        $doTimezone = self::getDataObject ("timezone");
        $doUser->addWhereCondition("username = '" . $username . "'");
        $doUser->addWhereCondition("password = '" . $password . "'");
        $doUser->addJoin ($doTimezone);
        $doUser->addSelectField("\"user\".*");
        $doUser->addSelectFields(array("timezoneid", "description", "timezone"), "timezone_%s", "timezone");
        if ($doUser->find(true))
        {
            $user = new User();
            $user->setFieldValues($doUser->getFields());
        }
        return $user;
    }
}

?>
