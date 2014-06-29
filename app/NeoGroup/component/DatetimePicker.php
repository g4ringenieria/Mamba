<?php

namespace NeoGroup\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

/**
 * DateTimePicker
 * Para mayor información consultar la url https://github.com/Eonasdan/bootstrap-datetimepicker
 */
class DatetimePicker extends HTMLComponent
{
    private $id;
    private $attributes;
    private $dateEnabled;
    private $timeEnabled;
    private $dateFormat;
    private $timeFormat;
    private $useMinutes;
    private $useSeconds;
    private $value;
    private $showPickerButton;
    
    public function __construct(HTMLView $view)
    {
        static $idCounter = 0;
        $this->id = "datetimepicker_" . ($idCounter++);        
        $this->view = $view;
        $this->attributes = array();
        $this->dateEnabled = true;
        $this->timeEnabled = true;
        $this->dateFormat = null;
        $this->timeFormat = null;
        $this->useMinutes = true;
        $this->useSeconds = false;
        $this->value = null;
        $this->showPickerButton = true;
    }
    
    public function setAttributes ($attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function setDateEnabled ($dateEnabled)
    {
        $this->dateEnabled = $dateEnabled;
    }
    
    public function setTimeEnabled ($timeEnabled)
    {
        $this->timeEnabled = $timeEnabled;
    }
    
    public function setDateFormat ($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }
    
    public function setTimeFormat ($timeFormat)
    {
        $this->timeFormat = $timeFormat;
    }
    
    public function setUseMinutes ($useMinutes)       
    {
        $this->useMinutes = $useMinutes;
    }
    
    public function setUseSeconds ($useSeconds)
    {
        $this->useSeconds = $useSeconds;
    }
    
    public function setValue ($value)
    {
        $this->value = $value;
    }
    
    public function setShowPickerButton ($showPickerButton)
    {
        $this->showPickerButton = $showPickerButton;
    }
    
    protected function createContent ()
    {
        $format = "";
        if ($this->dateEnabled)
            $format .= isset($this->dateFormat)? $this->dateFormat : "YYYY/MM/DD";
        if ($this->timeEnabled)
        {
            if (!empty($format))
                $format .= " ";
            if (isset($this->timeFormat))
            {
                $format .= $this->timeFormat;
            }
            else
            {
                $format .= "HH";
                if ($this->useMinutes)
                    $format .= ":mm";
                if ($this->useSeconds)
                    $format .= ":ss";
            }
        }
        
        if ($this->showPickerButton)
        {
            $content = new Tag("div", array("class"=>"input-group date", "id"=>$this->id));
            $content->add (new Tag("input", array_merge(array("class"=>"text", "class"=>"form-control", "data-format"=>$format), $this->attributes)));
            $content->add (new Tag("span", array("class"=>"input-group-addon"), new Tag("span", array("class"=>"glyphicon glyphicon-calendar"), "")));
        }
        else
        {
            $content = new Tag("input", array_merge(array("id"=>$this->id, "type"=>"text", "class"=>"form-control", "data-format"=>$format), $this->attributes));
        }
        return $content;
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScriptFile($this->view->getBaseUrl() . "js/moment.min.js");
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"); 
        $this->view->addStyleFile($this->view->getBaseUrl() . "assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css");
        $this->view->addScript('
            $(document).ready(function() 
            { 
                $("#' . $this->id . '").datetimepicker(
                {
                    pickDate: ' . ($this->dateEnabled?"true":"false") . ',
                    pickTime: ' . ($this->timeEnabled?"true":"false") . ',
                    useMinutes: ' . ($this->useMinutes?"true":"false") . ',
                    useSeconds: ' . ($this->useSeconds?"true":"false") . ',  
                    defaultDate: ' . (isset($this->value)? ('"' . $this->value . '"'):"null") . ',  
                });
            });
        ');
    }
}

?>
