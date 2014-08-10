<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

class SelectField extends HTMLComponent
{
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
        $this->source->type = "local";
        $this->source->valueField = "id";
        $this->source->displayField = "description";
        $this->source->data = array();
        
        
//        $option1 = new \stdClass();
//        $option1->id = 1;
//        $option1->description = "pepe paredes";
//        $option2 = new \stdClass();
//        $option2->id = 2;
//        $option2->description = "tito puente";
//        $this->source->data[] = $option1;
//        $this->source->data[] = $option2;
        
        $this->source->type = "remote";
        $this->source->displayField = "domain";
        $this->source->url = $this->view->getBaseUrl() . "holders/?returnFormat=json&" . session_name() . "=" . session_id();
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScript('
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
                        if (dataItem.description.indexOf(query) >= 0)
                            $selectSearchList.append(createSearchResultItem(dataItem[source.valueField], dataItem[source.displayField]));
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
                                if (data && data.success == true && data.results)
                                    data = data.results;
                                $selectSearchList.empty();
                                for (var i in data)
                                {
                                    var dataItem = data[i];
                                    $selectSearchList.append(createSearchResultItem(dataItem[source.valueField], dataItem[source.displayField]));
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
        $button = new Tag("button", array("href"=>"#", "class"=>"btn btn-default btn-block selectField-button"));
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