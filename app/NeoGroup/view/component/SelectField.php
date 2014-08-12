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
    
    public function setName ($name)
    {
        $this->name = $name;
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
            
            function selectSetValue (id, value, displayValue, focus)
            {
                var $selectField = $("#" + id);
                var $hiddenField = $selectField.find("input[type=hidden]");
                var $searchField = $selectField.find("input[type=text]");
                $hiddenField.val(value);
                $searchField.val(displayValue);
                $searchField.attr("readonly", true);
                if (focus != null)
                    $searchField.focus();
            }
            
            function selectClearValue (id)
            {
                var $selectField = $("#" + id);
                var $hiddenField = $selectField.find("input[type=hidden]");
                var $searchField = $selectField.find("input[type=text]");
                $searchField.attr("readonly", false);
                $hiddenField.val("");
                $searchField.val("");
            }

            function selectClearResults (id)
            {
                var $selectField = $("#" + id);
                var $selectDropdown = $selectField.find(".dropdown-menu");
                $selectDropdown.removeClass("show");
            }

            function selectSetResults (id, data, query)
            {
                var $selectField = $("#" + id);
                var $selectDropdown = $selectField.find(".dropdown-menu");
                var $selectSearchList = $selectField.find(".list-group");
                var source = $selectField[0].source;
                var showDropdown = false;

                $selectDropdown.removeClass("show");
                $selectSearchList.empty();
                for (var i in data)
                {
                    var dataItem = data[i];
                    var description = "";
                    if (source.displayTemplate)
                    {
                        description = source.displayTemplate;
                        for (var i in dataItem)
                            description = description.replace(new RegExp("{" + i + "}", "g"), dataItem[i]);
                    }
                    else
                    {
                        description = dataItem[source.displayField];
                    }
                    if (query == null || description.indexOf(query) >= 0)
                    {
                        var $searchItem = $("<a href=\"#\" class=\"list-group-item" + ((showDropdown)?"":" active") + "\" value=\"" + dataItem[source.valueField] + "\">" + description + "</a>");
                        $searchItem.click(function () 
                        {
                            var $searchItem = $(this);
                            var value = $searchItem.attr("value");
                            var displayValue = $searchItem[0].innerHTML;
                            selectSetValue (id, value, displayValue, true);
                            $selectDropdown.removeClass("show");
                        });
                        $selectSearchList.append($searchItem);
                        showDropdown = true;
                    }
                }
                
                if (showDropdown)
                {
                    var $hiddenField = $selectField.find("input[type=hidden]");
                    if (!$hiddenField.val())
                    {
                        $selectDropdown.addClass("show");
                        $selectSearchList.scrollTop(0);
                    }
                }
            }

            function selectSearchResults (id)
            {
                var $selectField = $("#" + id);
                var $searchField = $selectField.find("input[type=text]");
                var searchQuery = $searchField[0].value;
                var source = $selectField[0].source;
                $searchField.focus();

                if (source.type == "local")
                {
                    selectSetResults(id, source.data, searchQuery);
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
                            data: { query: searchQuery },
                            success: function (data, status, xhr) { if (data && data.success == true && data.results) selectSetResults(id, data.results, null); },
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
                    var $selectField = $(this).closest(".selectField");
                    var $hiddenField = $selectField.find("input[type=hidden]");
                    var id = $selectField.attr("id");
                    if ($hiddenField.val())
                        selectClearValue (id);
                    selectSearchResults (id);
                });
                $input = $(".selectField input[type=text]");
                $input.keydown(function(event)
                {
                    var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
                    if (keyCode == 13 || keyCode == 8 || keyCode == 46)
                    {
                        event.cancelBubble = true;
                        event.returnValue = false;
                        if (event.stopPropagation) 
                        {   
                            event.stopPropagation();
                            event.preventDefault();
                        }
                        return false;
                    }
                });
                $input.keypress(function(event)
                {
                    var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
                    if (keyCode == 13 || keyCode == 8 || keyCode == 46)
                    {
                        event.cancelBubble = true;
                        event.returnValue = false;
                        if (event.stopPropagation) 
                        {   
                            event.stopPropagation();
                            event.preventDefault();
                        }
                        return false;
                    }
                });
                $input.keyup(function(event)
                {
                    var $selectField = $(this).closest(".selectField");
                    var id = $selectField.attr("id");
                    var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
                    switch (keyCode)
                    {
                        case 13:
                            var $searchActiveItem = $selectField.find(".list-group .active");
                            var value = $searchActiveItem.attr("value");
                            var displayValue = $searchActiveItem[0].innerHTML;
                            selectSetValue (id, value, displayValue, true);
                            selectClearResults (id);
                            break;
                        case 27:
                            selectClearResults(id);
                            break;
                        case 38:
                            var $selectDropdown = $selectField.find(".dropdown-menu");
                            if ($selectDropdown.hasClass("show"))
                            {
                                var $searchActiveItem = $selectField.find(".list-group .active");
                                var $previousItem = $searchActiveItem.prev();
                                if ($previousItem.length > 0)
                                {
                                    $searchActiveItem.removeClass("active");
                                    $previousItem.addClass("active");
                                    
                                    var $selectSearchList = $selectField.find(".list-group");
                                    var listOffsets = $selectSearchList.offset();
                                    var listHeight = $selectSearchList.height();
                                    var itemOffsets = $previousItem.offset();
                                    var itemHeight = $previousItem.height();
                                    if (listOffsets.top > itemOffsets.top)
                                        $selectSearchList.scrollTop($selectSearchList.scrollTop() - ((listOffsets.top) - (itemOffsets.top)));
                                }
                            }
                            else
                            {
                                selectSearchResults (id);
                            }
                            break;
                        case 40:
                            var $selectDropdown = $selectField.find(".dropdown-menu");
                            if ($selectDropdown.hasClass("show"))
                            {
                                var $searchActiveItem = $selectField.find(".list-group .active");
                                var $nextItem = $searchActiveItem.next();
                                if ($nextItem.length > 0)
                                {
                                    $searchActiveItem.removeClass("active");
                                    $nextItem.addClass("active");
                                    
                                    var $selectSearchList = $selectField.find(".list-group");
                                    var listOffsets = $selectSearchList.offset();
                                    var listHeight = $selectSearchList.height();
                                    var itemOffsets = $nextItem.offset();
                                    var itemHeight = $nextItem.height();
                                    if ((listOffsets.top + listHeight) <= itemOffsets.top)
                                        $selectSearchList.scrollTop($selectSearchList.scrollTop() + ((itemOffsets.top + itemHeight) - (listOffsets.top + listHeight)));
                                }
                            }
                            else
                            {
                                selectSearchResults (id);
                            }
                            break;
                        case 8:
                        case 46:
                            var $hiddenField = $selectField.find("input[type=hidden]");
                            if ($hiddenField.val())
                                selectClearValue (id);
                            else
                                selectSearchResults (id);
                            break;
                        default:
                            selectSearchResults (id);
                            break;
                    }
                });
                $input.focusin(function() 
                {
                    var $input = $(this);
                    clearTimeout($input[0].focusTimeout);
                });
                $input.focusout(function() 
                {
                    var $input = $(this);
                    $input[0].focusTimeout = setTimeout(function() 
                    { 
                        var $selectField = $input.closest(".selectField");
                        var $hiddenField = $selectField.find("input[type=hidden]");
                        var $searchField = $selectField.find("input[type=text]");
                        var id = $selectField.attr("id");
                        selectClearResults(id); 
                        if (!$hiddenField.val())
                            $searchField.val("");
                    }, 200);
                });
            });
        '); 
        $this->view->addStyle('
            .selectField .dropdown-menu
            {
                width: 100%;
                padding: 5px;
                margin: 0px;
            }
            
            .selectField .list-group
            {
                max-height: 100px;
                margin: 0px;
                padding: 0px;
                overflow-x: hidden;
                overflow-y: auto;
            }
            
            .selectField .list-group .list-group-item
            {
                padding: 0px;
                margin: 0px;
                padding-left: 3px;
                margin-right: 3px;
                border: none;
            }
        ');
        $this->view->addScript('$("#' . $this->id . '")[0].source = ' . json_encode($this->source));
    }
    
    protected function createContent ()
    {
        $inputGroup = new Tag("div", array("class"=>"input-group"));
        $inputGroup->add (new Tag("input", array("type"=>"text", "name"=>$this->name . "_text", "class"=>"form-control", "autocomplete"=>"off")));
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