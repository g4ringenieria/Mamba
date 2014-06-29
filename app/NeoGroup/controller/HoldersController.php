<?php

namespace NeoGroup\controller;

use NeoGroup\model\HolderPeer;

class HoldersController extends EntityController
{
    public function getResourceAction ($query=null)
    {
        return HolderPeer::getHolders($this->getSession()->clientId, $query);
    }
}

?>