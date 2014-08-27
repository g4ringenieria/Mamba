<?php

namespace Mamba\view\report;

use Mamba\util\DateUtils;
use Mamba\util\GeoUtils;
use Mamba\view\component\DatetimePicker;
use Mamba\view\component\EntityTable;
use Mamba\view\component\Form;
use Mamba\view\component\Map;
use Mamba\view\component\MultiButton;
use Mamba\view\component\Panel;
use Mamba\view\component\SelectField;
use Mamba\view\SidebarSiteView;
use NeoPHP\web\http\Parameters;
use stdClass;

class ReportsView extends SidebarSiteView
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
    
    protected function buildSidebar($sidebar)
    {
        $sidebar->add($this->createFiltersForm());
    }
    
    protected function buildContent($content)
    {
        switch ($this->outputType)
        {
            case self::OUTPUTTYPE_GRID:
                $content->add ($this->createReportsGrid());
                break;
            case self::OUTPUTTYPE_MAP:
                $content->add ($this->createReportsMap ());
                break;
        }
    }
    
    protected function createFiltersForm ()
    {
        $parameters = Parameters::getInstance();
        $holderSelector = new SelectField($this, array("placeholder"=>"Vehículo", "name"=>"holderId", "value"=>$parameters->holderId, "displayvalue"=>$parameters->holderId_text));
        $holderSelector->setSourceType(SelectField::SOURCETYPE_REMOTE);
        $holderSelector->setRemoteUrl($this->getBaseUrl() . "holders/?returnFormat=json&" . session_name() . "=" . session_id());
        $holderSelector->setDisplayTemplate("{id}: {name} {domain}");
        $dateFromPicker = new DatetimePicker($this);
        $dateFromPicker->setAttributes(array("placeholder"=>"Fecha desde ...", "name"=>"dateFrom"));
        $dateFromPicker->setValue(isset($parameters->dateFrom)? $parameters->dateFrom : (date("Y/m/d") . " 00:00:00"));
        $dateToPicker = new DatetimePicker($this);
        $dateToPicker->setAttributes(array("placeholder"=>"Fecha hasta ...", "name"=>"dateTo"));
        $dateToPicker->setValue(isset($parameters->dateTo)? $parameters->dateTo : (date("Y/m/d") . " 23:59:59"));
        $button = new MultiButton("Buscar", array("class"=>"primary"));
        $button->addAction("Tabla", array("action"=>"showReportsInTable"));
        $button->addAction("Mapa", array("action"=>"showReportsInMap"));
        $form = new Form(array("method"=>"POST"));
        $form->addField($holderSelector);
        $form->addField($dateFromPicker);
        $form->addField($dateToPicker);
        $form->add($button);
        return $form;
    }
    
    protected function createReportsGrid ()
    {
        $grid = new EntityTable();
        $grid->addColumn ("Vehículo", "holder");
        $grid->addColumn ("Equipo", "device_id");
        $grid->addColumn ("Reporte", "reporttype_description");
        $grid->addColumn ("Fecha", "date", function ($date) { return DateUtils::formatDate($date, $this->getSession()->userDateFormat, $this->getSession()->userTimeZone); } );
        $grid->addColumn ("Velocidad", "speed");
        $grid->addColumn ("Curso", "course", function ($course) { return GeoUtils::getCourseString($course); });
        $grid->addColumn ("Latitud", "latitude");
        $grid->addColumn ("Longitud", "longitude");
        $grid->addColumn ("Ubicación", "location");
        $grid->setEntities($this->reports);  
        return new Panel(array("title"=>"Histórico de Reportes (Grilla)", "content"=>$grid, "autoWidth"=>true));
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
                if (!empty($position->latitude) && !empty($position->longitude))
                {
                    $pathOverlay->points[] = $position;
                    if (!$markerAdded)
                    {
                        $overlay = new stdClass();
                        $overlay->type = "marker";
                        $overlay->latitude = $report->getLatitude();
                        $overlay->longitude = $report->getLongitude();
                        $overlay->description = '
                            <b>Vehículo: </b>' . $report->getHolder() . '
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
        $map->setAttributes(array("style"=>"height:500px"));
        $map->addDefaultBaseLayers();
        $map->addOverlay($overlayLayer);
        return new Panel(array("title"=>"Histórico de Reportes (Mapa)", "content"=>$map));
    }
}

?>