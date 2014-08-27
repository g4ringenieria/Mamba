<?php

namespace Mamba\util;

abstract class DateUtils 
{
    public static function formatDate ($date, $format="Y-m-d H:i:s", $operationCount=0, $operand="hour")
    {
        $dateString = $date;
        if ($operationCount != 0)
            $dateString .= ($operationCount < 0? $operationCount : ("+".$operationCount)) . " " . $operand;
        return date ($format, strtotime ($dateString));
    }
    
    public static function getElapsedTime ($dateFrom, $dateTo, $units = 'seconds', $decimals = 2)
    {
        $divider['years'] = (60 * 60 * 24 * 365);
        $divider['months'] = (60 * 60 * 24 * 365 / 12);
        $divider['weeks'] = (60 * 60 * 24 * 7);
        $divider['days'] = (60 * 60 * 24);
        $divider['hours'] = (60 * 60);
        $divider['minutes'] = (60);
        $divider['seconds'] = 1;
        $elapsed_time = ((strtotime ($dateTo) - strtotime ($dateFrom)) / $divider[$units]);
        $elapsed_time = sprintf ("%0.{$decimals}f", $elapsed_time);
        return $elapsed_time;
    }
}

?>