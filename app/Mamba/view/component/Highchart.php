<?php

namespace Mamba\view\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class Highchart extends HTMLComponent
{

    private $id;
    private $titleAttributes;
    private $xAxisAttributes;
    private $yAxisAttributes;
    private $attributes;
    private $series;

    public function __construct(HTMLView $view)
    {
        static $idCounter = 0;
        $this->id = "highchart_" . ($idCounter++);
        $this->view = $view;
        $this->series = array();
    }
    
    public function getTitleAttributes()
    {
        return $this->titleAttributes;
    }

    public function setTitleAttributes($titleAttributes)
    {
        $this->titleAttributes = $titleAttributes;
    }

    public function getXAxisAttributes()
    {
        return $this->xAxisAttributes;
    }

    public function setXAxisAttributes($xAxisAttributes)
    {
        $this->xAxisAttributes = $xAxisAttributes;
    }

    public function getYAxisAttributes()
    {
        return $this->yAxisAttributes;
    }

    public function setYAxisAttributes($yAxisAttributes)
    {
        $this->yAxisAttributes = $yAxisAttributes;
    }
        
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries($series)
    {
        $this->series = $series;
    }

    public function addSerie($serie)
    {
        $this->series[] = $serie;
    }

    protected function createContent()
    {
        $div = new Tag("div", array("id" => $this->id));
        return $div;
    }

    protected function onBeforeBuild()
    {
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/highcharts-4.0.3/js/highcharts.js");
        $script = "$(function () {\n";
        $script .= "    $('#{$this->id}').highcharts({\n";
        $script .= "        chart: {";
        $script .= "            zoomType: 'x'";
        $script .= "        },";
        if (!empty($this->titleAttributes)) {
        $script .= "        title: {\n";
        if (!empty($this->titleAttributes['text'])) {
        $script .= "            text: '" . $this->titleAttributes['text'] . "',\n";
        }
        $script .= "        },\n";
        }
        if (!empty($this->xAxisAttributes)) {
        $script .= "        xAxis: {\n";
        if (!empty($this->xAxisAttributes['type'])) {
        $script .= "            type: '" . $this->xAxisAttributes['type'] . "',\n";
        }
        $script .= "        },\n";
        }
        if (!empty($this->yAxisAttributes)) {
        $script .= "        yAxis: {\n";
        if (!empty($this->yAxisAttributes['title'])) {
        $script .= "            title: {\n";
        if (!empty($this->yAxisAttributes['title']['text'])) {
        $script .= "                text: '" . $this->yAxisAttributes['title']['text'] . "'\n";
        }
        $script .= "            },\n";
        }
        $script .= "        },\n";
        }
        $script .= "        tooltip: {\n";
//        $script .= "            valueSuffix: '°C'\n";
        $script .= "        },\n";
        $script .= "        legend: {\n";
        $script .= "            layout: 'vertical',\n";
        $script .= "            align: 'right',\n";
        $script .= "            verticalAlign: 'middle',\n";
        $script .= "            borderWidth: 0\n";
        $script .= "        },\n";
        $script .= "        series: [\n";
        if (!empty($this->series)) {
        foreach ($this->series as $serie) {
        $script .= "            {\n";
        $script .= "                type: '" . $serie->getType() . "',\n";
        $script .= "                name: '" . $serie->getName() . "',\n";
        $seriedata = $serie->getData();
        if (!empty($seriedata)) {
        $script .= "                data: [\n";
        foreach ($seriedata as $data) {
        $script .= "                    [" . implode(",", $data) . "],\n";
        }
        }
        $script .= "                ]\n";
        $script .= "            },\n";
        }
        }
        $script .= "        ]\n";
        $script .= "    });\n";
        $script .= "});\n";
        $this->view->addScript($script);
    }

}
?>

