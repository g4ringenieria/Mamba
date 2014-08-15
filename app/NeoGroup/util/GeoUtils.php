<?php

namespace NeoGroup\util;

use Exception;

abstract class GeoUtils 
{
    public static function getCourseString ($course)
    {
        $courseStrings = array("N", "NE", "E", "SE", "S", "SO", "O", "NO", "N");
        $courseIndex = 0;
        for ($angle = 23; $angle < 360; $angle += 45) 
        {
            if ($course < $angle) 
                break;
            $courseIndex++;
        }
        return $courseStrings[$courseIndex];
    }
    
    public static function getLocation ($latitude, $longitude)
    {
        $location = "";
        if (!empty($latitude) && !empty($longitude)) 
        {
            try
            {
                $gl = json_decode(@file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&sensor=true"));
                if ($gl->status == "OK") 
                    $location = $gl->results[0]->formatted_address;
            } 
            catch (Exception $ex) {}
        }
        return $location;
    }
}

?>
