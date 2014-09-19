<?php

namespace Mamba\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\Tag;
use stdClass;

class TabPanel extends HTMLComponent
{
    private $id;
    private $tabs;
    
    public function __construct()
    {
        static $idCounter = 0;
        $this->id = "tabpanel_" . ($idCounter++); 
        $this->tabs = array();
    }
    
    public function addTab ($title, $content, $active=false)
    {
        $tab = new stdClass();
        $tab->title = $title;
        $tab->content = $content;
        $tab->active = $active;
        $this->tabs[] = $tab;
    }
    
    protected function createContent ()
    {
        $navs = new Tag("ul", array("class"=>"nav nav-tabs"));
        $tabs = new Tag("div", array("class"=>"tab-content"));
        $activeNav = null;
        $activeTab = null;
        for ($i = 0; $i < sizeof($this->tabs); $i++)
        {
            $tabData = $this->tabs[$i];
            $tabId = !empty($tabData->id)? $tabData->id : ($this->id . "_" . $i);
            $nav = new Tag("li", new Tag("a", array("href"=>"#".$tabId, "data-toggle"=>"tab"), $tabData->title));
            $tab = new Tag("div", array("class"=>"tab-pane fade", "id"=>$tabId), $tabData->content);
            if ($tabData->active == true || $i == 0)
            {
                $activeNav = $nav;
                $activeTab = $tab;
            }
            $navs->add($nav);
            $tabs->add($tab);
        }
        $activeNav->setAttribute("class", "active");
        $activeTab->setAttribute("class", $activeTab->getAttribute("class") . " in active");
        return new Tag("div", array("class"=>"tab-panel"), array($navs, $tabs));
    }
}

?>
