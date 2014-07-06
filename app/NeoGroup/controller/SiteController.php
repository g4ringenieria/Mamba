<?php

namespace NeoGroup\controller;

use NeoGroup\model\ToolPeer;
use NeoGroup\view\ErrorView;
use NeoGroup\view\MainView;
use NeoPHP\web\WebController;

class SiteController extends WebController
{   
    public function onBeforeActionExecution ($action)
    {
        $this->getSession()->start();
        $executeAction = ($action == "logout" || ($this->getSession()->isStarted() && isset($this->getSession()->sessionId)));
        if (!$executeAction)
            $this->redirectAction("site/portal/");
        return $executeAction;
    }
    
    public function indexAction ()
    {
        $mainView = new MainView();
        $mainView->setTools(ToolPeer::getToolsForProfileId($this->getSession()->profileId));
        $mainView->render();
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
