<?php

namespace NeoGroup;

use NeoPHP\cli\CLIApplication;

class CommandApplication extends CLIApplication
{
    protected function initialize ()
    {
        parent::initialize();
        $this->setName ("NeoGroup");
    }
}

?>