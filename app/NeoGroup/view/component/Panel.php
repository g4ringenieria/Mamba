<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\Tag;

class Panel extends Tag 
{
    public function __construct(array $attributes = array()) 
    {
        static $idCounter = 0;
        $id = "panel_" . ($idCounter++); 
        $attributes = array_merge(array("contentWrapper"=>true, "type"=>"default"), $attributes);
        
        $classTokens = array();
        $classTokens[] = "panel";
        if (isset($attributes["type"]))
            $classTokens[] = "panel-" . $attributes["type"];
        if (!empty($attributes["autoWidth"]))
            $classTokens[] = "panel-autowidth";
        if (!empty($attributes["fullsize"]))
            $classTokens[] = "fullsize";
        $panelAttributes = array();
        $panelAttributes["class"] = implode(" ", $classTokens);
        parent::__construct("div", $panelAttributes);
        
        if (!empty($attributes["title"]))
        {
            $title = $attributes["title"];
            if (isset($attributes["collapsible"]))
                $title = new Tag("a", array("data-toggle"=>"collapse", "href"=>"#".$id), $title);
            $this->add (new Tag("div", array("class"=>"panel-heading"), $title));
        }
        $content = new Tag("div", array("class"=>"panel-content"), $attributes["content"]);
        $content = !empty($attributes["contentWrapper"])? new Tag("div", array("class"=>"panel-body"), $content) : $content;
        if (isset($attributes["collapsible"]))
            $content = new Tag("div", array("id"=>$id, "class"=>"panel-collapse collapse" . (isset($attributes["collapsed"])?'':' in')), $content);
        $this->add($content);
    }
}

?>