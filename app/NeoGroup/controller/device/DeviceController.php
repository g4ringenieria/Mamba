<?php

namespace NeoGroup\controller\device;

use NeoPHP\mvc\Controller;

abstract class DeviceController extends Controller
{
    public function notifyPackageAction ($package)
    {
        $deviceId = hexdec(substr($package, 0, 4));
        $datagramHexa = substr($package, 4);
        $datagram = hex2bin($datagramHexa);
        $this->getLogger()->info("Datagram received from \"" . ($deviceId>0?$deviceId:"?") . "\": " . $datagramHexa);
    }
}

?>