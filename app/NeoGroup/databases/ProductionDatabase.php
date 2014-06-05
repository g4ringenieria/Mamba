<?php

namespace NeoGroup\databases;

use NeoPHP\data\Database;

class ProductionDatabase extends Database
{
    public function getDsn ()
    {
        return "pgsql:host=localhost; dbname=NeoGroup";
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
