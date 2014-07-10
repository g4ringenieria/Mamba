<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

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
                $("body").toggleClass("collapsed");
            }
            
            function loadUrl (url)
            {
                $("#iframe").attr("src", url);
                $("#left-panel nav li.active").removeClass("active");
                $("#left-panel nav li:has(a[href=\"" + url + "\"])").addClass("active");
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
            
            @media (max-width:765px) 
            {
                #side-container
                {
                    visibility: hidden;
                }

                #main-container
                {
                    padding-left:0px;
                }

                .collapsed #side-container
                {
                    visibility: visible;
                }

                .collapsed #main-container
                {
                    padding-left:200px;
                }
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
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <a href="#" onclick="toggleSidebar();" class="navbar-brand"><i class="fa fa-bars"></i>&nbsp;&nbsp;' . $this->getApplication()->getName() . '</a>
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
                            <li><a href="' . $this->getUrl("site/main/logout") . '"><i class="fa fa-power-off"></i> Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>';
    }
    
    protected function createSidebar()
    {
        $list = new Tag("ul", array("class"=>"nav nav-sidebar"));
        foreach ($this->tools as $tool)
        {
            $anchor = new Tag("a", array("href"=>$this->getUrl($tool->getAction())));
            $anchor->add (new Tag("i", array("class"=>"fa fa-" . $tool->getIcon()),""));
            $anchor->add ("&nbsp;" . $tool->getDescription());
            $list->add (new Tag("li", $anchor));
        }
        $sidebar = new Tag("div", array("id"=>"sidebar"), $list);
        return new Tag("div", array("id"=>"side-container"), $sidebar);
    }
    
    protected function createContent()
    {
        return '<div id="main-container"><iframe id="iframe" src="' . $this->getUrl(!empty($this->defaultAction)?$this->defaultAction:"site/dashboard/") . '"></iframe></div>';
    }
}

?>