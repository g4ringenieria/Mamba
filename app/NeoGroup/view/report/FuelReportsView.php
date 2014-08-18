<?php

namespace NeoGroup\view\report;

use NeoGroup\util\DateUtils;
use NeoGroup\view\component\DatetimePicker;
use NeoGroup\view\component\EntityTable;
use NeoGroup\view\component\Form;
use NeoGroup\view\component\MultiButton;
use NeoGroup\view\component\Panel;
use NeoGroup\view\component\SelectField;
use NeoGroup\view\component\Highchart;
use NeoGroup\view\component\HighchartSerie;
use NeoGroup\view\SidebarSiteView;
use NeoPHP\web\http\Parameters;

class FuelReportsView extends SidebarSiteView
{
    const OUTPUTTYPE_NONE = 0;
    const OUTPUTTYPE_GRID = 1;
    const OUTPUTTYPE_GRAPHIC = 2;

    private $reports;
    private $outputType;

    public function setReports($reports)
    {
        $this->reports = $reports;
    }

    public function setOutputType($outputType)
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
                $content->add($this->createReportsGrid());
                break;
            case self::OUTPUTTYPE_GRAPHIC:
                $content->add($this->createReportsGraphic());
                break;
        }
    }

    protected function createFiltersForm()
    {
        $parameters = Parameters::getInstance();
        $holderSelector = new SelectField($this, array("placeholder" => "Vehículo", "name" => "holderId", "value" => $parameters->holderId, "displayvalue" => $parameters->holderId_text));
        $holderSelector->setSourceType(SelectField::SOURCETYPE_REMOTE);
        $holderSelector->setRemoteUrl($this->getBaseUrl() . "holders/?returnFormat=json&" . session_name() . "=" . session_id());
        $holderSelector->setDisplayTemplate("{id}: {name} {domain}");
        $dateFromPicker = new DatetimePicker($this);
        $dateFromPicker->setAttributes(array("placeholder" => "Fecha desde ...", "name" => "dateFrom"));
        $dateFromPicker->setValue(isset($parameters->dateFrom) ? $parameters->dateFrom : (date("Y/m/d") . " 00:00:00"));
        $dateToPicker = new DatetimePicker($this);
        $dateToPicker->setAttributes(array("placeholder" => "Fecha hasta ...", "name" => "dateTo"));
        $dateToPicker->setValue(isset($parameters->dateTo) ? $parameters->dateTo : (date("Y/m/d") . " 23:59:59"));
        $button = new MultiButton("Buscar", array("class" => "primary"));
        $button->addAction("Tabla", array("action" => "showReportsInTable"));
        $button->addAction("Gráfico", array("action" => "showReportsInGraphic"));
        $form = new Form(array("method" => "POST"));
        $form->addField($holderSelector);
        $form->addField($dateFromPicker);
        $form->addField($dateToPicker);
        $form->add($button);
        return $form;
    }

    protected function createReportsGrid()
    {
        $grid = new EntityTable();
        $grid->addColumn("Holder", "holder");
        $grid->addColumn("Equipo", "device_id");
        $grid->addColumn("Reporte", "reporttype_description");
        $grid->addColumn("Fecha", "date", function ($date) { return DateUtils::formatDate($date, $this->getSession()->userDateFormat, $this->getSession()->userTimeZone);});
        $grid->addColumn("Tanque", "fuelTank");
        $grid->addColumn("Nivel (l)", "fuelLevel");
        $grid->addColumn("Temperatura (°c)", "fuelTemperature");
        $grid->setEntities($this->reports);
        return new Panel(array("title"=>"Histórico de Reportes de combustible", "content"=>$grid));
    }

    protected function createReportsGraphic()
    {
        $highchart = new Highchart($this);
        $highchart->setTitleAttributes(array('text' => "Histórico de Reportes de combustible"));
        $highchart->setXAxisAttributes(array('type' => "datetime"));
        $highchart->setYAxisAttributes(array('title' => array('text' => "l / °c")));
        if (!empty($this->reports)) 
        {
            $fuelLevelData = array();
            $fuelTemperatureData = array();
            foreach ($this->reports as $report) 
            {
                $fuelLevelData[] = array("Date.UTC(" . date("Y,m,d,H,i,s", strtotime($report->getDate())) . ")", $report->getFuelLevel());
                $fuelTemperatureData[] = array("Date.UTC(" . date("Y,m,d,H,i,s", strtotime($report->getDate())) . ")", $report->getFuelTemperature());
            }
            if (!empty($fuelLevelData)) 
            {
                $fuelLevelSerie = new HighchartSerie();
                $fuelLevelSerie->setType(HighchartSerie::TYPE_LINE);
                $fuelLevelSerie->setName("Nivel (l)");
                $fuelLevelSerie->setData($fuelLevelData);
                $highchart->addSerie($fuelLevelSerie);
            }
            if (!empty($fuelTemperatureData)) 
            {
                $fuelTemperatureSerie = new HighchartSerie();
                $fuelTemperatureSerie->setType(HighchartSerie::TYPE_LINE);
                $fuelTemperatureSerie->setName("Temperatura (°c)");
                $fuelTemperatureSerie->setData($fuelTemperatureData);
                $highchart->addSerie($fuelTemperatureSerie);
            }
        }
        return new Panel(array("title"=>"Histórico de Reportes de combustible", "content"=>$highchart));
    }
}

?>