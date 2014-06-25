<?php

namespace NeoGroup;

use NeoGroup\databases\ProductionDatabase;

class WebApplication extends \NeoPHP\web\WebApplication
{
    public function initialize ()
    {
        parent::initialize();
        $this->setName ("NeoGroup");
        $this->setDefaultControllerName("site");
        $this->setRestfull (true);
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
