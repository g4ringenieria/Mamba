<?php

namespace NeoGroup\controller\device;

use Exception;
use NeoPHP\mvc\Controller;
use NeoPHP\socket\Socket;

abstract class DeviceController extends Controller
{
    const DEBUGMODE = false;
    
    private $port;
    
    protected function __construct($port)
    {
        $this->port = $port;
    }
    
    public function notifyPackageAction ($datagram)
    {
        $package = new DevicePackage($datagram);
        if (DEBUGMODE)
            $this->getLogger()->info("Datagram received from \"" . ($package->getDeviceId() >0 ? $package->getDeviceId() : "?") . "\": " . substr($datagram, 4));
        $responsePackage = $this->notifyPackageReceived($package);
        if ($responsePackage != null && $responsePackage instanceof DevicePackage)
            print($responsePackage);
    }
    
    public function sendToDevice ($deviceId, $data)
    {
        $this->sendPackage(new DevicePackage($deviceId, $data));
    }
    
    public function sendPackage (DevicePackage $package)
    {
        $socket = null;
        try
        {
            $socket = new Socket();
            $socket->connect("localhost", $this->port);   
            $socket->send("sendPackage $package");
        }
        catch (Exception $exception)
        {
            try { $socket->close(); } catch (Exception $ex) {}
            throw $exception;
        }
    }
    
    public abstract function notifyPackageReceived (DevicePackage $package);
}

final class DevicePackage
{
    private $deviceId;
    private $data;
    
    public function __construct($deviceId, $data=null)
    {
        if (isset($data))
        {
            $this->deviceId = $deviceId;
            $this->data = $data;
        }
        else
        {
            $this->deviceId = hexdec(substr($deviceId, 0, 4));
            $this->data = hex2bin(substr($deviceId, 4));
        }
    }
    
    public function getDeviceId()
    {
        return $this->deviceId;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function __toString()
    {
        return str_pad(dechex($this->deviceId), 4, "0", STR_PAD_LEFT) . bin2hex($this->data);
    }
}

?>