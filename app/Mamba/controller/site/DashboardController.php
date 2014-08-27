<?php

namespace Mamba\controller\site;

use Mamba\controller\site\SiteController;
use Mamba\model\HolderPeer;
use Mamba\view\DashboardView;

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
