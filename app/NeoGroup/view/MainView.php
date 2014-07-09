<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;

class MainView extends HTMLView
{
    private $defaultAction = null;
    private $tools = array();

    public function setDefaultAction($defaultAction)
    {
        $this->defaultAction = $defaultAction;
    }

    public function setTools(array $tools)
    {
        $this->tools = $tools;
    }

    protected function build()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->addMeta(array("charset" => "utf-8"));
        $this->addMeta(array("name" => "viewport", "content" => "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));
        $this->addStyleFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/css/bootstrap.min.css");
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.1.0/css/font-awesome.min.css");
        $this->addScriptFile("//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/js/bootstrap.min.js");
        
        $this->addScript('
            function toggleSidebar () 
            {
                $(body).toggleClass("collapsed");
            }
        ');
        
        $this->addStyle('
            #side-container
            {
                position:fixed;
                width: 200px;
                height: 100%;
                padding-top: 50px;
                z-index: 20;
            }

            #main-container
            {
                position:fixed;
                width: 100%;
                height: 100%;
                padding-left: 200px;
                padding-top: 50px;
                z-index: 10;
            }
            
            .collapsed #side-container
            {
                visibility: hidden;
            }
            
            .collapsed #main-container
            {
                padding-left:0px;
            }
            
            #sidebar
            {
                width: 100%;
                height: 100%;
                overflow: auto;
                padding-top: 10px;
            }

            #iframe
            {
                width: 100%;
                height: 100%;
                border-style: none;
            }
        ');
        $this->buildBody();
    }

    protected function buildBody()
    {
        $this->getBodyTag()->add($this->createHeader());
        $this->getBodyTag()->add($this->createSidebar());
        $this->getBodyTag()->add($this->createContent());
    }

    
    protected function createHeader ()
    {   
        return '
        <nav class="navbar navbar-default navbar-fixed-top main-navbar" role="navigation" style="margin-bottom: 0">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" onclick="toggleSideBar();">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="' . $this->getUrl('site/main/') . '" class="navbar-brand"> ' . $this->getApplication()->getName() . '</a>
                </div>
                <ul class="nav navbar-nav pull-right hidden-xs">
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i></a></li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i></a></li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i></a></li>
                    <li class="dropdown">            
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-user"></i> ' . $this->getSession()->firstName . ' ' . $this->getSession()->lastName . ' <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="' . $this->getUrl("site/account/") . '"><i class="fa fa-user"></i> Mi Cuenta</a></li>
                            <li><a href="' . $this->getUrl("site/settings/") . '"><i class="fa fa-gear"></i> Configuración</a></li>
                            <li class="divider"></li>
                            <li><a href="' . $this->getUrl("site/logout") . '"><i class="fa fa-power-off"></i> Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>';
    }
    
    protected function createSidebar()
    {
        return '
        <div id="side-container">
            <div id="sidebar">
                <ul class="nav nav-sidebar">
                    <li class="active"><a href="#">Overview</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Export</a></li>
                </ul>
                <ul class="nav nav-sidebar">
                    <li><a href="">Nav item</a></li>
                    <li><a href="">Nav item again</a></li>
                    <li><a href="">One more nav</a></li>
                    <li><a href="">Another nav item</a></li>
                    <li><a href="">More navigation</a></li>
                </ul>
                <ul class="nav nav-sidebar">
                    <li><a href="">Nav item again</a></li>
                    <li><a href="">One more nav</a></li>
                    <li><a href="">Another nav item</a></li>
                </ul>
            </div>
        </div>';
    }
    
    protected function createContent()
    {
        return '<div id="main-container"><iframe id="iframe" src="' . $this->getUrl(!empty($this->defaultAction)?$this->defaultAction:"site/dashboard/") . '"></iframe></div>';
    }
}

?>