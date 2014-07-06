<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class MainView extends HTMLView
{
    private $defaultAction = null;
    private $tools = array();
    
    public function setDefaultAction ($defaultAction)
    {
        $this->defaultAction = $defaultAction;
    }
    
    public function setTools (array $tools)
    {
        $this->tools = $tools;
    }
    
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
        $this->addStyleFile($this->getBaseUrl() . "css/main.css");
        $this->addStyleFile($this->getBaseUrl() . "css/skin_google.css");
        $this->addStyleFile("http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700");
        $this->addScriptFile("//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.1.0/js/bootstrap.min.js");
        $this->addScriptFile($this->getBaseUrl() . "js/main.js");
        $this->buildBody();
    }
    
    protected function buildBody()
    {
        $this->getBodyTag()->setAttribute("class", "fixed-header fixed-navigation");
        $this->getBodyTag()->add($this->createHeader());
        $this->getBodyTag()->add($this->createNavigationPanel());
        $this->getBodyTag()->add($this->createMainPanel());
    }
    
    protected function createHeader()
    {
        return '
        <header id="header">
            <div id="logo-group"></div>
            <div class="pull-right">                
                <div id="hide-menu" class="btn-header pull-right"><span><a href="#" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a></span></div>
                <div id="logout" class="btn-header transparent pull-right"><span> <a href="' . $this->getUrl("site/logout")  . '" title="Cerrar sesión" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span></div>
                <div id="search-mobile" class="btn-header transparent pull-right"><span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span></div>
                <form action="#ajax/search.html" class="header-search pull-right">
                    <input id="search-fld" type="text" name="param" placeholder="Buscar ...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                    <a href="#" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
                </form>
                <div id="fullscreen" class="btn-header transparent pull-right"><span><a href="#" data-action="toggleFullscreen" title="Pantalla completa"><i class="fa fa-arrows-alt"></i></a></span></div>
            </div>
        </header>';
    }
    
    protected function createNavigationPanel()
    {
        $list = new Tag("ul");
        foreach ($this->tools as $tool)
        {
            $anchor = new Tag("a", array("href"=>$this->getUrl($tool->getAction())));
            $anchor->add (new Tag("i", array("class"=>"fa fa-lg fa-fw fa-" . $tool->getIcon()),""));
            $anchor->add (new Tag("span", array("class"=>"menu-item-parent"), $tool->getDescription()));
            $list->add (new Tag("li", $anchor));
        }
        $panel = new Tag("aside", array("id"=>"left-panel"));
        $panel->add ('<div class="login-info"><span><a href="#" id="show-shortcut" data-action="toggleShortcut"><span>john.doe</span></a></span></div>');
        $panel->add (new Tag("nav", $list));
        return $panel;
    }
    
    protected function createMainPanel()
    {
        return '<div id="main" role="main"><iframe id="content" src="' . $this->getUrl(!empty($this->defaultAction)?$this->defaultAction:"site/dashboard/") . '"></iframe></div>';
    }
}

?>