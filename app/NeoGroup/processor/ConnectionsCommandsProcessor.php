<?php

namespace NeoGroup\processor;

use Exception;
use NeoPHP\app\Processor;
use NeoPHP\connection\Connection;

class ConnectionsCommandsProcessor extends Processor
{
    public function start()
    {
        $this->getApplication()->getEventDispatcher()->addListener("commandEntered", $this);
    }

    public function stop()
    {
        $this->getApplication()->getEventDispatcher()->removeListener("commandEntered", $this);
    }
    
    public function onCommandEntered (Connection $connection, $command, $commandArguments)
    {
        switch ($command)
        {
            case "listConnections":
            case "lc":
                $connectionManager = $this->getApplication()->getConnectionManager();
                $connections = $connectionManager->getConnections();
                $response = "Connections count: " . sizeof($connections) . "\n\r";
                foreach ($connections as $conn)
                    $response .= $conn . "\n\r";
                $connection->send($response);
                break;
            case "closeConnection":
            case "cc";
                $response = "";
                if (!isset($commandArguments[0]))
                {
                    $response .= "Usage: closeConnection connectionId\n\r\n\r";
                }
                else
                {
                    $connectionId = $commandArguments[0];
                    $connectionToClose = $this->getApplication()->getConnectionManager()->getConnection($connectionId);
                    if ($connectionToClose == null)
                    {
                        $response .= "Connection \"" . $connectionId . "\" not found !!\n\r";
                    }
                    else
                    {
                        try
                        {
                            $connectionToClose->close();
                            $response .= "Connection \"" . $connectionId . "\" closed succesfully\n\r";
                        }
                        catch (Exception $ex)
                        {
                            $response .= "Connection \"" . $connectionId . "\" could not be closed\n\r";
                        }
                    }
                }
                $connection->send($response);
                break;
            case "sendConnection":
            case "sc":
                $response = "";
                if (sizeof($commandArguments) < 2)
                {
                    $response .= "Usage: sendConnection [options] connectionId message\n\r";
                }
                else
                {
                    $connectionId = $commandArguments[sizeof($commandArguments) - 2];
                    $data = $commandArguments[sizeof($commandArguments) - 1];
                    if (array_search("--hex", $commandArguments) !== false)
                        $data = hex2bin ($data);
                    
                    try
                    {
                        $this->getApplication()->getConnectionManager()->sendToConnectionId($connectionId, $data);
                        $response .= "Message sent successfully\n\r";
                    }
                    catch (Exception $ex)
                    {
                        $response .= "Message could not be sent. Error: " . $ex->getMessage() . "\n\r";
                    }
                }
                $connection->send($response);
                break;
        }
    }
}

?>