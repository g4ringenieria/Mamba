<?php

namespace NeoGroup\controller\site\report;

use NeoGroup\controller\site\SiteController;
use NeoGroup\model\Report;
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
        $reportsView->setReports(ReportPeer::getReports(array("reportClass"=>Report::CLASSTYPE_FUELPOSITION, "holderId"=>$holderId, "dateFrom"=>$dateFrom, "dateTo"=>$dateTo)));
        $reportsView->setOutputType(FuelReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInGraphicAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new FuelReportsView();
        $reportsView->setReports(ReportPeer::getReports(array("reportClass"=>Report::CLASSTYPE_FUELPOSITION, "holderId"=>$holderId, "dateFrom"=>$dateFrom, "dateTo"=>$dateTo)));
        $reportsView->setOutputType(FuelReportsView::OUTPUTTYPE_GRAPHIC);
        $reportsView->render();
    }
}

?>