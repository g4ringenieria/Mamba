<?php

namespace NeoGroup\controller;

use Exception;
use NeoPHP\web\WebRestController;
use stdClass;

abstract class EntityController extends WebRestController
{   
    public function onBeforeActionExecution ($action, &$params)
    {
        $execute = true;
        if (php_sapi_name() == "cli")
        {
            if (sizeof($params) > 0)
                $params["resource"] = $this->convertInputToResource($params[0]);
        }
        else
        {
            $execute = $this->getSession()->isStarted() && isset($this->getSession()->sessionId);
            if ($execute)
            {
                $content = $this->getRequest()->getContent();
                if ($content != null)
                    $params["resource"] = $this->convertInputToResource($content);
            }
            else
            {
                $this->onActionError($action, new Exception ("No Session"));
            }
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