<?php

namespace NeoGroup\controllers\site;

use NeoPHP\web\WebController;

class PortalController extends WebController
{   
    public function onBeforeActionExecution ($action)
    {
        $this->executeAction("session/deleteResource");
        return true;
    }
    
    public function indexAction ()
    {
        $this->createView('site/portal')->render();
    }
    
    public function onActionError ($action, $error)
    {
        $errorView = $this->createView("site/error");
        $errorView->setException ($error);
        $errorView->render();
    }
}

?>
