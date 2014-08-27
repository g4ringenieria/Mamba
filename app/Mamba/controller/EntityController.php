<?php

namespace Mamba\controller;

use Exception;
use NeoPHP\web\WebController;
use stdClass;

abstract class EntityController extends WebController
{   
    public function processAction ($action, array $parameters = array())
    {
        $response = null;
        if ($action == "index")
        {
            $method = $this->getRequest()->getMethod ();
            switch ($method)
            {
                case "GET":
                    $response = $this->processAction("getResource");
                    break;
                case "PUT":
                    $response = $this->processAction("createResource");
                    break;
                case "POST":
                    $response = $this->processAction("updateResource"); 
                    break;
                case "DELETE":
                    $response = $this->processAction("deleteResource");
                    break;
            }
        }
        else
        {
            $response = parent::processAction($action, $parameters);
        }
        return $response;
    }
    
    public function onBeforeActionExecution ($action, $params)
    {
        $execute = $this->getSession()->isStarted() && isset($this->getSession()->sessionId);
        if ($execute)
        {
            $content = $this->getRequest()->getContent();
            if ($content != null)
                $this->resource = $this->convertInputToResource($content);
        }
        else
        {
            $this->onActionError($action, new Exception ("No Session"));
        }
        return $execute;
    }
    
    protected function onAfterActionExecution ($action, $params, $response)
    {
        if ($this->getRequest()->getParameters()->returnFormat == "json")
        {
            $result = new stdClass();
            $result->success = true;
            if ($response != null)
                $result->results = $response;
            $output = json_encode ($result);
            $this->getResponse()->setContentType("application/json");
            $this->getResponse()->setContent($output);
        }
        return $response;
    }
    
    protected function executeAction ($action, $parameters=array())
    {
        if (isset($this->resource))
            $parameters["resource"] = $this->resource;
        return parent::executeAction($action, $parameters);
    }
    
    public function onActionError ($action, $error)
    {
        if ($this->getRequest()->getParameters()->returnFormat == "json")
        {
            $result = new stdClass();
            $result->success = false;
            $result->errorMessage = $error->getMessage();
            $output = json_encode ($result);
            $this->getResponse()->setContentType("application/json");
            $this->getResponse()->setContent($output);
        }
    }
    
    protected function convertInputToResource ($content)
    {
        return null;
    }
}

?>