<?php

namespace Mamba\view\administration;

use Mamba\component\Button;
use Mamba\component\EntityTable;
use Mamba\component\Panel;
use Mamba\view\SiteView;
use NeoPHP\web\html\Tag;

class LineCRUDView extends SiteView
{
    private $lines = null;
    
    public function setLines ($lines)
    {
        $this->lines = $lines;
    }
    
    protected function build()
    {
        parent::build();
        $this->addScript('
            $("#linesTable tr").on(
            {
                click: function (e) 
                {
                    $("#linesTable tr").removeClass("info");
                    $(this).addClass("info");
                    $("#updateButton").prop("disabled",false); 
                    $("#deleteButton").prop("disabled",false); 
                },
                dblclick: function (e)
                {
                    var id = $(this).attr("lineId");
                    window.open("showLineForm?lineid=" + id, "_self");
                }
            });
            $("#createButton").click(function (e) 
            {
                window.open("showLineForm", "_self");
            });
            $("#updateButton").click(function (e) 
            {
                var id = $("#linesTable tr.info").attr("lineId");
                window.open("showLineForm?lineid=" + id, "_self");
            });
            $("#deleteButton").click(function (e) 
            {
                var id = $("#linesTable tr.info").attr("lineId");
                if (window.confirm("Esta seguro de eliminar la zone horaria " + id + " ?"))
                    window.open("deleteLine?lineid=" + id, "_self");
            });
        ');
    }
    
    protected function buildContent($content)
    {
        $container = new Tag("div");
        $container->add ($this->createButtonToolbar());
        $container->add ($this->createLinesTable());        
        $content->add (new Tag("div", array("class"=>"container"), new Panel(array("title"=>"Adm de Zonas Horarias", "content"=>$container))));
    }
    
    protected function createButtonToolbar()
    {
        $toolbar = new Tag("ul", array("class"=>"nav nav-pills"));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-file-o"></i>&nbsp;Crear', array("id"=>"createButton", "class"=>"btn btn-primary"))));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-pencil"></i></i>&nbsp;Modificar', array("id"=>"updateButton", "class"=>"btn btn-primary", "disabled"=>"true"))));
        $toolbar->add (new Tag("li", new Button('<i class="fa fa-trash"></i>&nbsp;Eliminar', array("id"=>"deleteButton", "class"=>"btn btn-primary", "disabled"=>"true"))));
        return $toolbar;
    }
    
    protected function createLinesTable()
    {
        $table = new EntityTable(array("id"=>"linesTable"));
        $table->addColumn ("#", "id");
        $table->addColumn ("Prestadora", "serviceProvider_description");
        $table->addColumn ("Número", "number");
        $table->addColumn ("Descripción", "description");
        $table->addColumn ("Equipo", "device_id");
        $table->setEntities($this->lines);
        $table->addEntityProperty("lineId", "id");
        return $table;
    }
}

?>