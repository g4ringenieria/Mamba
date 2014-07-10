<?php

namespace NeoGroup\view;

use NeoPHP\web\html\Tag;
use NeoPHP\web\http\Parameters;
use stdClass;
use NeoGroup\view\component\Button;
use NeoGroup\view\component\DatetimePicker;
use NeoGroup\view\component\EntityTable;
use NeoGroup\view\component\Map;
use NeoGroup\view\component\Selector;
use NeoGroup\util\DateUtils;
use NeoGroup\util\GeoUtils;

class ReportsView extends SiteView
{
    const OUTPUTTYPE_NONE = 0;
    const OUTPUTTYPE_GRID = 1;
    const OUTPUTTYPE_MAP = 2;
    
    private $reports;
    private $outputType;
    
    public function setReports($reports)
    {
        $this->reports = $reports;
    }
    
    public function setOutputType ($outputType)
    {
        $this->outputType = $outputType;
    }
    
    protected function getPageTitle ()
    {
        return "Herramienta de Reportes";
    }
    
    protected function buildPage($page) 
    {
        $page->add ($this->createFiltersForm());
        switch ($this->outputType)
        {
            case self::OUTPUTTYPE_GRID:
                $page->add ($this->createReportsGrid());
                break;
            case self::OUTPUTTYPE_MAP:
                $page->add ($this->createReportsMap ());
                break;
        }   
    }
    
    protected function createFiltersForm ()
    {
        $parameters = Parameters::getInstance();
        $holderSelector = new Selector($this);
        $holderSelector->setAttributes(array("placeholder"=>"Seleccione un holder ...", "name"=>"holderId"));
        $holderSelector->setRemoteUrl($this->getUrl("holders/"));
        $holderSelector->setRequestParams(array("returnFormat"=>"json"));
        $holderSelector->setValueField("id");
        $holderSelector->setSearchFields(array("id","name","domain"));
        $holderSelector->setTemplate("<div><span>{id}: {name} {domain}</span></div>");
        if (isset($parameters->holderId))
            $holderSelector->setValue($parameters->holderId);
        $dateFromPicker = new DatetimePicker($this);
        $dateFromPicker->setAttributes(array("placeholder"=>"Fecha desde ...", "name"=>"dateFrom"));
        $dateFromPicker->setValue(isset($parameters->dateFrom)? $parameters->dateFrom : (date("Y/m/d") . " 00:00:00"));
        $dateToPicker = new DatetimePicker($this);
        $dateToPicker->setAttributes(array("placeholder"=>"Fecha hasta ...", "name"=>"dateTo"));
        $dateToPicker->setValue(isset($parameters->dateTo)? $parameters->dateTo : (date("Y/m/d") . " 23:59:59"));
        $button = new Button();
        $button->setType("danger");
        $button->setText("Buscar");
        $button->addAction("Tabla", array(), "showReportsInTable");
        $button->addAction("Mapa", array(), "showReportsInMap");
        $container = new Tag("div", array("class"=>"row"));
        $container->add (new Tag("div", array("class"=>"col-sm-3"), $holderSelector));
        $container->add (new Tag("div", array("class"=>"col-sm-3"), $dateFromPicker));
        $container->add (new Tag("div", array("class"=>"col-sm-3"), $dateToPicker));
        $container->add (new Tag("div", array("class"=>"col-sm-3"), $button));
        return new Tag("form", array("style"=>"margin-bottom: 10px", "method"=>"POST"), $container);
    }
    
    protected function createReportsGrid ()
    {
        $grid = new EntityTable();
        $grid->addColumn ("Holder", "holder");
        $grid->addColumn ("Equipo", "device_id");
        $grid->addColumn ("Reporte", "reporttype_description");
        $grid->addColumn ("Fecha", "date", function ($date) { return DateUtils::formatDate($date, $this->getSession()->userDateFormat, $this->getSession()->userTimeZone); } );
        $grid->addColumn ("Velocidad", "speed");
        $grid->addColumn ("Curso", "course", function ($course) { return GeoUtils::getCourseString($course); });
        $grid->addColumn ("Latitud", "latitude");
        $grid->addColumn ("Longitud", "longitude");
        $grid->addColumn ("Odómetro", "odometer");
        $grid->setEntities($this->reports);  
        return $grid;
    }
    
    protected function createReportsMap () 
    {
        $reportsData = array();
        foreach ($this->reports as $report )
        {
            if (!isset($reportsData[$report->getHolder()->getId()]))
                $reportsData[$report->getHolder()->getId()] = array();
            $reportsData[$report->getHolder()->getId()][strtotime($report->getDate())] = $report;
        }
        
        $paths = array();
        $markers = array();
        foreach ($reportsData as $holderReportData)
        {
            krsort($holderReportData);
            $markerAdded = false;
            $pathOverlay = new stdClass();
            $pathOverlay->type = "polyline";    
            $pathOverlay->points = array();
            foreach ($holderReportData as $report)
            {
                $position = new stdClass();
                $position->latitude = $report->getLatitude();
                $position->longitude = $report->getLongitude();
                $pathOverlay->points[] = $position;
                
                if (!$markerAdded)
                {
                    $overlay = new stdClass();
                    $overlay->type = "marker";
                    $overlay->latitude = $report->getLatitude();
                    $overlay->longitude = $report->getLongitude();
                    $overlay->description = '
                        <b>Holder: </b>' . $report->getHolder() . '
                        <br><b>Equipo: </b>' . $report->getDevice()->getId() . '
                        <br><b>Evento: </b>' . $report->getReportType() . '
                        <br><b>Fecha posición: </b>' . DateUtils::formatDate($report->getDate(), $this->getSession()->userDateFormat, $this->getSession()->userTimeZone) . '
                        <br><b>Velocidad: </b>' . $report->getSpeed() . '
                        <br><b>Curso: </b>' . GeoUtils::getCourseString($report->getCourse()) . '
                        <br><b>Odómetro: </b>' . $report->getOdometer() . '
                    ';
                    $overlay->label = strval($report->getHolder());
                    $overlay->labelConfig = new stdClass(); 
                    $overlay->labelConfig->noHide = true;
                    $markers[] = $overlay;
                    $markerAdded = true;
                }
            }
            $paths[] = $pathOverlay;
        }
        $overlayLayer = new stdClass();
        $overlayLayer->name = "Unidades";
        $overlayLayer->type = "featureGroup";
        $overlayLayer->overlays = array();  
        $overlayLayer->fitToBounds = true;
        foreach ($paths as $path)
            $overlayLayer->overlays[] = $path;
        foreach ($markers as $marker)
            $overlayLayer->overlays[] = $marker;
        
        $map = new Map($this);
        $map->setAttributes(array("style"=>"height:500px;"));
        $map->addDefaultBaseLayers();
        $map->addOverlay($overlayLayer);
        return $map;
    }
}

?>