<?php

namespace Mamba;

class WebApplication extends \NeoPHP\web\WebApplication
{
    protected function initialize ()
    {
        parent::initialize();
        $this->setName ("Mamba Solutions");
        $this->setDefaultControllerName("site/portal");
        $this->setRestfull (true);
    }
}

?>