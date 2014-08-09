<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\Tag;

class PasswordField extends Tag
{
    public function __construct(array $attributes = array())
    {
        parent::__construct("input", array_merge(array("type"=>"password", "class"=>"form-control"), $attributes));
    }
}

?>