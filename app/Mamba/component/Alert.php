<?php

namespace Mamba\component;

use NeoPHP\web\html\Tag;

class Alert extends Tag
{
    public function __construct($message = "", array $attributes = array())
    {
        $classTokens = array();
        $classTokens[] = "alert";
        $content = "";
        if (isset($attributes["type"]))
        {
            $classTokens[] = "alert-" . $attributes["type"];
            unset($attributes["type"]);
        }
        else
        {
            $classTokens[] = "alert-info";
        }
        if (isset($attributes["dismissable"]))
        {
            $classTokens[] = "alert-dismissible";
            unset($attributes["dismissable"]);
            $content .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        }
        $content .= $message;
        parent::__construct("div", array_merge(array("role"=>"alert", "class"=>implode(" ", $classTokens)), $attributes), $content);
        
    }
}

?>