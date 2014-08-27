<?php

namespace Mamba\view;

use NeoPHP\web\html\Tag;

abstract class SidebarSiteView extends SiteView
{
    protected function buildBody()
    {
        $this->getBodyTag()->setAttribute("class", "sidebarMode");
        $this->getBodyTag()->add ($this->createSidebar());
        parent::buildBody();
    }
    
    protected function createSidebar ()
    {
        $sidebar = new Tag("div", array("id"=>"sidebar"));
        $this->buildSidebar ($sidebar);
        return new Tag("div", array("id"=>"sidebarwrapper"), $sidebar);
    }
    
    protected abstract function buildSidebar ($sidebar);
}

?>