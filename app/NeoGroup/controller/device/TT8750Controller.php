<?php

namespace NeoGroup\controller\device;

class TT8750Controller extends DeviceController
{
    public function notifyPackageReceived(DevicePackage $package)
    {
        return new DevicePackage(10064, "test");
    }
}

?>