<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

abstract class SiteView extends HTMLView
{
    protected function build()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->addMeta(array("charset"=>"utf-8"));
        $this->addMeta(array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));
        $this->addMeta(array("name"=>"apple-mobile-web-app-capable", "content"=>"yes"));
        $this->addMeta(array("name"=>"apple-mobile-web-app-status-bar-style", "content"=>"black"));
        $this->addStyleFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/css/bootstrap.min.css");
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.1.0/css/font-awesome.min.css");
        $this->addStyleFile($this->getBaseUrl() . "css/site.css");
        $this->addStyleFile($this->getBaseUrl() . "css/skin_google.css");
        $this->addStyleFile("http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700");
        $this->addScriptFile("//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/js/bootstrap.min.js");
        $this->buildBody();
    }
    
    protected function buildBody()
    {
        $this->getBodyTag()->setAttribute("class", "fixed-header fixed-navigation");
        $this->buildPage($this->getBodyTag()); 
    }
    
    protected abstract function buildPage($body);
}

?>