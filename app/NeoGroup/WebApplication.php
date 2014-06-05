<?php

namespace NeoGroup;

class WebApplication extends \NeoPHP\web\WebApplication
{
    public function __construct ()
    {
        parent::__construct("NeoGroup", true);
        $this->setDefaultControllerName("site");
    }
    
    /**
     * Obtiene una base de datos por su nombre
     * @param string Nombre de la base de datos
     * @return \NeoPHP\data\Database
     */
    public function getDatabase ($databaseName)
    {
        return $this->getCacheResource((isset($this->settings->databasesBaseNamespace)? $this->settings->databasesBaseNamespace : $this->getBaseNamespace() . "\\databases") . "\\" . $databaseName . "Database", array());
    }
    
    /**
     * Retorna la base de datos por defecto de la aplicación 
     * @return \NeoPHP\data\Database
     */
    public function getDefaultDatabase ()
    {
        return $this->getDatabase(isset($this->settings->databaseName)? $this->settings->databaseName : "production");
    }
}

?>
