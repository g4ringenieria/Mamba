<?php

namespace Mamba\component;

use NeoPHP\mvc\MVCApplication;
use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class MapOL extends HTMLComponent
{
    public static $PROJECTION_4326 = "EPSG:4326";
    public static $PROJECTION_3857 = "EPSG:3857";
    
    protected $view;
    protected $attributes;
    protected $targetScript;
    protected $layerScripts;
    protected $controlsScripts;
    protected $viewScript;
    protected $preConfigScripts;
    protected $postConfigScripts;
    
    public function __construct(HTMLView $view, array $attributes = array())
    {
        static $idCounter = 0;
        $this->view = $view;
        $this->attributes = array_merge(array("id"=>"map_" . ($idCounter++)), $attributes);
        $this->targetScript = 'document.getElementById("' . $this->attributes["id"] . '")';
        $this->layerScripts = array();
        $this->controlsScripts = array();
        $this->viewScript = 'new ol.View({ center: [0, 0], zoom: 2 })';
        $this->preConfigScripts = array();
        $this->postConfigScripts = array();
    }
    
    protected function createContent ()
    {
        return new Tag("div", $this->attributes, '');
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScriptFile(MVCApplication::getInstance()->getBaseUrl() . "assets/openlayers-3.0/build/ol.js"); 
        $this->view->addStyleFile(MVCApplication::getInstance()->getBaseUrl() . "assets/openlayers-3.0/css/ol.css");
        $scriptTemplate = '
            (function()
            {
                {preConfig}
                var map = new ol.Map(
                {
                    target: {target},
                    layers: {layers},
                    controls: {controls},
                    view: {view}
                });
                {postConfig}
            })();
        ';
        $layersScript = '[' . implode(",\r\n", $this->layerScripts) . ']';
        $controlsScript = '[' . implode(",\r\n", $this->controlsScripts) . ']';
        $preConfigScripts = implode("\r\n", $this->preConfigScripts);
        $postConfigScripts = implode("\r\n", $this->postConfigScripts);
        $scriptTemplate = str_replace('{target}', $this->targetScript, $scriptTemplate);
        $scriptTemplate = str_replace('{layers}', $layersScript, $scriptTemplate);
        $scriptTemplate = str_replace('{controls}', $controlsScript, $scriptTemplate);
        $scriptTemplate = str_replace('{view}', $this->viewScript, $scriptTemplate);
        $scriptTemplate = str_replace('{preConfig}', $preConfigScripts, $scriptTemplate);
        $scriptTemplate = str_replace('{postConfig}', $postConfigScripts, $scriptTemplate);
        $this->view->addScript($scriptTemplate);
    }
    
    public function addLayer ($layer)
    {
        $layerScript = null;
        switch ($layer->type)
        {
            case "tile":
                $layerScript = $this->createTileLayer($layer);
                break;
            case "vector":
                $layerScript = $this->createVectorLayer($layer);
                break;
        }
        $this->layerScripts[] = $layerScript;
    }
    
    public function addControl ($control)
    {
        $controlScript = null;
        switch ($control->type)
        {
            case "zoom":
                $controlScript = "new ol.control.Zoom()";
                break;
            case "scaleline":
                $controlScript = "new ol.control.ScaleLine()";
                break;
            case "rotate":
                $controlScript = "new ol.control.Rotate()";
                break;
            case "fullscreen":
                $controlScript = "new ol.control.FullScreen()";
                break;
        }
        $this->controlsScripts[] = $controlScript;
    }
    
    public function setView ($view)
    {
        $this->viewScript = 'new ol.View({ center: [' . $view->center[0] . ', ' . $view->center[1] . '], zoom: ' . $view->zoom . ' })';
    }
    
    public function addPreConfigScript ($preConfigScript)
    {
        $this->preConfigScripts[] = $preConfigScript;
    }
    
    public function addPostConfigScript ($postConfigScript)
    {
        $this->postConfigScripts[] = $postConfigScript;
    }
    
    public static function createTileLayer ($layer)
    {
        $layerScript = '';
        $layerScript .= 'new ol.layer.Tile(';
        $layerAttributes = array();
        if (!empty($layer->source))
            $layerAttributes[] = 'source: ' . self::createSource($layer->source);
        if (sizeof($layerAttributes) > 0)
            $layerScript .= '{' . implode(",", $layerAttributes) . '}';
        $layerScript .= ')';
        return $layerScript;
    }
    
    public static function createVectorLayer ($layer)
    {
        $layerScript = '';
        $layerScript .= 'new ol.layer.Vector(';
        $layerAttributes = array();
        if (!empty($layer->name))
            $layerAttributes[] = 'name: "' . $layer->name . '"';
        if (!empty($layer->source))
            $layerAttributes[] = 'source: ' . self::createSource($layer->source);
        if (!empty($layer->style))
            $layerAttributes[] = 'style: ' . self::createStyle($layer->style);
        if (sizeof($layerAttributes) > 0)
            $layerScript .= '{' . implode(",", $layerAttributes) . '}';
        $layerScript .= ')';
        return $layerScript;
    }
    
    public static function createSource ($source)
    {
        $sourceScript = null;
        switch ($source->type)
        {
            case "osm":
                $sourceScript = self::createOSMSource($source);
                break;
            case "mapQuest":
                $sourceScript = self::createMapQuestSource($source);
                break;
            case "vector":
                $sourceScript = self::createVectorSource($source);
                break;
        }
        return $sourceScript;
    }
    
    public static function createOSMSource ($source)
    {
        return 'new ol.source.OSM()';
    }
    
    public static function createMapQuestSource ($source)
    {
        return 'new ol.source.MapQuest({layer: "' . $source->layerType . '"})';
    }
    
    public static function createVectorSource ($source)
    {
        $sourceScript = '';
        $sourceScript .= 'new ol.source.Vector(';
        $sourceAttributes = array();
        if (!empty($source->features))
        {
            $featuresScripts = array();
            foreach ($source->features as $feature)
                $featuresScripts[] = self::createFeature ($feature);
            $sourceAttributes[] = 'features: [' . implode(",", $featuresScripts) . ']';
        }
        if (sizeof($sourceAttributes) > 0)
            $sourceScript .= '{' . implode(",", $sourceAttributes) . '}';
        $sourceScript .= ')';
        return $sourceScript;
    }
    
    public static function createFeature ($feature)
    {
        $featureScript = '';
        $featureScript .= 'new ol.Feature(';
        $featureAttributes = array();
        foreach ($feature as $property=>$propertyValue)
        {
            switch ($property)
            {
                case "geometry": 
                    $featureAttributes[] = 'geometry: ' . self::createGeometry($feature->geometry);
                    break;
                default:
                    $featureAttributes[] = $property . ': "' . $propertyValue . '"';
                    break;
            }
        }
        if (sizeof($featureAttributes) > 0)
            $featureScript .= '{' . implode(",", $featureAttributes) . '}';
        $featureScript .= ')';
        return $featureScript;
    }
    
    public static function createGeometry ($geometry)
    {
        $geometryScript = null;
        switch ($geometry->type)
        {
            case "point": 
                $geometryScript = self::createPointGeometry ($geometry);
                break;
        }
        return $geometryScript;
    }
    
    public static function createStyle ($style)
    {
        if (is_string($style)) return $style;
        $styleScript = '';
        $styleScript .= 'new ol.style.Style(';
        $attributes = array();
        if (!empty($style->image))
            $attributes[] = 'image: ' . self::createIconStyle($style->image);
        if (sizeof($attributes) > 0)
            $styleScript .= '{' . implode(",", $attributes) . '}';
        $styleScript .= ')';
        return $styleScript;
    }
    
    public static function createIconStyle ($iconStyle)
    {
        $iconStyleScript = '';
        $iconStyleScript .= 'new ol.style.Icon(';
        $attributes = array();
        if (!empty($iconStyle->src))
            $attributes[] = 'src: "' . $iconStyle->src . '"';
        if (!empty($iconStyle->anchor))
        {
            $attributes[] = 'anchor: [' . $iconStyle->anchor[0] . ',' . $iconStyle->anchor[1] . ']';
            $attributes[] = 'anchorXUnits: "' . ((!empty($iconStyle->anchorXUnits))? $iconStyle->anchorXUnits : "pixels") . '"';
            $attributes[] = 'anchorYUnits: "' . ((!empty($iconStyle->anchorYUnits))? $iconStyle->anchorYUnits : "pixels") . '"';
        }
        if (!empty($iconStyle->opacity))
            $attributes[] = 'opacity: ' . $iconStyle->opacity;
        if (sizeof($attributes) > 0)
            $iconStyleScript .= '{' . implode(",", $attributes) . '}';
        $iconStyleScript .= ')';
        return $iconStyleScript;
    }
    
    public static function createPointGeometry ($geometry)
    {
        return 'new ol.geom.Point(' . self::createCoordinate($geometry->coordinates) . ')';
    }
    
    public static function createCoordinate (array $coordinates)
    {
        return 'ol.proj.transform([' . $coordinates[0] . ',' . $coordinates[1] . '], "' . self::$PROJECTION_4326 . '", "' . self::$PROJECTION_3857 . '")';
    }
    
    public static function createExtent (array $extent)
    {
        return 'ol.proj.transformExtent([' . $coordinate[0] . ',' . $coordinate[1] . ',' . $coordinate[2] . ',' . $coordinate[3] . '], "' . PROJECTION_4326 . '", "' . PROJECTION_3857 . '")';
    }
}

?>