<?php

namespace NeoGroup\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

class Typeahead extends HTMLComponent 
{
    private $id;
    private $attributes;
    private $componentAttributes;
    
    public function __construct(HTMLView $view, $source=array())
    {
        static $idCounter = 0;
        $this->id = "typeahead_" . ($idCounter++); 
        $this->view = $view;
        $this->attributes = array();
        $this->componentAttributes = new stdClass();
        $this->componentAttributes->source = $source;
    }
    
    public function setElements ($elements)
    {
        $this->componentAttributes->source = $elements;
    }
    
    public function addElement ($element)
    {
        $this->componentAttributes->source[] = $element;
    }
    
    public function setAttributes ($attributes)
    {
        $this->attributes = $attributes;
    }
    
    /**
     * Setea atributos de componente
     * Para ver una lista completa de atributos para establecer chequear
     * la siguiente pagina https://github.com/bassjobsen/Bootstrap-3-Typeahead
     * @param type $attributes
     */
    public function setComponentAttributes ($attributes)
    {
        $this->componentAttributes = $attributes;
    }
    
    protected function createContent ()
    {
        $tag = new Tag("input", array_merge(array("id"=>$this->id, "type"=>"text", "class"=>"form-control"), $this->attributes), '');
        return $tag;
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/bootstrap-typeahead/bootstrap-typeahead.min.js"); 
        $this->view->addScript('$(document).ready(function() { $("#' . $this->id . '").typeahead(' . json_encode($this->componentAttributes) . ');});');
    }
}

?>
