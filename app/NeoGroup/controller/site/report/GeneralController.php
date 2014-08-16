<?php

namespace NeoGroup\controller\site\report;

use NeoGroup\controller\site\SiteController;
use NeoGroup\model\ReportPeer;
use NeoGroup\util\DateUtils;
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
        $reportsView->setReports($this->getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInMapAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new ReportsView();
        $reportsView->setReports($this->getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_MAP);
        $reportsView->render();
    }
    
    private function getReports ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        if ($dateFrom != null)
            $dateFrom = DateUtils::formatDate($dateFrom, "Y-m-d H:i:s", -$this->getSession()->userTimeZone);
        if ($dateTo != null)
            $dateTo = DateUtils::formatDate($dateTo, "Y-m-d H:i:s", -$this->getSession()->userTimeZone);
        return ReportPeer::getReports(array("holderId"=>$holderId, "dateFrom"=>$dateFrom, "dateTo"=>$dateTo));
    }
}

?>