<?php

namespace Mamba\component;

use NeoPHP\util\IntrospectionUtils;
use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\Tag;
use stdClass;

class EntityTable extends HTMLComponent
{
    private $id;
    private $columns;
    private $entities;
    private $entityProperties;
    private $attributes;
    private $hover;
    private $striped;
    private $bordered;
    private $condensed;
    private $responsive;
    
    public function __construct($attributes = array())
    {
        static $idCounter = 0;
        $this->id = isset($attributes["id"])? $attributes["id"] : "table_" . ($idCounter++);
        $this->columns = array();
        $this->entities = array();
        $this->entityProperties = array();
        $this->attributes = $attributes;
        $this->hover = true;
        $this->striped = true;
        $this->bordered = false;
        $this->responsive = true;
    }
    
    public function addColumn ($name, $property, callable $renderer=null)
    {
        $column = new stdClass();
        $column->name = $name;
        $column->property = $property;
        $column->renderer = $renderer;
        $this->columns[] = $column;
    }
    
    public function setEntities ($entities)
    {
        $this->entities = $entities;
    }
    
    public function addEntity ($entity)
    {
        $this->entities[] = $entity;
    }
    
    public function getHover()
    {
        return $this->hover;
    }

    public function setHover($hover)
    {
        $this->hover = $hover;
    }

    public function getStriped()
    {
        return $this->striped;
    }

    public function setStriped($striped)
    {
        $this->striped = $striped;
    }

    public function getBordered()
    {
        return $this->bordered;
    }

    public function setBordered($bordered)
    {
        $this->bordered = $bordered;
    }

    public function getCondensed()
    {
        return $this->condensed;
    }

    public function setCondensed($condensed)
    {
        $this->condensed = $condensed;
    }

    public function getResponsive()
    {
        return $this->responsive;
    }

    public function setResponsive($responsive)
    {
        $this->responsive = $responsive;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function addEntityProperty($propertyName, $entityPropertyName)
    {
        $this->entityProperties[$propertyName] = $entityPropertyName;
    }
    
    protected function createContent ()
    {
        $columns = $this->columns;
        $entities = $this->entities;
        $tableHead = new Tag("thead");
        $tableHeadContainer = new Tag("tr");
        foreach ($columns as $column)
        {
            $tableHeadContainer->add (new Tag("th", array(), $column->name));
        }
        $tableHead->add($tableHeadContainer);
        
        $tableBody = new Tag("tbody");
        foreach ($entities as $entity)
        {
            $tableRow = new Tag("tr");
            foreach ($this->entityProperties as $propertyName=>$entityPropertyName)
            {
                $tableRow->setAttribute($propertyName, IntrospectionUtils::getRecursivePropertyValue($entity, $entityPropertyName));
            }
            foreach ($columns as $column)
            {
                if (!is_string($column->property) && is_callable($column->property))
                {
                    $columnValue = call_user_func_array($column->property, array($entity));
                }
                else
                {
                    $columnValue = IntrospectionUtils::getRecursivePropertyValue($entity, $column->property);
                    if (isset($column->renderer))
                        $columnValue = call_user_func_array($column->renderer, array($columnValue));
                }
                $tableRow->add (new Tag("td", strval($columnValue)));
            }
            $tableBody->add($tableRow);
        }
        
        $tableClassName = "table";
        if ($this->striped)
            $tableClassName .= " table-striped";
        if ($this->hover)
            $tableClassName .= " table-hover";
        if ($this->bordered)
            $tableClassName .= " table-bordered";
        if ($this->condensed)
            $tableClassName .= " table-condensed";
        $tableClassName .= " unselectable";
        $table = new Tag("table", array_merge(array("id"=>$this->id, "class"=>$tableClassName, "style"=>"cursor:pointer;"), $this->attributes));
        $table->add($tableHead);
        $table->add($tableBody);
        return ($this->responsive)? new Tag("div", array("class"=>"table-wrapper"), $table) : $table;
    }
}

?>
