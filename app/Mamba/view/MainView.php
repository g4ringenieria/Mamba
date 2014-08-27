<?php

namespace Mamba\view;

use NeoPHP\util\StringUtils;
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
        $this->addMeta(array("charset" => "utf-8"));
        $this->addMeta(array("name" => "viewport", "content" => "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.1.0/css/font-awesome.min.css");
        $this->addScriptFile($this->getBaseUrl() . "js/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.2.0/js/bootstrap.min.js");
        $this->addStyleFile($this->getBaseUrl() . "assets/bootstrap-3.2.0/css/bootstrap.cerulean.min.css");
        $this->addStyleFile($this->getBaseUrl() . "css/main.css");
        $this->addScriptFile($this->getBaseUrl() . "js/main.js");
        $this->buildBody();
    }

    protected function buildBody()
    {
        $this->getBodyTag()->add($this->createHeader());
        $this->getBodyTag()->add($this->createContent());
    }

    protected function createHeader ()
    {   
        return '
        <nav id="header" class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">' . $this->getApplication()->getName() . '</a>
                </div>
                <div class="navbar-collapse collapse navbar-responsive-collapse">
                    ' . $this->createMainMenu() . '
                    ' . $this->createUserMenu() . '
                </div>
            </div>
        </nav>';
    }
    
    protected function getToolsAsArray ()
    {
        $array = array();
        foreach ($this->tools as $tool)
        {
            $path = $tool->getPath();
            if (!StringUtils::startsWith($path, "/"))
                $path = "/" . $path;
            if (!StringUtils::endsWith($path, "/"))
                $path .= "/";
            
            $tokens = explode("/", $path);
            unset($tokens[sizeof($tokens) - 1]);
            unset($tokens[0]);
            $arrayPointer = &$array;
            foreach ($tokens as $token)
            {
                $menuItem = array();
                $menuItem["title"] = $token;
                $menuItem["items"] = array();
                $menuItemKey = md5("m_".$token);
                if (!isset($arrayPointer[$menuItemKey]))
                    $arrayPointer[$menuItemKey] = $menuItem;
                $arrayPointer = &$arrayPointer[$menuItemKey]["items"];
            }
            
            $menuItem = array();
            $menuItem["title"] = $tool->getDescription();
            $menuItem["action"] = $tool->getAction();
            $menuItemKey = md5("mi_".$tool->getDescription());
            if (!isset($arrayPointer[$menuItemKey]))
                $arrayPointer[$menuItemKey] = $menuItem;
        }
        return $array;
    }
    
    protected function createMainMenu()
    {
        $toolsArray = $this->getToolsAsArray();
        $mainMenu = new Tag("ul", array("class"=>"nav navbar-nav navbar-left"));
        foreach ($toolsArray as $tool)
            $mainMenu->add ($this->createMenuItem ($tool));
        return $mainMenu;
    }
    
    protected function createUserMenu()
    {
        return '
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">            
                <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-user"></i> ' . $this->getSession()->firstName . ' ' . $this->getSession()->lastName . ' <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#" onclick="loadUrl(\'' . $this->getUrl("site/account/") . '\')"><i class="fa fa-user"></i> Mi Cuenta</a></li>
                    <li><a href="#" onclick="loadUrl(\'' . $this->getUrl("site/settings/") . '\')"><i class="fa fa-gear"></i> Configuración</a></li>
                    <li class="divider"></li>
                    <li><a href="' . $this->getUrl("site/main/logout") . '"><i class="fa fa-power-off"></i> Salir</a></li>
                </ul>
            </li>
        </ul>';
    }
    
    protected function createMenuItem ($element)
    {
        $anchor = new Tag("a", array("href"=>isset($element["action"])? $this->getUrl($element["action"]) : "#"));
        if (isset($element["icon"]))
            $anchor->add (new Tag("i", array("class"=>"fa fa-" . $element["icon"]),""));
        $anchor->add ("&nbsp;" . $element["title"]);
        $listItem = new Tag("li");
        $listItem->add($anchor);
        if (isset($element["items"]))
        {
            $anchor->setAttribute("class", "dropdown-toggle");
            $anchor->setAttribute("data-toggle", "dropdown");
            $anchor->add (" <b class=\"caret\"></b>");
            $list = new Tag("ul", array("class"=>"dropdown-menu"), "");
            foreach ($element["items"] as $item)
                $list->add ($this->createMenuItem($item));
            $listItem->add($list);
        }
        return $listItem;
    }
    
    protected function createContent()
    {
        return '<div id="mainContent"><iframe id="iframe" src="' . $this->getUrl("site/dashboard/") . '"></iframe></div>';
    }
}

?>