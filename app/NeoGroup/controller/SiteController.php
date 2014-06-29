<?php

namespace NeoGroup\controller;

use NeoGroup\view\ErrorView;
use NeoPHP\web\WebController;

class SiteController extends WebController
{   
    public function onBeforeActionExecution ($action)
    {
        $this->getSession()->start();
        $executeAction = ($action == "login" || $action == "logout" || $action == "error" || ($this->getSession()->isStarted() && isset($this->getSession()->sessionId)));
        if (!$executeAction)
            $this->redirectAction("site/portal/");
        return $executeAction;
    }
    
    public function indexAction ()
    {
        $this->executeAction("site/portal/");
    }
    
    public function logoutAction ()
    {
        $this->executeAction("session/deleteResource");
        $this->redirectAction();
    }
    
    public function onActionError ($action, $error)
    {
        $errorView = new ErrorView(); 
        $errorView->setException ($error);
        $errorView->render();
    }
}

?>
