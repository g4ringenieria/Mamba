<?php

namespace NeoGroup\model;

use NeoGroup\model\PositionReport;
use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class ReportPeer extends DatabaseModel
{
    public static function getReports ($holderId=null, $dateFrom=null, $dateTo=null, $page=1, $pageSize=300)
    {
        $reports = array();
        $doReport = self::getDataObject("report");
        $doHolder = self::getDataObject("holder");
        $doReportType = self::getDataObject("reporttype");
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
            $report = null;
            switch ($doReport->reportclasstypeid) 
            {
                case Report::CLASSTYPE_POSITION:
                    $report = new PositionReport();
                    break;
                case Report::CLASSTYPE_DATAPOSITION:
                    $report = new DataPositionReport();
                    break;
                case Report::CLASSTYPE_FUELPOSITION:
                    $report = new FuelPositionReport();
                    break;
            }
            if ($report != null)
            {
                $report->setFieldValues($doReport->getFields());
                $reports[] = $report;
            }
        }
        return $reports;
    }
}

?>