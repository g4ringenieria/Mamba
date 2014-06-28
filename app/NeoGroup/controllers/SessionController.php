<?php

namespace NeoGroup\controllers;

use Exception;
use NeoGroup\models\UserPeer;

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
        $user = UserPeer::getUserForUsernameAndPassword($username, $password);
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
}

?>