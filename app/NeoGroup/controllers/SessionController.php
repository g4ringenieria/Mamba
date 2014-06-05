<?php

namespace NeoGroup\controllers;

use Exception;
use NeoPHP\data\DataObject;
use NeoGroup\models\Tool;
use NeoGroup\models\User;

class SessionController extends EntityController
{
    public function onBeforeActionExecution ($action)
    {
        return true;
    }
    
    public function getResourceAction ($username, $password)
    {
        $this->createResourceAction($username, $password);
    }
    
    public function createResourceAction ($username, $password)
    {
        $this->getSession()->destroy();
        $sessionId = false;
        $user = $this->getUserForUsernameAndPassword($username, $password);
        if ($user != null)
        {
            $this->getSession()->start();
            $this->getSession()->sessionId = session_id();
            $this->getSession()->sessionName = session_name();
            $this->getSession()->userId = $user->getId();
            $this->getSession()->userDateFormat = "Y-m-d H:i:s";
            $this->getSession()->userTimeZone = $user->getTimeZone()->getTimezone();
            $this->getSession()->firstName = $user->getFirstname();
            $this->getSession()->lastName = $user->getLastname();
            $this->getSession()->clientId = $user->getClient()->getId();
            $this->getSession()->clientDescription = $user->getClient()->getDescription();
            $this->getSession()->tools = serialize($user->getProfile()->getTools());
            $sessionId = session_id();
        }
        else
        {
            throw new Exception ("Nombre de usuario o contraseña incorrecta");
        }
        return $sessionId;
    }
    
    public function deleteResourceAction ()
    {
        $this->getSession()->destroy();
    }
    
    private function getUserForUsernameAndPassword ($username, $password)
    {
        $user = null;
        $database = $this->getApplication()->getDefaultDatabase ();
        $doUser = $database->getDataObject ("user");
        $doUser->addWhereCondition("username = '" . $username . "'");
        $doUser->addWhereCondition("password = '" . $password . "'");
        $doProfile = $database->getDataObject ("profile");
        $doUser->addJoin ($doProfile);
        $doTimezone = $database->getDataObject ("timezone");
        $doUser->addJoin ($doTimezone);
        $doUser->addSelectField("\"user\".*");
        $doUser->addSelectFields(array("timezoneid", "description", "timezone"), "timezone_%s", "timezone");
        
        if ($doUser->find(true))
        {
            $user = new User();
            $user->completeFromFieldsArray($doUser->getFields());
            $doTool = $database->getDataObject ("tool");
            $doProfileTool = $database->getDataObject ("profiletool");
            $doTool->addJoin ($doProfileTool, DataObject::JOINTYPE_INNER, "toolid");
            $doTool->addWhereCondition("profileid = " . $user->getProfile()->getId());
            $doTool->find();
            while ($doTool->fetch ()) 
            {
                $tool = new Tool();
                $tool->completeFromFieldsArray($doTool->getFields());
                $user->getProfile()->addTool($tool);
            }
        }
        return $user;
    }
}

?>
