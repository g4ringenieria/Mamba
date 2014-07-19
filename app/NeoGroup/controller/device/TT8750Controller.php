<?php

namespace NeoGroup\controller\device;

class TT8750Controller extends DeviceController
{
    protected function __construct()
    {
        parent::__construct(8000);
    }
    
    public function notifyPackageReceived(DevicePackage $package)
    {
        return new DevicePackage(10064, "testResponse");
    }
}

?>