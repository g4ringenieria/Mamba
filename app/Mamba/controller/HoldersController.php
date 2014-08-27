<?php

namespace Mamba\controller;

use Mamba\model\HolderPeer;

class HoldersController extends EntityController
{
    public function getResourceAction ($query=null)
    {
        return HolderPeer::getHolders($this->getSession()->clientId, $query);
    }
}

?>