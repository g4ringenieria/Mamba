<?php

namespace NeoGroup\processors;

use NeoPHP\app\Processor;
use NeoPHP\connection\Connection;
use NeoPHP\connection\ConnectionListener;

class CommandsProcessor extends Processor implements ConnectionListener
{
    public function start()
    {
        $this->getApplication()->getConnectionManager()->addConnectionListener ($this);
    }

    public function stop()
    {
        $this->getApplication()->getConnectionManager()->removeConnectionListener ($this);
    }
    
    public function onConnectionAdded(Connection $connection) {}
    public function onConnectionRemoved(Connection $connection) {}
    public function onConnectionDataSent(Connection $connection, $dataSent) {}
    
    public function onConnectionDataReceived(Connection $connection, $dataReceived)
    {
        if ($dataReceived{0} == '$')
        {
            $commandString = substr($dataReceived, 1);
            $commandTokens = $this->parseCommand($commandString);
            if (sizeof($commandTokens) >= 1)
            {
                $command = $commandTokens[0];
                $commandArguments = array_slice($commandTokens, 1);
                $this->getApplication()->getEventDispatcher()->fireEvent("commandEntered", array($connection, $command, $commandArguments));
            }
        }
    }
    
    private function parseCommand ($str)
    {
        $tokens = array();
        $tokenStart = -1;
        $inCommaToken = false;
        $commaChar = null;
        for ($index = 0; $index < strlen($str); $index++)
        {
            $char = substr($str, $index, 1);
            if (!$inCommaToken)
            {
                if (ctype_space($char))
                {
                    if ($tokenStart != -1)
                    {
                        $tokens[] = trim(substr($str, $tokenStart, ($index-$tokenStart))); 
                        $tokenStart = -1;
                    }
                }
                else if ($char == '"' || $char == '\'')
                {
                    if ($tokenStart != -1)
                        $tokens[] = trim(substr($str, $tokenStart, ($index-$tokenStart))); 
                    $tokenStart = $index+1;
                    $inCommaToken = true;
                    $commaChar = $char;
                }
                else if ($tokenStart == -1)
                {
                    $tokenStart = $index;
                }
            }
            else
            {
                if ($char == $commaChar)
                {
                    $tokens[] = trim(substr($str, $tokenStart, ($index-$tokenStart))); 
                    $inCommaToken = false;
                    $tokenStart = -1;
                }
            }
        }
        if ($tokenStart != -1 && !$inCommaToken)
            $tokens[] = trim(substr($str, $tokenStart));
        return $tokens;
    }
}

?>