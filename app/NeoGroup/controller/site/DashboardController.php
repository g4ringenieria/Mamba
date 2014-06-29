<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\model\HolderPeer;
use NeoGroup\view\DashboardView;

class DashboardController extends SiteController
{
    public function indexAction ()
    {
        $dashboardView = new DashboardView();
        $dashboardView->setHolders(HolderPeer::getHoldersWithLastReport($this->getSession()->clientId));
        $dashboardView->render();
    }
}

?>
