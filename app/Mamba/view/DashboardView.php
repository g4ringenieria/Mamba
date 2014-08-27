<?php

namespace Mamba\view;

use Mamba\util\DateUtils;
use Mamba\util\GeoUtils;
use Mamba\view\component\EntityTable;
use Mamba\view\component\Map;
use Mamba\view\component\Panel;
use stdClass;

class DashboardView extends SiteView
{
    private $holders = array();
    
    public function setHolders ($holders)
    {
        $this->holders = $holders;
    }
        
    protected function getPageTitle ()
    {
        return "Dashboard";
    }
    
    protected function buildContent($page) 
    {
        $page->add ($this->createHoldersMapWidget());
        $page->add ($this->createHoldersGridWidget());
    }
        
    protected function createHoldersMapWidget() 
    {
        $overlayLayer = new stdClass();
        $overlayLayer->name = "Unidades";
        $overlayLayer->type = "featureGroup";
        $overlayLayer->overlays = array();  
        $overlayLayer->fitToBounds = true;
        foreach ($this->holders as $holder)
        {
            $report = $holder->getLastReport ();
            $overlay = new stdClass();
            $overlay->type = "marker";
            $overlay->latitude = $report->getLatitude();
            $overlay->longitude = $report->getLongitude();
            $overlay->description = '   
                <b>Holder: </b>' . $holder . '
                <br><b>Equipo: </b>' . $report->getDevice()->getId() . '
                <br><b>Reporte: </b>' . $report->getReportType()->getDescription() . '
                <br><b>Fecha posición: </b>' . DateUtils::formatDate($report->getDate(), $this->getSession()->userDateFormat, $this->getSession()->userTimeZone) . '
                <br><b>Velocidad: </b>' . $report->getSpeed() . '
                <br><b>Curso: </b>' . GeoUtils::getCourseString($report->getCourse()) . '
            ';
            $overlay->label = strval($holder);
            $overlay->labelConfig = new stdClass(); 
            $overlay->labelConfig->noHide = true;
            $overlayLayer->overlays[] = $overlay;
        }
        
        $map = new Map($this);
        $map->setAttributes(array("style"=>"height:500px;"));
        $map->addDefaultBaseLayers();
        $map->addOverlay($overlayLayer);
        return new Panel (array("title"=>"Vehículos en Mapa (" . date("Y-m-d H:i:s", time()) . ")", "content"=>$map, "collapsible"=>true));
    }
    
    protected function createHoldersGridWidget() 
    {
        $grid = new EntityTable(); 
        $grid->addColumn ("Holder", function ($holder) { return $holder; } );
        $grid->addColumn ("Equipo", "lastReport_device_id");
        $grid->addColumn ("Reporte", "lastReport_ReportType_description");
        $grid->addColumn ("Fecha", "lastReport_date", function ($date) { return DateUtils::formatDate($date, $this->getSession()->userDateFormat, $this->getSession()->userTimeZone); });
        $grid->addColumn ("Velocidad", "lastReport_speed");
        $grid->addColumn ("Curso", "lastReport_course", function ($course) { return GeoUtils::getCourseString($course); });
        $grid->addColumn ("Latitud", "lastReport_latitude");
        $grid->addColumn ("Longitud", "lastReport_longitude");
        $grid->addColumn ("Ubicación", "lastReport_location");
        $grid->setEntities($this->holders);  
        return new Panel (array("title"=>"Vehículos en Tabla (" . date("Y-m-d H:i:s", time()) . ")", "content"=>$grid, "collapsible"=>true));
    }
}

?>