<?php

namespace NeoGroup\controllers\site;

use NeoGroup\views\site\ErrorView;
use NeoPHP\web\WebController;
use NeoPHP\web\WebScriptView;

class PortalController extends WebController
{   
    public function onBeforeActionExecution ($action)
    {
        $this->executeAction("session/deleteResource");
        return true;
    }
    
    public function indexAction ()
    {
        $view = new WebScriptView("site/portal");
        $view->render();
    }
    
    public function onActionError ($action, $error)
    {
        $errorView = new ErrorView();
        $errorView->setException ($error);
        $errorView->render();
    }
}

?>
