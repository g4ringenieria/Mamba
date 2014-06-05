<?php

namespace NeoGroup;

use NeoPHP\data\Database;
use NeoPHP\server\ServerApplication as FrameworkServerApplication;
use NeoGroup\processors\CommandsProcessor;
use NeoGroup\processors\ConnectionsCommandsProcessor;
use NeoGroup\processors\ConnectionsDebugProcessor;
use NeoGroup\processors\STD900DeviceProcessor;
use NeoGroup\processors\TT8750DeviceProcessor;

class ServerApplication extends FrameworkServerApplication
{
    const SERVER_PORT = 8000;
    
    public function __construct ($port=0)
    {   
        parent::__construct(($port==0)? ServerApplication::SERVER_PORT : $port);
        $this->addProcessor(new ConnectionsDebugProcessor($this));
        $this->addProcessor(new TT8750DeviceProcessor($this));
        $this->addProcessor(new STD900DeviceProcessor($this));
        $this->addProcessor(new CommandsProcessor($this));
        $this->addProcessor(new ConnectionsCommandsProcessor($this));
    }
    
    /**
     * Obtiene una base de datos por su nombre
     * @param string Nombre de la base de datos
     * @return Database
     */
    public function getDatabase ($databaseName)
    {
        return $this->getCacheResource((isset($this->settings->databasesBaseNamespace)? $this->settings->databasesBaseNamespace : $this->getBaseNamespace() . "\\databases") . "\\" . $databaseName . "Database", array());
    }
    
    /**
     * Retorna la base de datos por defecto de la aplicación 
     * @return Database
     */
    public function getDefaultDatabase ()
    {
        return $this->getDatabase(isset($this->settings->databaseName)? $this->settings->databaseName : "production");
    }
}

?>