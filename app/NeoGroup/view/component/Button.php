<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\Tag;

class Button extends Tag
{
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
        if (isset($attributes["active"]))
        {
            $classTokens[] = "active";
            unset($attributes["active"]);
        }
        if (isset($attributes["action"]))
        {
            $attributes["onclick"] = "$(this).closest('form').attr('action','" . $attributes["action"] . "').submit(); return true;";
            unset($attributes["action"]);
        }
        $buttonAttributes = array();
        $buttonAttributes["type"] = "button";
        $buttonAttributes["class"] = implode(" ", $classTokens);
        $buttonAttributes = array_merge($buttonAttributes, $attributes);
        parent::__construct("button", $buttonAttributes, $text);
    }
}

?>