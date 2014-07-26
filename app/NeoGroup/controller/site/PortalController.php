<?php

namespace NeoGroup\controller\site;

use NeoGroup\view\ErrorView;
use NeoPHP\web\WebController;
use NeoPHP\web\WebScriptView;

class PortalController extends WebController
{   
    public function onBeforeActionExecution ($action, $params)
    {
        $this->getSession()->destroy();
        return true;
    }
    
    public function indexAction ()
    {
        $view = new WebScriptView("scripts/PaperClip");
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