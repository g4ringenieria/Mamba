<?php

namespace Mamba\component;

use NeoPHP\web\html\Tag;

class TextField extends Tag
{
    public function __construct(array $attributes = array())
    {
        parent::__construct("input", array_merge(array("type"=>"text", "class"=>"form-control"), $attributes));
    }
}

?>