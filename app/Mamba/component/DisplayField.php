<?php

namespace Mamba\component;

use NeoPHP\web\html\Tag;

class DisplayField extends Tag
{
    public function __construct($value)
    {
        parent::__construct("div", array("class"=>"controls"));
        $this->add(new Tag("p", array("class"=>"form-control-static"), strval($value)));
    }
}

?>