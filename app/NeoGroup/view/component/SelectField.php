<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

class SelectField extends HTMLComponent
{
    const SOURCETYPE_LOCAL = "local";
    const SOURCETYPE_REMOTE = "remote";
    
    private $id;
    private $name;
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
        $this->view->addScript('
            function createTemplateDescriptionFromSearchResult (template, item)
            { 
                for (var i in item)
                    template = template.replace(new RegExp("{" + i + "}", "g"), item[i]);
                return template; 
            }

            function createSearchResultItem (value, text)
            {
                return "<a href=\"#\" class=\"list-group-item selectField-searchItem\" value=\"" + value + "\" onclick=\"selectSearchResultItem($(this)); return false;\">" + text + "</a>";
            }
            
            function selectSearchResultItem ($searchResultItem)
            {
                var value = $searchResultItem.attr("value");
                var displayValue = $searchResultItem[0].innerHTML;
                var $selectField = $searchResultItem.closest(".selectField");
                var $selectButtonText = $selectField.find(".selectField-button .pull-left");
                var $selectDropdown = $selectField.find(".selectField-dropdown");
                var $hiddenValueField = $selectField.find(".selectField-hiddenValue");
                var $hiddenDisplayValueField = $selectField.find(".selectField-hiddenDisplayValue");
                $selectButtonText.html(displayValue);
                $hiddenValueField.val (value);
                $hiddenDisplayValueField.val (displayValue);
                $selectDropdown.removeClass("show");
            }

            function searchResults ($selectField, query)
            {
                if (query == null)
                {
                    var $selectSearchField = $selectField.find("input");
                    query = $selectSearchField[0].value;
                }

                var $selectSearchList = $selectField.find(".selectField-searchList");
                var source = $selectField[0].source;
                if (source.type == "local")
                {
                    $selectSearchList.empty();
                    for (var i in source.data)
                    {
                        var dataItem = source.data[i];
                        var description = source.displayTemplate? createTemplateDescriptionFromSearchResult(source.displayTemplate, dataItem) : dataItem[source.displayField];
                        if (description.indexOf(query) >= 0)
                            $selectSearchList.append(createSearchResultItem(dataItem[source.valueField], description));
                    }
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
                                $selectSearchList.empty();
                                if (data && data.success == true && data.results)
                                {
                                    for (var i in data.results)
                                    {
                                        var dataItem = data.results[i];
                                        var description = source.displayTemplate? createTemplateDescriptionFromSearchResult(source.displayTemplate, dataItem) : dataItem[source.displayField];
                                        $selectSearchList.append(createSearchResultItem(dataItem[source.valueField], description));
                                    }
                                }
                            },
                            error: function ()
                            {
                            },
                            timeout: function ()
                            {
                            }
                        });
                    }, 500);
                }
            }

            $(document).ready(function() 
            {
                var $selectButton = $(".selectField-button");   
                $selectButton.click (function (event) 
                { 
                    var $selectField = $(this).closest(".selectField");
                    var $selectDropdown = $selectField.find(".selectField-dropdown");
                    var $selectSearchField = $selectField.find("input");
                    $selectDropdown.toggleClass("show");
                    if ($selectDropdown.hasClass("show"))
                    {
                        $selectSearchField.focus();
                        var source = $selectField[0].source;
                        if (source.type == "local")
                            searchResults ($selectField);
                    }
                    event.stopPropagation();
                    return false;
                });
                
                var $selectSearchField = $(".selectField input");
                $selectSearchField.keyup (function (event)
                {
                    var $field = $(this);
                    var $selectField = $field.closest(".selectField");
                    var query = $field[0].value;
                    searchResults ($selectField, query);
                });
            });
        '); 
        $this->view->addStyle('
            .selectField-dropdown
            {
                width: 100%; 
                padding: 5px;
            }

            .selectField-searchList
            {
                margin: 0px;
                margin-top: 10px;
                padding: 0px;
                max-height: 100px;
                overflow-y: auto;
                overflow-x: hidden;
            }
            
            .selectField-searchItem
            {
                padding: 0px;
                border: none;
            }
        ');
        $this->view->addScript('$("#' . $this->id . '")[0].source = ' . json_encode($this->source));
    }
    
    protected function createContent ()
    {
        $container = new Tag("div", array("id"=>$this->id, "class"=>"dropdown selectField"));
        $button = new Tag("button", array("type"=>"button", "class"=>"btn btn-default btn-block selectField-button"));
        $button->add (new Tag("span", array("class"=>"pull-left"), "--"));
        $button->add (new Tag("span", array("class"=>"pull-right caret", "style"=>"margin-top:6px;")));
        $searchField = new Tag("input", array("type"=>"text", "class"=>"form-control", "placeholder"=>"Buscar ..."));
        $searchList = new Tag("div", array("class"=>"list-group selectField-searchList"));
        $dropdown = new Tag("ul", array("class"=>"dropdown-menu selectField-dropdown"));
        $dropdown->add (new Tag("li", $searchField));
        $dropdown->add (new Tag("li", $searchList));
        $container->add ($button);
        $container->add ($dropdown);
        $container->add (new Tag("input", array("type"=>"hidden", "class"=>"selectField-hiddenValue", "name"=>$this->name)));
        $container->add (new Tag("input", array("type"=>"hidden", "class"=>"selectField-hiddenDisplayValue", "name"=>$this->name . "_text")));
        return $container;
    }
}

?>