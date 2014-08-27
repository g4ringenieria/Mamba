<?php

namespace Mamba\util;

use Exception;
use NeoPHP\socket\Socket;

abstract class ServerUtils 
{
    public static function sendCommand ($command, $waitResponse=false)
    {
        $socket = null;
        $socketResponse = null;
        $socketException = null;
        try
        {
            $socket = new Socket("localhost", 8000);
            $socket->connect();   
            $socket->send($command);
            if ($waitResponse)
                $socketResponse = $socket->receive();
        }
        catch (Exception $exception)
        {
            $socketException = $exception;
        }
        try { $socket->close(); } catch (Exception $ex) {}
        if (!empty($socketException))
            throw $socketException;
        return $socketResponse;
    }
}

?>
