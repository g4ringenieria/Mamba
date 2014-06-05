<?php

namespace NeoGroup\controllers\site;

use NeoPHP\data\DataObject;
use NeoGroup\controllers\SiteController;
use NeoGroup\models\Report;
use NeoGroup\views\site\ReportsView;

class ReportsController extends SiteController
{
    public function indexAction ()
    {
        $this->createView("site/reports")->render();
    }
    
    public function showReportsInTableAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reports = $this->getReports($holderId, $dateFrom, $dateTo);
        $reportsView = $this->createView("site/reports");
        $reportsView->setReports($reports);
        $reportsView->setOutputType(ReportsView::OUTPUTTYPE_GRID);
        $reportsView->render();
    }
    
    public function showReportsInMapAction ($holderId=null, $dateFrom=null, $dateTo=null)
    {
        $reports = $this->getReports($holderId, $dateFrom, $dateTo);
        $reportsView = $this->createView("site/reports");
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
        $doEvent = $database->getDataObject("event");
        $doReport->addJoin($doHolder);
        $doReport->addJoin($doEvent);
        $doReport->addSelectField("report.*");
        $doReport->addSelectFields(array("holderid", "name", "domain"), "holder_%s", "holder");
        $doReport->addSelectFields(array("eventid", "description"), "event_%s", "event");
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
            $report = new Report();
            $report->completeFromFieldsArray($doReport->getFields());
            $reports[] = $report;
        }
        return $reports;
    }
}

?>
