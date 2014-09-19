<?php

namespace Mamba\component;

use NeoPHP\web\html\HTMLComponent;

class HighchartSerie extends HTMLComponent
{
    const TYPE_LINE = "line";
    const TYPE_COLUMN = "column";
    
    private $type;
    private $name;
    private $data;
    
    public function __construct()
    {
        
    }
    
    public function getType() {
        return $this->type;
    }

    public function getName() {
        return $this->name;
    }

    public function getData() {
        return $this->data;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setData($data) {
        $this->data = $data;
    }


}
?>