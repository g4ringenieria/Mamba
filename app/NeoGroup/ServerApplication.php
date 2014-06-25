<?php

namespace NeoGroup;

use NeoPHP\server\ServerApplication as FrameworkServerApplication;
use NeoGroup\processors\CommandsProcessor;
use NeoGroup\processors\ConnectionsCommandsProcessor;
use NeoGroup\processors\ConnectionsDebugProcessor;
use NeoGroup\processors\STD900DeviceProcessor;
use NeoGroup\processors\TT8750DeviceProcessor;
use NeoGroup\databases\ProductionDatabase;

class ServerApplication extends FrameworkServerApplication
{
    const SERVER_PORT = 8000;
    
    public function initialize ()
    {   
        parent::initialize();
        $this->setPort(8000);
        $this->addProcessor(new ConnectionsDebugProcessor($this));
        $this->addProcessor(new TT8750DeviceProcessor($this));
        $this->addProcessor(new STD900DeviceProcessor($this));
        $this->addProcessor(new CommandsProcessor($this));
        $this->addProcessor(new ConnectionsCommandsProcessor($this));
    }
    
    /**
     * Retorna la base de datos por defecto de la aplicación 
     * @return NeoGroup\databases\ProductionDatabase
     */
    public function getDatabase ()
    {
        if (empty($this->database))
            $this->database = new ProductionDatabase ();
        return $this->database;
    }
}

?>