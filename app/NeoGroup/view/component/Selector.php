<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

/**
 * Utiliza el componente Selectize.js
 * Para más información ver la pagina web http://brianreavis.github.io/selectize.js/
 * Ejemplos de uso:
 * 
 * //Ejemplo 1: local
 * $selector = new Selector($this);
 * $selector->setAttributes(array("placeholder"=>"Seleccione un número ..."));
 * $selector->setOptions(array("uno", "rama"=>"dos", "tres", "cuatro", "cinco"));
 * 
 * //Ejemplo 2: carga remota
 * $selector = new Selector($this);
 * $selector->setAttributes(array("placeholder"=>"Seleccione una persona ..."));
 * $selector->setRemoteUrl("getPersons.php");
 * $selector->setValueField("id");
 * $selector->setSearchFields(array("name", "lastname"));
 * $selector->setTemplate("<div><span>{id}: {name} {lastname}</span></div>");
 * 
 * //Ejemplo de resultado obtenido de getPersons.php
 * [
 *     {
 *         "id": 119,
 *         "name": "tito",
 *         "lastname": "paredes"
 *     },
 *     {
 *         "id": 78,
 *         "name": "pippo",
 *         "lastname": "chippolaz"
 *     },
 *     {
 *         "id": 209,
 *         "name": "ramon",
 *         "lastname": "demusicoligero"
 *     }
 * ]
 */
class Selector extends HTMLComponent 
{
    private $id;
    private $attributes;
    private $creationEnabled;
    private $multipleSelection;
    private $maxOptionsSelected;
    private $valueField;
    private $displayField;
    private $searchFields;
    private $options;
    private $remoteUrl;
    private $requestMethod;
    private $requestParams;
    private $requestQueryParamName;
    private $requestQueryFieldsParamName;
    private $optionTemplate;
    private $optionRendererFunction;
    private $itemTemplate;
    private $itemRendererFunction;
    private $template;
    
    public function __construct(HTMLView $view)
    {
        static $idCounter = 0;
        $this->id = "entitySelector_" . ($idCounter++); 
        $this->view = $view;
        $this->attributes = array();
        $this->creationEnabled = false;
        $this->multipleSelection = false;
        $this->maxOptionsSelected = 0;
        $this->valueField = "value";
        $this->displayField = "text";
        $this->searchFields = null;
        $this->options = array();
        $this->remoteUrl = null;
        $this->requestMethod = "GET";
        $this->requestParams = new stdClass();
        $this->requestQueryParamName = "query";
        $this->requestQueryFieldsParamName = "queryFields";
        $this->optionTemplate = null;
        $this->optionRendererFunction = null;
        $this->itemTemplate = null;
        $this->itemRendererFunction = null;
        $this->template = null;
        $this->value = null;
    }
    
    public function setAttributes ($attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function setCreationEnabled ($creationEnabled)
    {
        $this->creationEnabled = $creationEnabled;
    }
    
    public function setMultipleSelection ($multiple)
    {
        $this->multipleSelection = $multiple;
    }
    
    public function setMaxOptionsSelected ($maxOptionsSelected)
    {
        $this->maxOptionsSelected = $maxOptionsSelected;
    }
    
    public function setValueField ($valueField)
    {
        $this->valueField = $valueField;
    }
    
    public function setDisplayField ($displayField)
    {
        $this->displayField = $displayField;
    }
    
    public function setSearchFields ($searchFields)
    {
        if (!is_array($searchFields))
            $searchFields = array($searchFields);
        $this->searchFields = $searchFields;
    }
    
    public function setOptions ($options)
    {
        $this->options = $options;
    }
    
    public function setRemoteUrl ($remoteUrl)
    {
        $this->remoteUrl = $remoteUrl;
    }
    
    public function setRequestMethod ($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }
    
    public function setRequestParams ($requestParams)
    {
        $this->requestParams = $requestParams;
    }
    
    public function setRequestQueryParamName ($requestQueryParamName)
    {
        $this->requestQueryParamName = $requestQueryParamName;
    }
    
    public function setOptionTemplate ($optionTemplate)
    {
        $this->optionTemplate = $optionTemplate;
    }
    
    public function setOptionRendererFunction ($optionRendererFunction)
    {
        $this->optionRendererFunction = $optionRendererFunction;
    }
    
    public function setItemTemplate ($itemTemplate)
    {
        $this->itemTemplate = $itemTemplate;
    }
    
    public function setItemRendererFunction ($itemRendererFunction)
    {
        $this->itemRendererFunction = $itemRendererFunction;
    }
    
    public function setTemplate ($template)
    {
        $this->template = $template;
    }
    
    public function setValue ($value)
    {
        $this->value = $value;
    }
    
    protected function createContent ()
    {
        return new Tag("select", array_merge(array("id"=>$this->id), $this->attributes), '');
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScriptFile ($this->view->getBaseUrl() . "assets/bootstrap-selectize/js/standalone/selectize.min.js");
        $this->view->addStyleFile ($this->view->getBaseUrl() . "assets/bootstrap-selectize/css/selectize.bootstrap3.css");
        $this->view->addScript ('
            function selectizeLoad (url, params, callback)
            {
                $.ajax(
                {
                    url: url,
                    data: params,
                    error: function() 
                    {
                        callback();
                    },
                    success: function(data) 
                    {
                        try { if (data.success) callback(data.results); } catch (e) {}
                    }
                });
            }
            
            function selectizeTemplateItem (template, item, escape)
            { 
                for (var i in item)
                    template = template.replace(new RegExp("{" + i + "}", "g"), escape(item[i]));
                return template; 
            }
        ');
        
        $selectorOptions = array();
        if ($this->creationEnabled === true)
            $selectorOptions["create"] = "true";
        if ($this->multipleSelection === true)
            $selectorOptions["maxItems"] = ($this->maxOptionsSelected > 0)? $this->maxOptionsSelected : "null";
        $selectorOptions["valueField"] = '"' . $this->valueField . '"';
        $selectorOptions["labelField"] = '"' . $this->displayField . '"';
        $selectorOptions["searchField"] = json_encode(($this->searchFields != null)? $this->searchFields : array($this->displayField));
        
        $valueField = $this->valueField;
        $displayField = $this->displayField;
        $options = array();
        foreach ($this->options as $key=>$value)
        {
            if (is_object($value))
            {
                $options[] = $value;
            }
            else
            {
                $options[$key] = new stdClass();        
                $options[$key]->$valueField = $key;
                $options[$key]->$displayField = $value;
            }
        }
        $selectorOptions["options"] = json_encode(array_values($options));
        
        if ($this->remoteUrl != null)
        {
            $selectorOptions["load"] = 'function(query, callback) { var params = ' . json_encode($this->requestParams) . '; params.' . $this->requestQueryParamName . ' = query; selectizeLoad ("' . $this->remoteUrl . '", params, callback); }';
        }
        
        if ($this->template != null)
        {
            $this->optionTemplate = $this->template;
            $this->itemTemplate = $this->template;
        }
        
        if ($this->optionTemplate != null)
        {
            $this->optionRendererFunction = 'function(item, escape) { return selectizeTemplateItem("' . $this->optionTemplate . '",item, escape); }';
        }
        
        if ($this->itemTemplate != null)
        {
            $this->itemRendererFunction = 'function(item, escape) { return selectizeTemplateItem("' . $this->itemTemplate . '",item, escape); }';
        }
        
        if ($this->optionRendererFunction != null || $this->itemRendererFunction != null)
        {
            $functions = array();
            if ($this->optionRendererFunction != null)
                $functions[] = "option: " . $this->optionRendererFunction;
            if ($this->itemRendererFunction != null)
                $functions[] = "item: " . $this->itemRendererFunction;
            $selectorOptions["render"] = "{" . implode(",", $functions) . "}";
        }
        
        $selectorScript = '$(document).ready(function() {'; 
        $selectorScript .= 'var $selector = $("#' . $this->id . '").selectize({' . implode(",", array_map(create_function('$v,$k', 'return $k . ": " . $v;'), array_values($selectorOptions), array_keys($selectorOptions))) . '}); '; 
        if ($this->remoteUrl != null)
        {
            $selectorScript .= 'var selector = $selector[0].selectize;';
            $selectorScript .= 'selector.load(function (callback) {';
            $selectorScript .= 'var params = ' . json_encode($this->requestParams) . ';';
            if ($this->value != null)
                $selectorScript .= 'params.' . $this->valueField . ' = ' . $this->value . ';';
            $selectorScript .= 'selectizeLoad ("' . $this->remoteUrl . '", params, function (results) {';
            $selectorScript .= 'callback(results);';
            if ($this->value != null)
                $selectorScript .= 'selector.setValue(' . $this->value . ');';
            $selectorScript .= '});';
            $selectorScript .= '});';
        }
        $selectorScript .= '});';
        $this->view->addScript($selectorScript);
    }
}

?>
