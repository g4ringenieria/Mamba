<?php

namespace NeoGroup\controller\site;

use NeoGroup\view\ErrorView;
use NeoGroup\view\LoginView;
use NeoPHP\web\WebController;

class PortalController extends WebController
{   
    public function onBeforeActionExecution ($action, $params)
    {
        $this->getSession()->destroy();
        return true;
    }
    
    public function indexAction ()
    {
        $view = new LoginView();
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