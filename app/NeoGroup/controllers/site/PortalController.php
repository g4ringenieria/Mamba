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
    
    public function indexAction ($message=null)
    {
        $view = $this->createView('site/portal');
        if (!empty($message))
            $view->setMessage($message);
        $view->render();
    }
}

?>
