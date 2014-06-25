<?php

namespace NeoGroup\databases;

use NeoPHP\data\Database;

class ProductionDatabase extends Database
{
    public function getDsn ()
    {
        return "pgsql:host=www.neogroup-solutions.com; dbname=neogroup";
    }
    
    public function getUsername ()
    {
        return "postgres";
    }
    
    public function getPassword ()
    {
        return "tuvieja.com";
    }
    
    public function getDriverOptions ()
    {
        return array();
    }
}

?>
