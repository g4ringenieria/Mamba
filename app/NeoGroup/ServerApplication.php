<?php

namespace NeoGroup;

use NeoGroup\processor\CommandsProcessor;
use NeoGroup\processor\ConnectionsCommandsProcessor;
use NeoGroup\processor\ConnectionsDebugProcessor;
use NeoGroup\processor\STD900DeviceProcessor;
use NeoGroup\processor\TT8750DeviceProcessor;
use NeoPHP\server\ServerApplication as FrameworkServerApplication;

class ServerApplication extends FrameworkServerApplication
{
    public function initialize ()
    {   
        parent::initialize();
        $this->setPort(8000);
        $this->addProcessor(new ConnectionsDebugProcessor());
        $this->addProcessor(new TT8750DeviceProcessor());
        $this->addProcessor(new STD900DeviceProcessor());
        $this->addProcessor(new CommandsProcessor());
        $this->addProcessor(new ConnectionsCommandsProcessor());
    }
}

?>