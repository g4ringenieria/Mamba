<?php

namespace NeoGroup\controller\site\report;

use NeoGroup\controller\site\SiteController;
use NeoGroup\model\ReportPeer;
use NeoGroup\view\report\FuelReportsView;

class FuelController extends SiteController
{
    public function indexAction ()
    {
        $reportsView = new FuelReportsView();
        $reportsView->render();
    }
    
    public function showReportsInTableAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new FuelReportsView();
        $reportsView->setReports(ReportPeer::getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(FuelReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInGraphicAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new FuelReportsView();
        $reportsView->setReports(ReportPeer::getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(FuelReportsView::OUTPUTTYPE_GRAPHIC);
        $reportsView->render();
    }
}

?>