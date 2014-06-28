<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;
use NeoGroup\models\HolderPeer;
use NeoGroup\views\site\DashboardView;

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
