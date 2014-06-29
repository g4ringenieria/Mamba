<?php

namespace NeoGroup\component;

use NeoPHP\web\html\HTMLComponent;
use NeoPHP\web\html\HTMLView;
use NeoPHP\web\html\Tag;
use stdClass;

class Map extends HTMLComponent
{
    private $view;
    private $id;
    private $className;
    private $attributes;
    private $initialPosition;
    private $initialZoom;
    private $attribution;
    private $baseLayers;
    private $overlays;
    
    public function __construct(HTMLView $view)
    {
        static $idCounter = 0;
        $this->view = $view;
        $this->attributes = array();
        $this->id = "map_" . ($idCounter++); 
        $this->className = "map";
        $this->initialPosition = new stdClass();
        $this->initialPosition->latitude = 0;
        $this->initialPosition->longitude = 0;
        $this->initialZoom = 3;
        $this->attribution = "";
        $this->baseLayers = array();
        $this->overlays = array();
    }
    
    public function setAttributes ($attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function addBaseLayer ($baseLayer)
    {
        $this->baseLayers[] = $baseLayer;
    }
    
    public function addOverlay ($overlay)
    {
        $this->overlays[] = $overlay;
    }
    
    public function setInitialPosition ($position)
    {
        $this->initialPosition = $position;
    }
    
    public function setInitialZoom ($zoom)
    {
        $this->initialZoom = $zoom;
    }
    
    public function setAttribution ($attribution)
    {
        $this->attribution = $attribution;
    }
    
    protected function createContent ()
    {
        return new Tag("div", array_merge(array("id"=>$this->id,"class"=>$this->className), $this->attributes), '');
    }
    
    protected function onBeforeBuild ()
    {
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/leaflet-0.7.2/leaflet.js"); 
        $this->view->addStyleFile($this->view->getBaseUrl() . "assets/leaflet-0.7.2/leaflet.css");    
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/leaflet-label/leaflet.label.js");
        $this->view->addStyleFile($this->view->getBaseUrl() . "assets/leaflet-label/leaflet.label.css");
        $this->view->addScriptFile($this->view->getBaseUrl() . "assets/leaflet-oms/oms.min.js");
        $this->view->addScript('
            var maps = {};
            
            function getMap (id) 
            { 
                return maps[id]; 
            }
            
            function createMap (mapConfig)
            {
                var map = L.map(mapConfig.id, 
                {
                    center: mapConfig.center,
                    zoom: mapConfig.zoom,
                    attribution: mapConfig.attribution
                });
                map.oms = new OverlappingMarkerSpiderfier(map);
                map.layersControl = L.control.layers([], []);
                map.layersControl.addTo(map);
                
                if (mapConfig.baseLayers)
                {
                    var baseLayers = [];
                    for (var i in mapConfig.baseLayers)
                        baseLayers.push (createBaseLayer(mapConfig.baseLayers[i]));
                    map.addLayer(baseLayers[0]);
                    for (var i in baseLayers)
                        map.layersControl.addBaseLayer(baseLayers[i], baseLayers[i].name);
                }
                
                if (mapConfig.overlays)
                {
                    for (var i in mapConfig.overlays)
                    {
                        var overlayConfig = mapConfig.overlays[i];
                        overlayConfig.oms = map.oms;
                        var overlay = createOverlay(overlayConfig);
                        if (overlay != null)
                        {
                            overlay.addTo(map);
                            map.layersControl.addOverlay(overlay, overlay.name);
                            if (overlayConfig.fitToBounds)
                                map.fitBounds(overlay.getBounds().pad(0.1));
                        }
                    }
                }
                
                maps[mapConfig.id] = map;
            }
            
            function createBaseLayer (baseLayerConfig)
            {
                var baseLayer = L.tileLayer(baseLayerConfig.url, baseLayerConfig.attributes);
                baseLayer.name = baseLayerConfig.name;
                return baseLayer;
            }
            
            function createOverlay (overlayConfig)
            {
                var overlay = null;
                switch (overlayConfig.type)
                {
                    case "featureGroup":
                        overlay = createFeatureGroup (overlayConfig);
                        break;
                    case "marker":
                        overlay = createMarker (overlayConfig);
                        break;
                    case "polyline":
                        overlay = createPolyline (overlayConfig)
                        break;
                }
                if (overlay != null)
                {
                    overlay.name = overlayConfig.name;
                }
                return overlay;
            }
            
            function createFeatureGroup (featureGroupConfig)
            {
                var layerGroup = new L.FeatureGroup();
                if (featureGroupConfig.overlays)
                {
                    for (var i in featureGroupConfig.overlays)
                    {
                        var overlayConfig = featureGroupConfig.overlays[i];
                        overlayConfig.oms = featureGroupConfig.oms;
                        var overlay = createOverlay(overlayConfig);
                        if (overlay != null)
                            overlay.addTo(layerGroup);
                    }
                }
                return layerGroup;
            }
            
            function createMarker (markerConfig)
            {
                var marker = null;
                if (markerConfig.latitude != null && markerConfig.longitude != null)
                {
                    marker = L.marker([markerConfig.latitude, markerConfig.longitude]);
                    if (markerConfig.description)
                        marker.bindPopup(markerConfig.description);
                    if (markerConfig.label)
                        marker.bindLabel(markerConfig.label, markerConfig.labelConfig || {});
                    if (markerConfig.oms)
                        markerConfig.oms.addMarker(marker);
                }
                return marker;
            }
            
            function createPolyline (polylineConfig)
            {
                var points = [];
                for (var i in polylineConfig.points)
                {
                    var point = polylineConfig.points[i];
                    points.push (L.latLng(point.latitude, point.longitude));
                }
                return L.polyline(points);
            }
        ', 'mapJSFunctions');
        
        $mapConfig = new stdClass();
        $mapConfig->id = $this->id;
        $mapConfig->center = array($this->initialPosition->latitude, $this->initialPosition->longitude); 
        $mapConfig->zoom = $this->initialZoom;
        $mapConfig->attribution = $this->attribution;
        $mapConfig->baseLayers = $this->baseLayers;
        $mapConfig->overlays = $this->overlays;
        $this->view->addScript('createMap(' . json_encode($mapConfig) . ')');
    }
    
    public function addDefaultBaseLayers ()
    {
        $this->addBaseLayer ($this->createMapQuestOpenOSMLayer());
        $this->addBaseLayer ($this->createOpenCycleMapLayer());
        $this->addBaseLayer ($this->createOpenStreetMapMapnikLayer());
        $this->addBaseLayer ($this->createOpenStreetMapDELayer());
        $this->addBaseLayer ($this->createEsriWorldStreetMapLayer());
        $this->addBaseLayer ($this->createEsriWorldImageryLayer());
    }
    
    public static function createLayer ($name, $url, $attributes=array())
    {
        $layer = new stdClass();
        $layer->name = $name;
        $layer->url = $url;
        $layer->attributes = $attributes;
        return $layer;
    }
    
    public static function createCloudMadeDefaultLayer ()
    {
        return self::createLayer ("CloudMade (Default)", "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/{styleId}/256/{z}/{x}/{y}.png", array("styleId"=>997, "attribution"=>""));
    }
    
    public static function createCloudMadeMidnightLayer ()
    {
        return self::createLayer ("CloudMade (Midnight)", "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/{styleId}/256/{z}/{x}/{y}.png", array("styleId"=>999, "attribution"=>""));
    }
    
    public static function createCloudMadeMinimalLayer ()
    {
        return self::createLayer ("CloudMade (Minimal)", "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/{styleId}/256/{z}/{x}/{y}.png", array("styleId"=>22677, "attribution"=>""));
    }
    
    public static function createOpenStreetMapMapnikLayer ()
    {
        return self::createLayer ("OpenStreetMap (Mapnik)", "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", array("attribution"=>'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'));
    }
    
    public static function createOpenStreetMapDELayer ()
    {
        return self::createLayer ("OpenStreetMap (DE)", "http://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png", array("attribution"=>'&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'));
    }
    
    public static function createOpenCycleMapLayer ()
    {
        return self::createLayer ("OpenCycleMap", "http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png", array("attribution"=>'&copy; <a href="http://www.opencyclemap.org">OpenCycleMap</a>, &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'));
    }
    
    public static function createMapQuestOpenOSMLayer ()
    {
        return self::createLayer ("MapQuestOpen (OSM)", 'http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg', array("attribution"=>'Tiles Courtesy of <a href="http://www.mapquest.com/">MapQuest</a> &mdash; Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>', "subdomains"=>'1234'));
    }
    
    public static function createEsriWorldStreetMapLayer ()
    {
        return self::createLayer ("Esri (WorldStreetMap)", 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', array("attribution"=>'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012'));
    }
    
    public static function createEsriWorldTopoMapLayer ()
    {
        return self::createLayer ("Esri (WorldTopoMap)", 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', array("attribution"=>'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community'));
    }
    
    public static function createEsriWorldImageryLayer ()
    {
        return self::createLayer ("Esri (WorldImagery)", 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', array("attribution"=>'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'));
    }
}

?>