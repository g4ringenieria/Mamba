<?php

namespace NeoGroup\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\Tag;
use stdClass;

class Button extends HTMLComponent
{
    private $actions;
    private $type;
    
    public function __construct($text=null, $attributes=array(), $action=null)
    {
        $this->actions = array();
        $this->type = "default";
        if (!empty($text))
            $this->addAction ($text, $attributes, $action);
    }
    
    public function setType ($type)
    {
        $this->type = $type;
    }
    
    public function setText ($text)
    {
        $this->buttonText = $text;
    }
    
    public function addAction ($text, $attributes=array(), $action=null)
    {
        $actionObject = new stdClass();
        $actionObject->text = $text;
        $actionObject->attributes = $attributes;
        $actionObject->action = $action;
        $this->actions[] = $actionObject;
    }
    
    protected function createContent ()
    {
        $button = null;
        if (sizeof($this->actions) == 1)
        {
            $action = $this->actions[0];
            $attributes = array("class"=>"btn btn-" . $this->type);
            if (isset($action->action))
                $attributes["onclick"] = "var form = $(this).closest('form'); form[0].action = '" . $action->action . "'; form[0].submit(); return true;";
            $button = new Tag("button", array_merge($attributes, $action->attributes), isset($this->buttonText)? $this->buttonText : $action->text);
        }
        else
        {
            $button = new Tag("div", array("class"=>"btn-group"));
            $attributes = array("class"=>"btn btn-" . $this->type);
            if (isset($this->actions[0]->action))
                $attributes["onclick"] = "var form = $(this).closest('form'); form[0].action = '" . $this->actions[0]->action . "'; form[0].submit(); return true;";
            $button->add (new Tag("button", array_merge($attributes, $this->actions[0]->attributes), isset($this->buttonText)? $this->buttonText : $this->actions[0]->text));
            $button->add (new Tag("button", array("class"=>"btn btn-" . $this->type . " dropdown-toggle", "data-toggle"=>"dropdown"), '<span class="caret"></span>'));
            
            $actionsList = new Tag("ul", array("class"=>"dropdown-menu", "role"=>"menu"));
            foreach ($this->actions as $action)
            {   
                $attributes = array();
                $attributes["href"] = "#";
                if (isset($action->action))
                    $attributes["onclick"] = "var form = $(this).closest('form'); form[0].action = '" . $action->action . "'; form[0].submit(); return true;";
                $actionsList->add (new Tag("li", new Tag("a", $attributes, $action->text)));
            }
            $button->add ($actionsList);
        }
        return $button;
    }
}

?>
