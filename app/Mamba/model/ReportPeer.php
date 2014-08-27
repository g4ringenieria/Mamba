<?php

namespace Mamba\model;

use Mamba\model\PositionReport;
use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class ReportPeer extends DatabaseModel
{
    public static function getReports (array $options = array())
    {
        $options = array_merge(array("page"=>1, "pageSize"=>300), $options);
        
        $reports = array();
        $doReport = self::getDataObject("report");
        $doHolder = self::getDataObject("holder");
        $doReportType = self::getDataObject("reporttype");
        $doReport->addJoin($doHolder);
        $doReport->addJoin($doReportType);
        $doReport->addSelectField("report.*");
        $doReport->addSelectFields(array("holderid", "name", "domain"), "holder_%s", "holder");
        $doReport->addSelectFields(array("reporttypeid", "description"), "reporttype_%s", "reporttype");
        if (isset($options["reportClass"]))
        {
            $reportClasses = is_array($options["reportClass"])? $options["reportClass"] : array($options["reportClass"]);
            $doReport->addWhereCondition("report.reportclasstypeid in (" . implode(",", $reportClasses) . ")");
        }
        if (isset($options["holderId"]))
            $doReport->addWhereCondition("report.holderid = " . $options["holderId"]);
        if (isset($options["dateFrom"]))
            $doReport->addWhereCondition("report.date > '" . $options["dateFrom"] . "'");
        if (isset($options["dateTo"]))
            $doReport->addWhereCondition("report.date < '" . $options["dateTo"] . "'");
        $doReport->addOrderByField("report.date", DataObject::ORDERBYTYPE_DESC);
        $doReport->setOffset(($options["page"]-1)*$options["pageSize"]);
        $doReport->setLimit($options["pageSize"]);
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