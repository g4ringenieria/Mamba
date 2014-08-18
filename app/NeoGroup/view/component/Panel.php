<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\Tag;

class Panel extends HTMLComponent 
{
    private $id;
    private $title;
    private $content;
    private $type;
    private $collapsible;
    private $collapsed;
    private $addBodyWrapper;
    
    public function __construct($title=null, $content=null) 
    {
        static $idCounter = 0;
        $this->id = "panel_" . ($idCounter++); 
        $this->title = $title;
        $this->content = $content;
        $this->type = "default";
        $this->collapsible = false;
        $this->collapsed = false;
        $this->addBodyWrapper = true;
    }
    
    public function setTitle ($title) 
    {
        $this->title = $title;
    }

    public function setContent ($content) 
    {
        $this->content = $content;
    }
    
    public function setType ($type)
    {
        $this->type = $type;
    }
    
    public function setAddBodyWrapper ($addBodyWrapper)
    {
        $this->addBodyWrapper = $addBodyWrapper;
    }
    
    public function setCollapsible ($collapsible)
    {
        $this->collapsible = $collapsible;
    }
    
    public function setCollapsed ($collapsed)
    {
        $this->collapsed = $collapsed;
    }
    
    protected function createContent ()
    {  
        $panel = new Tag("div", array("class"=>"panel panel-" . $this->type));
        if (!empty($this->title))
        {
            $title = $this->title;
            if ($this->collapsible)
                $title = new Tag("a", array("data-toggle"=>"collapse", "href"=>"#".$this->id), $title);
            $panel->add (new Tag("div", array("class"=>"panel-heading"), $title));
        }
        $content = new Tag("div", array("class"=>"panel-content"), $this->content);
        $content = $this->addBodyWrapper? new Tag("div", array("class"=>"panel-body"), $content) : $content;
        if ($this->collapsible)
            $content = new Tag("div", array("id"=>$this->id, "class"=>"panel-collapse collapse" . ($this->collapsed?'':' in')), $content);
        $panel->add ($content);
        return $panel;
    }
}

?>
