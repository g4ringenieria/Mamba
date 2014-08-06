<?php

namespace NeoGroup\view;

use Exception;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class ErrorView extends HTMLView
{
    private $exception;
    
    protected function build ()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->bodyTag->add(new Tag("pre", "Error: " . $this->exception->getMessage() . "<br><br>" . print_r($this->exception->getTraceAsString(), true)));
    }
    
    public function setException (Exception $exception)
    {
        $this->exception = $exception;
    }
}

?>