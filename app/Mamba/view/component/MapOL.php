<?php

namespace Mamba\view\component;

use NeoPHP\mvc\MVCApplication;
use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;

class MapOL extends HTMLComponent
{
    public static $PROJECTION_4326 = "EPSG:4326";
    public static $PROJECTION_3857 = "EPSG:3857";
    
    private $view;
    private $attributes;
    private $targetScript;
    private $layerScripts;
    private $viewScript;
    private $preConfigScripts;
    private $postConfigScripts;
    
    public function __construct(HTMLView $view, array $attributes = array())
    {
        static $idCounter = 0;
        $this->view = $view;
        $this->attributes = array_merge(array("id"=>"map_" . ($idCounter++)), $attributes);
        $this->targetScript = 'document.getElementById("' . $this->attributes["id"] . '")';
        $this->layerScripts = array();
        $this->viewScript = 'new ol.View({ center: [0, 0], zoom: 2 })';
        $this->preConfigScripts = array();
        $this->postConfigScripts = array();
    }
    
    protected function createContent ()
    {
        return new Tag("div", $this->attributes, '');
    }
    
    public function fitOnLayer ($layerName, $buffer=100)
    {
        $this->addPostConfigScript('
            var layers = map.getLayers();
            layers.forEach(function (layer)
            {
                if (layer.getProperties().name == "' . $layerName . '")
                {
                    var extent = layer.getSource().getExtent();
                    map.getView().fitExtent(ol.extent.buffer(extent, ' . $buffer . '), map.getSize());
                }
            });
        ');
    }
    
    public function addPopupSupport ()
    {
        $this->addPostConfigScript('
            map.on("click", function(evt) 
            {
                if (map.popupElement == null)
                {
                    map.popupElement = $("<div id=\"popup\"></div>");
                    $(map.getTarget()).append(map.popupElement);

                    map.popupOverlay = new ol.Overlay(
                    {
                        element: map.popupElement.get(0),
                        positioning: "bottom-center",
                        stopEvent: false
                    });
                    map.addOverlay(map.popupOverlay);
                }
                var featureFound = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) 
                { 
                    var popupOffset = 0;
                    var style = feature.getStyle() || layer.getStyle();
                    if (style != null && (typeof style === "object"))
                    {
                        var image = style.getImage();
                        if (image != null)
                        {
                            var imageAnchor = image.getAnchor();
                            if (imageAnchor != null)
                                popupOffset = imageAnchor[1] + 2;
                        }
                    }
                    map.popupElement.css("padding-bottom", popupOffset);

                    var geometry = feature.getGeometry();
                    var coord = geometry.getCoordinates();
                    map.popupOverlay.setPosition(coord);
                    map.popupElement.popover(
                    {
                        "placement": "top",
                        "html": true,
                        "content": feature.get("description")
                    });
                    map.popupElement.popover("show");
                    return true; 
                });

                if (!featureFound) 
                {
                    map.popupElement.popover("hide");
                }
            });

            $(map.getViewport()).on("mousemove", function(e) 
            {
                var pixel = map.getEventPixel(e.originalEvent);
                var hit = map.forEachFeatureAtPixel(pixel, function(feature, layer) { return true; });
                map.getTarget().style.cursor = (hit)? "pointer" : "";
            });
        ');
        $this->view->addStyle('
            .popover { max-width: 400px; }
            .popover-content { white-space: nowrap; }
        ');
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
                    view: {view}
                });
                {postConfig}
            })();
        ';
        $layersScript = '[' . implode(",\r\n", $this->layerScripts) . ']';
        $preConfigScripts = implode("\r\n", $this->preConfigScripts);
        $postConfigScripts = implode("\r\n", $this->postConfigScripts);
        $scriptTemplate = str_replace('{target}', $this->targetScript, $scriptTemplate);
        $scriptTemplate = str_replace('{layers}', $layersScript, $scriptTemplate);
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
        if (!empty($feature->geometry))
            $featureAttributes[] = 'geometry: ' . self::createGeometry($feature->geometry);
        if (!empty($feature->description))
        {
            $description = $feature->description;
            $description = str_replace("\n", "", $description);
            $description = str_replace("\r", "", $description);
            $featureAttributes[] = 'description: "' . $description . '"';
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
            $attributes[] = 'anchor: [' . $iconStyle->anchor[0] . ',' . $iconStyle->anchor[1] . ']';
        if (!empty($iconStyle->anchorXUnits))
            $attributes[] = 'anchorXUnits: "' . $iconStyle->anchorXUnits . '"';
        if (!empty($iconStyle->anchorYUnits))
            $attributes[] = 'anchorYUnits: "' . $iconStyle->anchorYUnits . '"';
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