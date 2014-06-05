<?php

namespace NeoGroup\processors;

use NeoPHP\app\Processor;
use NeoPHP\connection\Connection;
use NeoPHP\connection\ConnectionListener;

class ConnectionsDebugProcessor extends Processor implements ConnectionListener
{
    public function start()
    {
        $this->getApplication()->getConnectionManager()->addConnectionListener ($this);
    }

    public function stop()
    {
        $this->getApplication()->getConnectionManager()->removeConnectionListener ($this);
    }
    
    public function onConnectionAdded(Connection $connection)
    {
        $this->getLogger()->debug ("Connection Added (" . $connection . ") !!");
    }

    public function onConnectionRemoved(Connection $connection)
    {
        $this->getLogger()->debug ("Connection Removed (" . $connection . ") !!");
    }
    
    public function onConnectionDataReceived(Connection $connection, $dataReceived)
    {
        $this->getLogger()->debug ("Connection data received from (" . $connection . "): " . bin2hex($dataReceived));
    }

    public function onConnectionDataSent(Connection $connection, $dataSent)
    {
        $this->getLogger()->debug ("Connection data sent to (" . $connection . "): " . bin2hex($dataSent));
    }
}

?>