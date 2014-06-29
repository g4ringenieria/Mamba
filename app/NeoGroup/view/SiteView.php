<?php

namespace NeoGroup\view;

use NeoPHP\web\html\Tag;

abstract class SiteView extends BootstrapView
{
    protected function build ()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.0.3/css/font-awesome.css");
        $this->addStyleFile($this->getBaseUrl() . "css/style.css?_dc=7");
        $this->addScript('
            function toggleSideBar ()
            {
                $(".sidebar").fadeToggle();
            }
            
            $(function() 
            {
                $(window).bind("load resize", function() 
                {
                    if ($(this).width() < 748) 
                    {
                        $(".sidebar").fadeOut();
                    } 
                    else 
                    {
                        $(".sidebar").fadeIn();
                    }
                })
            })
        ');
        $this->buildHead();
        $this->buildBody();
    }
    
    protected function buildHead ()
    {
    }
    
    protected function buildBody ()
    {
        $this->bodyTag->add($this->createHeader());
        $this->bodyTag->add($this->createContent());
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
                    <a href="' . $this->getUrl('site/dashboard/') . '" class="navbar-brand"> ' . $this->getApplication()->getName() . '</a>
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
    
    protected function createContent()
    {
        $container = new Tag("div", array("class"=>"container-fluid"));
        $container->add ($this->createSideBar());
        $container->add ($this->createPage());
        return $container;
    }
    
    protected function createSideBar ()
    {
        $tools = unserialize($this->getSession()->tools);
        $sidebar = new Tag("div", array("class"=>"col-xs-5 col-sm-3 col-md-2 sidebar"));
        $sidebarList = new Tag("ul", array("class"=>"nav nav-sidebar"));
        $sidebarList->add ($this->createSideBarMenuItem ("Dashboard", "site/dashboard/", "dashboard"));
        if(is_array($tools))
        {
            foreach ($tools as $tool)
            {
                $sidebarList->add ($this->createSideBarMenuItem ($tool->getDescription(), $tool->getAction(), $tool->getIcon()));
            }
        }
        $sidebarList->add ('
            <li class="visible-xs"><a href="' . $this->getUrl("site/account/") . '"><i class="fa fa-user"></i> Mi Cuenta</a></li>
            <li class="visible-xs"><a href="' . $this->getUrl("site/settings/") . '"><i class="fa fa-gear"></i> Configuración</a></li>
            <li class="visible-xs"><a href="' . $this->getUrl("site/logout") . '"><i class="fa fa-power-off"></i> Salir</a></li>
        ');        
        $sidebar->add ($sidebarList);
        return $sidebar;
    }
    
    protected function createSideBarMenuItem ($title, $action, $iconName=null)
    {
        $content = "";
        if ($iconName != null && !empty($iconName))
            $content .= '<i class="fa fa-' . $iconName . '"></i> ';
        $content .= $title;
        $menuItem = new Tag("li", array(), new Tag("a", array("href"=>$this->getUrl($action)), $content));
        return $menuItem;
    }
    
    protected function createPage ()
    {
        $page = new Tag("div", array("class"=>"col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"));
        $title = $this->getPageTitle();
        if (!empty($title))
            $page->add (new Tag("h1", array("class"=>"page-header"), $title));
        $this->buildPage ($page);
        return $page;
    }
    
    protected function getPageTitle ()
    {
        return null;
    }
    
    protected abstract function buildPage ($page);
}

?>