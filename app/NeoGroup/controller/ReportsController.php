<?php

namespace NeoGroup\controller;

use DateTime;
use NeoGroup\model\Device;
use NeoGroup\model\Holder;
use NeoGroup\model\PositionReport;
use NeoGroup\model\Report;
use NeoGroup\model\ReportType;

class ReportsController extends EntityController
{
    public function createResourceAction ($resource)
    {
        $resource->insert();
    }
    
    protected function convertInputToResource ($content)
    {
        $report = null;
        if (!empty($content))
        {
            $decodedContent = json_decode($content);
            if (isset($decodedContent->reportClassTypeId))
            {
                switch ($decodedContent->reportClassTypeId)
                {
                    case Report::CLASSTYPE_POSITION: $report = new PositionReport(); break;
                }

                if ($report != null)
                {
                    foreach ($decodedContent as $propertyName => $propertyValue)
                    {
                        switch ($propertyName)
                        {
                            case "reportTypeId":
                                $report->setReportType(new ReportType($propertyValue));
                                break;
                            case "deviceId":
                                $report->setDevice(new Device($propertyValue));
                                break;
                            case "holderId":
                                $report->setHolder(new Holder($propertyValue));
                                break;
                            case "date":
                                $date = new DateTime();
                                $date->setTimestamp($propertyValue);
                                $report->setDate($date);
                                break;
                            default: 
                                $report->setPropertyValue($propertyName, $propertyValue);
                                break;
                        }
                    }
                }
            }
        }
        return $report;
    }
}

?>