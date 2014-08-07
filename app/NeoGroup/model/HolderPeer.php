<?php

namespace NeoGroup\model;

use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class HolderPeer extends DatabaseModel
{
    public static function getHolders ($clientId, $query=null)
    {
        $holders = array();
        $doHolder = self::getDataObject("holder");
        $doHolder->addSelectField("holder.*");
        $doClientHolder = self::getDataObject ("clientholder");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addWhereCondition ("clientholder.clientid = " . $clientId);
        if (isset($query))
        {
            $conditions = array();
            if (is_numeric($query))
                $conditions[] = "holder.holderid = " . $query;
            $conditions[] = "holder.name ilike '%" . $query . "%'";
            $conditions[] = "holder.domain ilike '%" . $query . "%'";
            $doHolder->addWhereCondition ("(" . implode(" OR ", $conditions) . ")");
        }
        $doHolder->find();
        while ($doHolder->fetch())
        {
            $holder = new Holder();
            $holder->setFieldValues($doHolder->getFields());
            $holders[] = $holder;
        }
        return $holders;
    }
    
    public static function getHoldersWithLastReport ($clientId)
    {
        $holders = array();
        $doHolder = self::getDataObject("holder");
        $doDevice = self::getDataObject("device");
        $doClientHolder = self::getDataObject ("clientholder");
        $doLastReport = self::getDataObject ("lastreport");
        $doReportType = self::getDataObject ("reporttype");
        $doHolder->addJoin ($doDevice, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doLastReport->addJoin ($doReportType, DataObject::JOINTYPE_INNER);
        $doHolder->addJoin ($doLastReport, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addSelectField("holder.*");
        $doHolder->addSelectField("device.deviceid", "deviceid");
        $doHolder->addSelectField("clientholder.owner", "ownerid");
        $doHolder->addSelectFields(array("reportid", "reportclasstypeid", "deviceid", "date", "inputdate", "data"), "%s", "lastreport");
        $doHolder->addSelectFields(array("reporttypeid", "description"), "reporttype_%s", "reporttype");
        $doHolder->addWhereCondition ("clientholder.clientid = " . $clientId);
        $doHolder->addWhereCondition ("holder.active = true");
        $doHolder->find();
        while ($doHolder->fetch())
        {
            $holder = new Holder();
            $holder->setFieldValues($doHolder->getFields());
            switch ( $doHolder->reportclasstypeid ) 
            {
                case Report::CLASSTYPE_POSITION:
                    $lastReport = new PositionReport();
                    break;
                case Report::CLASSTYPE_FUELPOSITION:
                    $lastReport = new FuelPositionReport();
                    break;
            }
            
            $lastReport->setFieldValues($doHolder->getFields());
            $holder->setLastReport($lastReport);
            $holders[] = $holder;
        }
        return $holders;
    }
}

?>