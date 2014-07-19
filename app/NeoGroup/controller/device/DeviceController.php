<?php

namespace NeoGroup\controller\device;

use NeoPHP\mvc\Controller;

abstract class DeviceController extends Controller
{
    public function notifyPackageAction ($datagram)
    {
        $package = new DevicePackage($datagram);
        $responsePackage = $this->notifyPackageReceived($package);
        if ($responsePackage != null && $responsePackage instanceof DevicePackage)
            print($responsePackage);
        $this->getLogger()->info("Datagram received from \"" . ($package->getDeviceId() >0 ? $package->getDeviceId() : "?") . "\": " . substr($datagram, 4));
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