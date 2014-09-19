<?php

namespace Mamba\view\report;

use Mamba\util\DateUtils;
use Mamba\util\GeoUtils;
use Mamba\component\DatetimePicker;
use Mamba\component\EntityTable;
use Mamba\component\Form;
use Mamba\component\MapOL_Default;
use Mamba\component\MultiButton;
use Mamba\component\Panel;
use Mamba\component\SelectField;
use Mamba\view\SidebarSiteView;
use NeoPHP\web\http\Parameters;
use stdClass;

class PositionReportsView extends SidebarSiteView
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
        $form->addField($holderSelector, array("label"=>"Vehículo"));
        $form->addField($dateFromPicker, array("label"=>"Fecha desde"));
        $form->addField($dateToPicker, array("label"=>"Fecha hasta"));
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
        $vectorSource = new stdClass();
        $vectorSource->type = "vector";
        $vectorSource->features = array();
        foreach ($this->reports as $report)
        {
            $feature = new stdClass();
            $feature->geometry = (object)array("type"=>"point", "coordinates"=>array($report->getLongitude(), $report->getLatitude())); //$geometry;
            $feature->iconId = $report->getReportType()->getId();
            $feature->description = '';
            $feature->description .= '<b>Vehículo: </b>' . $report->getHolder();
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
        $vectorLayer->source = $vectorSource;
        $vectorLayer->style = "createFeatureStyleFunction()";
        
        $map = new MapOL_Default($this, array("style"=>"height:500px;"));
        $map->adjustViewOnLayer("features");
        $map->addLayer($vectorLayer);
        return new Panel(array("title"=>"Histórico de Reportes (Mapa)", "content"=>$map));
    }
}

?>