<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;

abstract class SiteView extends HTMLView
{
    protected function build()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->addMeta(array("charset"=>"utf-8"));
        $this->addMeta(array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));
        $this->addStyleFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/css/bootstrap.min.css");
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.1.0/css/font-awesome.min.css");
        $this->addScriptFile("//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/js/bootstrap.min.js");
        $this->addStyle('body { padding: 10px; }');
        $this->buildBody();
    }
    
    protected function buildBody()
    {
        $this->buildPage($this->getBodyTag()); 
    }
    
    protected abstract function buildPage($body);
}

?>