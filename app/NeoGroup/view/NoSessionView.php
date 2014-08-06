<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;

class NoSessionView extends HTMLView
{
    protected function build ()
    {
        parent::build();
        $this->getBodyTag()->add("No session !!");
    }
}

?>