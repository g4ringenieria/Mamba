<?php

namespace Mamba\controller\site;

use Mamba\view\ErrorView;
use Mamba\view\LoginView;
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