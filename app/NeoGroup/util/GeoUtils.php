<?php

namespace NeoGroup\util;

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
}

?>
