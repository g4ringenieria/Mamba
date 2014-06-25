<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;
use NeoGroup\models\PositionReport;
use NeoGroup\views\site\ReportsView;
use NeoPHP\data\DataObject;

class ReportsController extends SiteController
{
    public function indexAction ()
    {
        $reportsView = new ReportsView();
        $reportsView->render();
    }
    
    public function showReportsInTableAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reports = $this->getReports($holderId, $dateFrom, $dateTo);
        $reportsView = new ReportsView();
        $reportsView->setReports($reports);
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInMapAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reports = $this->getReports($holderId, $dateFrom, $dateTo);
        $reportsView = new ReportsView();
        $reportsView->setReports($reports);
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_MAP);
        $reportsView->render();
    }
    
    private function getReports ($holderId=null, $dateFrom=null, $dateTo=null, $page=1, $pageSize=300)
    {
        $reports = array();
        $database = $this->getApplication()->getDefaultDatabase();
        $doReport = $database->getDataObject("report");
        $doHolder = $database->getDataObject("holder");
        $doReportType = $database->getDataObject("reporttype");
        $doReport->addJoin($doHolder);
        $doReport->addJoin($doReportType);
        $doReport->addSelectField("report.*");
        $doReport->addSelectFields(array("holderid", "name", "domain"), "holder_%s", "holder");
        $doReport->addSelectFields(array("reporttypeid", "description"), "reporttype_%s", "reporttype");
        if (isset($holderId))
            $doReport->addWhereCondition("report.holderid = " . $holderId);
        if (isset($dateFrom))
            $doReport->addWhereCondition("report.date > '" . $dateFrom . "'");
        if (isset($dateTo))
            $doReport->addWhereCondition("report.date < '" . $dateTo . "'");
        $doReport->addOrderByField("report.date", DataObject::ORDERBYTYPE_DESC);
        $doReport->setOffset(($page-1)*$pageSize);
        $doReport->setLimit($pageSize);
        $doReport->find();
        while ($doReport->fetch())
        { 
            $report = new PositionReport();
            $report->completeFromFieldsArray($doReport->getFields());
            $reports[] = $report;
        }

        return $reports;
    }
}

?>
