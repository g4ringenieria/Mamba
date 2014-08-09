<?php

namespace NeoGroup\view;

use NeoGroup\util\DateUtils;
use NeoGroup\util\GeoUtils;
use NeoGroup\view\component\EntityTable;
use NeoGroup\view\component\Map;
use NeoGroup\view\component\Panel;
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
                <br><b>Odómetro: </b>' . $report->getOdometer() . '
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
        $panel = new Panel ("Vehículos en Mapa (" . date("Y-m-d H:i:s", time()) . ")", $map);
        $panel->setAddBodyWrapper(false);
        $panel->setCollapsible(true);
        return $panel;
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
        $grid->addColumn ("Odómetro", "lastReport_odometer");
        $grid->setEntities($this->holders);  
        $panel = new Panel ("Vehículos en Tabla (" . date("Y-m-d H:i:s", time()) . ")", $grid);
        $panel->setAddBodyWrapper(false);
        $panel->setCollapsible(true);
        return $panel;
    }
}

?>