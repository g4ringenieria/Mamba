<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\site\SiteController;
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
