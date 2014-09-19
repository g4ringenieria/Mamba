<?php

namespace Mamba\view\administration;

use Mamba\component\Button;
use Mamba\component\EntityTable;
use Mamba\component\Panel;
use Mamba\view\SiteView;
use NeoPHP\web\html\Tag;

class TimeZoneCRUDView extends SiteView
{
    private $timezones = null;
    
    public function setTimezones ($timezones)
    {
        $this->timezones = $timezones;
    }
    
    protected function build()
    {
        parent::build();
        $this->addScript('
            $("#timezonesTable tr").on(
            {
                click: function (e) 
                {
                    $("#timezonesTable tr").removeClass("info");
                    $(this).addClass("info");
                    $("#updateButton").prop("disabled",false); 
                    $("#deleteButton").prop("disabled",false); 
                },
                dblclick: function (e)
                {
                    var id = $(this).attr("timezoneId");
                    window.open("showTimeZoneForm?timezoneid=" + id, "_self");
                }
            });
            $("#createButton").click(function (e) 
            {
                window.open("showTimeZoneForm", "_self");
            });
            $("#updateButton").click(function (e) 
            {
                var id = $("#timezonesTable tr.info").attr("timezoneId");
                window.open("showTimeZoneForm?timezoneid=" + id, "_self");
            });
            $("#deleteButton").click(function (e) 
            {
                var id = $("#timezonesTable tr.info").attr("timezoneId");
                if (window.confirm("Esta seguro de eliminar la zone horaria " + id + " ?"))
                    window.open("deleteTimeZone?timezoneid=" + id, "_self");
            });
        ');
    }
    
    protected function buildContent($content)
    {
        $container = new Tag("div");
        $container->add ($this->createButtonToolbar());
        $container->add ($this->createTimeZonesTable());        
        $content->add (new Tag("div", array("class"=>"container"), new Panel(array("title"=>"Adm de Zonas Horarias", "content"=>$container))));
    }
    
    protected function createButtonToolbar()
    {
        $toolbar = new Tag("ul", array("class"=>"nav nav-pills"));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-file-o"></i>&nbsp;Crear', array("id"=>"createButton", "class"=>"btn btn-primary"))));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-pencil"></i></i>&nbsp;Modifiar', array("id"=>"updateButton", "class"=>"btn btn-primary", "disabled"=>"true"))));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-trash"></i>&nbsp;Eliminar', array("id"=>"deleteButton", "class"=>"btn btn-primary", "disabled"=>"true"))));
        return $toolbar;
    }
    
    protected function createTimeZonesTable()
    {
        $table = new EntityTable(array("id"=>"timezonesTable"));
        $table->addColumn ("#", "id");
        $table->addColumn ("Nombre", "description");
        $table->addColumn ("Corrimiento horario", "timezone");
        $table->setEntities($this->timezones);
        $table->addEntityProperty("timezoneId", "id");
        return $table;
    }
}

?>