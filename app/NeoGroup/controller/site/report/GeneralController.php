<?php

namespace NeoGroup\controller\site\report;

use NeoGroup\controller\site\SiteController;
use NeoGroup\model\ReportPeer;
use NeoGroup\view\report\ReportsView;

class GeneralController extends SiteController
{
    public function indexAction ()
    {
        $reportsView = new ReportsView();
        $reportsView->render();
    }
    
    public function showReportsInTableAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new ReportsView();
        $reportsView->setReports(ReportPeer::getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInMapAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new ReportsView();
        $reportsView->setReports(ReportPeer::getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_MAP);
        $reportsView->render();
    }
}

?>