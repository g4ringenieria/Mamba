<?php

namespace NeoGroup\view\component;

use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class Form extends Tag
{
    const TYPE_BASIC = 0;
    const TYPE_INLINE = 1;
    const TYPE_HORIZONTAL = 2;
    
    private $type;
    private $columns;
    
    public function __construct(HTMLView $view=null, array $attributes = array())
    {
        parent::__construct("form", $attributes);
        $this->type = self::TYPE_BASIC;
        $this->columns = 1;
        
        if ($view != null)
        {
            $view->addScript ('

                jQuery.fn.extend(
                {
                    setFieldState: function (state, stateText, stateTextAppend)
                    {
                        return this.each(function() 
                        {
                            var $field = $(this);
                            var $fieldGroup = $field.closest(".form-group");
                            $fieldGroup.addClass("has-" + state);
                            if (stateText)
                            {
                                var $helpBlock = $fieldGroup.find(".help-block");
                                $helpBlock.html(stateTextAppend == true? ($helpBlock.html() + ($helpBlock.html() != ""? "<br>" : "") + stateText) : stateText);
                                $helpBlock.removeClass("hidden");
                            }
                        });
                    },

                    addFieldError: function (errorText)
                    {
                        this.setFieldState("error", errorText, true);
                    },

                    clearFieldState: function (state)
                    {
                        return this.each(function() 
                        {
                            $field = $(this);
                            var $fieldGroup = $field.closest(".form-group");
                            $fieldGroup.removeClass("has-error");
                            $fieldGroup.removeClass("has-warning");
                            $fieldGroup.removeClass("has-success");
                            var $helpBlock = $fieldGroup.find(".help-block");
                            $helpBlock.addClass("hidden");
                            $helpBlock.html("");
                        });
                    }
                });
            ');
        }
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        switch ($this->type)
        {
            case self::TYPE_INLINE:
                $this->setAttribute("class", "form-inline");
                break;
            case self::TYPE_HORIZONTAL:
                $this->setAttribute("class", "form-horizontal"); 
                break;
        }
    }
    
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }
    
    public function addField ($field, array $attributes = array())
    {
        if (!isset($this->fieldsCounter))
            $this->fieldsCounter = 0;
        
        $classTokens = array();
        $classTokens[] = "form-group";
        if (isset($attributes["error"]))
            $classTokens[] = "has-error";
        if (isset($attributes["success"]))
            $classTokens[] = "has-success";
        if (isset($attributes["warning"]))
            $classTokens[] = "has-warning";
        $formgroup = new Tag("div", array("class"=>implode(" ", $classTokens)));
        
        switch ($this->type)
        {
            case self::TYPE_BASIC:
                if (!empty($attributes["label"]))
                    $formgroup->add (new Tag("label", array("class"=>"control-label"), $attributes["label"]));
                $formgroup->add ($field);
                $formgroup->add (new Tag("p", array("class"=>"help-block hidden"), ""));
                
                if ($this->columns > 1)
                {
                    if ($this->fieldsCounter % $this->columns == 0)
                    {
                        $this->fieldRow = new Tag("div", array("class"=>"row"));
                        $this->add ($this->fieldRow);
                    }
                    $this->fieldRow->add(new Tag("div", array("class"=>"col-md-" . (12/$this->columns)), $formgroup));
                }
                else
                {
                    $this->add($formgroup);
                }
                break;
            case self::TYPE_INLINE:
                if (!empty($attributes["label"]))
                    $formgroup->add (new Tag("label", array("class"=>"sr-only"), $attributes["label"]));
                $formgroup->add ($field);
                $this->add($formgroup);
                break;
            case self::TYPE_HORIZONTAL:
                if (!empty($attributes["label"]))
                {
                    $formgroup->add (new Tag("label", array("class"=>"col-sm-2 control-label"), $attributes["label"]));
                    $formgroup->add (new Tag("div", array("class"=>"col-sm-10"), $field));
                }
                else
                {
                    $formgroup->add (new Tag("div", array("class"=>"col-sm-12"), $field));
                }
                $formgroup->add (new Tag("p", array("class"=>"help-block hidden"), ""));
                if ($this->columns > 1)
                {
                    if ($this->fieldsCounter % $this->columns == 0)
                    {
                        $this->fieldRow = new Tag("div", array("class"=>"row"));
                        $this->add ($this->fieldRow);
                    }
                    $this->fieldRow->add(new Tag("div", array("class"=>"col-md-" . (12/$this->columns)), $formgroup));
                }
                else
                {
                    $this->add($formgroup);
                }
                break;
        }
        $this->fieldsCounter++;
    }
}

?>