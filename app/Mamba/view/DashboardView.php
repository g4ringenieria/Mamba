<?php

namespace Mamba\view;

use Mamba\util\DateUtils;
use Mamba\util\GeoUtils;
use Mamba\view\component\EntityTable;
use Mamba\view\component\MapOL_Default;
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
        $vectorSource = new stdClass();
        $vectorSource->type = "vector";
        $vectorSource->features = array();
        foreach ($this->holders as $holder)
        {
            $report = $holder->getLastReport ();
            $feature = new stdClass();
            $feature->geometry = (object)array("type"=>"point", "coordinates"=>array($report->getLongitude(), $report->getLatitude())); //$geometry;
            $feature->iconId = "pickup";
            $feature->label = $holder;
            $feature->description = '';
            $feature->description .= '<b>Holder: </b>' . $holder;
            $feature->description .= '<br><b>Equipo: </b>' . $report->getDevice()->getId();
            $feature->description .= '<br><b>Reporte: </b>' . $report->getReportType()->getDescription();
            $feature->description .= '<br><b>Fecha posición: </b>' . DateUtils::formatDate($report->getDate(), $this->getSession()->userDateFormat, $this->getSession()->userTimeZone);
            $feature->description .= '<br><b>Velocidad: </b>' . $report->getSpeed();
            $feature->description .= '<br><b>Curso: </b>' . GeoUtils::getCourseString($report->getCourse());
            $vectorSource->features[] = $feature;
        }
        $vectorLayer = new stdClass();
        $vectorLayer->type = "vector";
        $vectorLayer->name = "features";
        $vectorLayer->style = "createFeatureStyleFunction()";
        $vectorLayer->source = $vectorSource;
        
        $map = new MapOL_Default($this, array("style"=>"height:500px;"));
        $map->addLayer($vectorLayer);
        $map->adjustViewOnLayer ("features");
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