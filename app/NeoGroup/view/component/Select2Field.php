<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

class Select2Field extends HTMLComponent
{
    const SOURCETYPE_LOCAL = "local";
    const SOURCETYPE_REMOTE = "remote";
    
    private $id;
    private $name;
    private $placeholder;
    private $view;
    private $source;
    
    public function __construct(HTMLView $view)
    {
        static $idCounter = 0;
        $this->id = "selectField_" . ($idCounter++);
        $this->name = $this->id;
        $this->view = $view;
        $this->source = new stdClass();
        $this->source->type = self::SOURCETYPE_LOCAL;
        $this->source->valueField = "id";
        $this->source->displayField = "description";
        $this->source->data = array();
    }
    
    public function setSourceType ($sourceType)
    {
        $this->source->type = $sourceType;
    }
    
    public function setValueField ($valueField)
    {
        $this->source->valueField = $valueField;
    }
    
    public function setDisplayField ($displayField)
    {
        $this->source->displayField = $displayField;
    }
    
    public function setDisplayTemplate ($displayTemplate)
    {
        $this->source->displayTemplate = $displayTemplate;
    }
    
    public function setRemoteUrl ($remoteUrl)
    {
        $this->source->url = $remoteUrl;
    }
    
    public function setName ($name)
    {
        $this->name = $name;
    }
    
    public function setPlaceholder ($placeholder)
    {
        $this->placeholder = $placeholder;
    }
    
    public function setOptions (array $options)
    {
        foreach ($options as $id=>$option)
        {
            if ($option instanceof stdClass)
                $this->addOption($option);
            else
                $this->addOption($id, $option);
        }
    }
    
    public function addOption ($option, $optionValue=null)
    {
        if ($option instanceof stdClass)
        {
            $this->source->data[] = $option;
        }
        else
        {
            $valueField = $this->source->valueField;
            $displayField = $this->source->displayField;
            $newOption = new stdClass();
            $newOption->$valueField = $option;
            $newOption->$displayField = $optionValue;
            $this->source->data[] = $newOption;
        }
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScript('$("#' . $this->id . '")[0].source = ' . json_encode($this->source));
        $this->view->addScript('
            function createTemplateDescriptionFromSearchResult (template, item)
            { 
                for (var i in item)
                    template = template.replace(new RegExp("{" + i + "}", "g"), item[i]);
                return template; 
            }

            function showSelectResults (id)
            {
                var $selectField = $("#" + id);
                var $selectSearchField = $selectField.find("input[type=text]");
                var $selectDropdown = $selectField.find(".dropdown-menu");
                var $selectSearchList = $selectField.find(".list-group");
                
                $selectDropdown.removeClass("show");
                var searchQuery = $selectSearchField[0].value;
                var source = $selectField[0].source;
                if (source.type == "local")
                {
                    var showDropdown = false;
                    $selectSearchList.empty();
                    for (var i in source.data)
                    {
                        var dataItem = source.data[i];
                        var description = source.displayTemplate? createTemplateDescriptionFromSearchResult(source.displayTemplate, dataItem) : dataItem[source.displayField];
                        if (description.indexOf(searchQuery) >= 0)
                        {
                            showDropdown = true;
                            var $searchItem = $("<a href=\"#\" class=\"list-group-item\" value=\"" + dataItem[source.valueField] + "\">" + description + "</a>");
                            $searchItem.click(function () {alert("superv");});
                            $selectSearchList.append($searchItem);
                        }
                    }
                    if (showDropdown)
                        $selectDropdown.addClass("show");
                }
                else if (source.type == "remote")
                {
                    clearTimeout($selectField[0].searchProcess);
                    $selectField[0].searchProcess = setTimeout(function()
                    {
                        $.ajax(
                        {
                            url: source.url,
                            method: "GET",
                            data: { query: query },
                            success: function (data, status, xhr)
                            {
                                var showDropdown = false;
                                $selectSearchList.empty();
                                if (data && data.success == true && data.results)
                                {
                                    for (var i in data.results)
                                    {
                                        var dataItem = data.results[i];
                                        var description = source.displayTemplate? createTemplateDescriptionFromSearchResult(source.displayTemplate, dataItem) : dataItem[source.displayField];
                                        var $searchItem = $("<a href=\"#\" class=\"list-group-item\" value=\"" + dataItem[source.valueField] + "\">" + description + "</a>");
                                        $searchItem.click(function () {alert("superv");});
                                        $selectSearchList.append($searchItem);
                                        showDropdown = true;
                                    }
                                    if (showDropdown)
                                        $selectDropdown.addClass("show");
                                }
                            },
                            error: function () {},
                            timeout: function () {}
                        });
                    }, 500);
                }
            }
            




            
            $(document).ready(function() 
            {
                $button = $(".selectField .btn");
                $button.click(function () 
                {
                    showSelectResults ("selectField_0");
                });
            });
        '); 
        $this->view->addStyle('
            .selectField .dropdown-menu
            {
                width: 100%; 
                max-height: 100px;
                padding: 5px;
                margin: 0px;
            }
            
            .selectField .list-group
            {
                margin: 0px;
                padding: 0px;
            }
            
            .selectField .list-group .list-group-item
            {
                padding: 0px;
                margin: 0px;
                border: none;
            }
        ');
        $this->view->addScript('$("#' . $this->id . '")[0].source = ' . json_encode($this->source));
    }
    
    protected function createContent ()
    {
        $inputGroup = new Tag("div", array("class"=>"input-group"));
        $inputGroup->add (new Tag("input", array("type"=>"text", "class"=>"form-control")));
        $inputGroup->add (new Tag("span", array("class"=>"input-group-btn"), new Tag("button", array("class"=>"btn btn-default", "type"=>"button"), "<span class=\"glyphicon glyphicon-search\"></span>")));
        $hiddenField = new Tag("input", array("type"=>"hidden", "name"=>$this->name));
        $dropdownList = new Tag("div", array("class"=>"list-group"));
        $dropdown = new Tag("ul", array("class"=>"dropdown-menu"));
        $dropdown->add (new Tag("li", $dropdownList));
        $container = new Tag("div", array("id"=>$this->id, "class"=>"dropdown selectField"));
        $container->add ($inputGroup);
        $container->add ($dropdown);
        $container->add ($hiddenField);
        return $container;
    }
}

?>