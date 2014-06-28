<?php

namespace NeoGroup\controllers;

use NeoGroup\models\HolderPeer;

class HoldersController extends EntityController
{
    public function getResourceAction ($query=null)
    {
        return HolderPeer::getHolders($this->getSession()->clientId, $query);
    }
}

?>