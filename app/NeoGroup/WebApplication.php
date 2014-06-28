<?php

namespace NeoGroup;

class WebApplication extends \NeoPHP\web\WebApplication
{
    public function initialize ()
    {
        parent::initialize();
        $this->setName ("NeoGroup");
        $this->setDefaultControllerName("site");
        $this->setRestfull (true);
    }
}

?>
