<?php

namespace Mamba\controller;

use Mamba\model\DevicePeer;

class DevicesController extends EntityController
{
    public function getResourceAction ($query=null)
    {
        return DevicePeer::getDevices($this->getSession()->clientId, $query);
    }
}

?>