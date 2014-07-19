<?php

namespace NeoGroup;

class CLIApplication extends \NeoPHP\cli\CLIApplication
{
    protected function initialize ()
    {
        parent::initialize();
        $this->setName ("NeoGroup");
    }
}

?>