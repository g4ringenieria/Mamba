<?php

namespace NeoGroup\processors;

use Exception;
use NeoPHP\app\Processor;
use NeoPHP\connection\Connection;
use NeoPHP\connection\ConnectionListener;

abstract class DeviceProcessor extends Processor implements ConnectionListener
{
    private static $connectionProcessor = array();
    
    public function start()
    {
        $this->getApplication()->getConnectionManager()->addConnectionListener ($this);
    }

    public function stop()
    {
        $this->getApplication()->getConnectionManager()->removeConnectionListener ($this);
    }

    public function onConnectionAdded(Connection $connection) {}
    public function onConnectionDataSent(Connection $connection, $dataSent) {}
    
    public function onConnectionDataReceived(Connection $connection, $dataReceived)
    {
        $connectionId = $connection->getId();
        if (!isset($this->connectionProcessor[$connectionId]))
        {
            if ($this->checkDatagramValidity($dataReceived))
            {
                $this->processDatagram ($connection, $dataReceived);
                $this->connectionProcessor[$connectionId] = $this;
            }
        }
        else if ($this->connectionProcessor[$connectionId] === $this)
        {
            $this->processDatagram ($connection, $dataReceived);
        }    
    }
    
    public function onConnectionRemoved(Connection $connection) 
    {
        $connectionId = $connection->getId();
        if (!empty($connectionId))
            unset($this->connectionProcessor[$connectionId]);
    }
    
    private function processDatagram (Connection $connection, $datagram)
    {
        try
        {
            $this->datagramReceived ($connection, $datagram);   
        }
        catch (Exception $ex)
        {
            $this->getLogger()->error ("Error with incoming package (" . bin2hex($datagram) . "). Message: " . $ex->getMessage());
        }
    }
    
    protected function sendDatagram (Connection $connection, $datagram)
    {
        $connection->send($datagram);
    }
    
    protected abstract function checkDatagramValidity ($datagram);
    protected abstract function datagramReceived (Connection $connection, $datagram);
}

?>