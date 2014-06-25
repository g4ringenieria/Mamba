<?php

namespace NeoGroup\controllers\site;

use NeoGroup\views\site\ErrorView;
use NeoGroup\views\site\PortalView;
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
        $portalView = new PortalView();
        $portalView->render();
    }
    
    public function onActionError ($action, $error)
    {
        $errorView = new ErrorView();
        $errorView->setException ($error);
        $errorView->render();
    }
}

?>
