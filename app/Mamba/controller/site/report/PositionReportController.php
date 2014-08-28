<?php

namespace Mamba\controller\site\report;

use Mamba\controller\site\SiteController;
use Mamba\model\ReportPeer;
use Mamba\util\DateUtils;
use Mamba\view\report\PositionReportsView;

class PositionReportController extends SiteController
{
    public function indexAction ()
    {
        $reportsView = new PositionReportsView();
        $reportsView->render();
    }
    
    public function showReportsInTableAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new PositionReportsView();
        $reportsView->setReports($this->getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(PositionReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInMapAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reportsView = new PositionReportsView();
        $reportsView->setReports($this->getReports($holderId, $dateFrom, $dateTo));
        $reportsView->setOutputType(PositionReportsView::OUTPUTTYPE_MAP);
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