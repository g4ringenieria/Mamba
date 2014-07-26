<?php

namespace NeoGroup\controller;

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
                    $response = parent::processAction("getResource", $parameters);
                    break;
                case "PUT":
                    $response = parent::processAction("createResource", $parameters);
                    break;
                case "POST":
                    $response = parent::processAction("updateResource", $parameters); 
                    break;
                case "DELETE":
                    $response = parent::processAction("deleteResource", $parameters);
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
        parent::executeAction($action, $parameters);
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