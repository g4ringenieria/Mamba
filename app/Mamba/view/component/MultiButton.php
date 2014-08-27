<?php

namespace Mamba\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\Tag;
use stdClass;

class MultiButton extends HTMLComponent
{
    private $text;
    private $actions;
    private $attributes;
    
    public function __construct($text="", $attributes=array())
    {
        $classTokens = array();
        $classTokens[] = "btn";
        if (isset($attributes["class"]))
        {
            $classTokens[] = "btn-" . $attributes["class"];
            unset($attributes["class"]);
        }
        else
        {
            $classTokens[] = "btn-default";
        }
        if (isset($attributes["size"]))
        {
            $classTokens[] = "btn-" . $attributes["size"];
            unset($attributes["size"]);
        }
        if (isset($attributes["block"]))
        {
            $classTokens[] = "btn-block";
            unset($attributes["block"]);
        }
        
        $buttonAttributes = array();
        $buttonAttributes["type"] = "button";
        $buttonAttributes["class"] = implode(" ", $classTokens);
        $buttonAttributes = array_merge($buttonAttributes, $attributes);
        $this->text = $text;
        $this->actions = array();
        $this->attributes = $buttonAttributes;
    }
    
    public function setText ($text)
    {
        $this->buttonText = $text;
    }
    
    public function addAction ($text, $attributes=array())
    {
        $actionObject = new stdClass();
        $actionObject->text = $text;
        $actionObject->attributes = $attributes;
        $this->actions[] = $actionObject;
    }
    
    protected function createContent ()
    {
        $buttonGroup = new Tag("div", array("class"=>"btn-group"));
        $attributes = $this->attributes;
        if (isset($this->actions[0]->attributes["action"]))
            $attributes["onclick"] = "$(this).closest('form').attr('action','" . $this->actions[0]->attributes["action"] . "').submit(); return true;";
        $buttonGroup->add (new Tag("button", $attributes, $this->text));
        $buttonGroup->add (new Tag("button", array("class"=>$attributes["class"] . " dropdown-toggle", "data-toggle"=>"dropdown"), '<span class="caret"></span>'));

        $actionsList = new Tag("ul", array("class"=>"dropdown-menu", "role"=>"menu"));
        foreach ($this->actions as $action)
        {   
            $attributes = array();
            $attributes["href"] = "#";
            if (isset($action->attributes["action"]))
                $attributes["onclick"] = "$(this).closest('form').attr('action','" . $action->attributes["action"] . "').submit(); return true;";
            $actionsList->add (new Tag("li", new Tag("a", $attributes, $action->text)));
        }
        $buttonGroup->add ($actionsList);
        return $buttonGroup;
    }
}

?>
