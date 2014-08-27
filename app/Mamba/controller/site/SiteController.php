<?php

namespace Mamba\controller\site;

use Mamba\view\ErrorView;
use Mamba\view\LoginView;
use NeoPHP\web\WebController;

abstract class SiteController extends WebController
{
    public function onBeforeActionExecution ($action, $params)
    {
        $this->getSession()->start();
        $executeAction = $this->getSession()->isStarted() && isset($this->getSession()->sessionId);
        if (!$executeAction)
        {
            $view = new LoginView();
            $view->render();
        }
        return $executeAction;
    }
    
    public function onActionError ($action, $error)
    {
        $errorView = new ErrorView(); 
        $errorView->setException ($error);
        $errorView->render();
    }
}

?>