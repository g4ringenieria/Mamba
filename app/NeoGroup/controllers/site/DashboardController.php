<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;
use NeoGroup\models\Holder;
use NeoGroup\models\PositionReport;
use NeoGroup\models\Report;
use NeoGroup\views\site\DashboardView;
use NeoPHP\data\DataObject;

class DashboardController extends SiteController
{
    public function indexAction ()
    {
        $holders = $this->getHolders();
        $dashboardView = new DashboardView();
        $dashboardView->setHolders($holders);
        $dashboardView->render();
    }
    
    private function getHolders ()
    {
        $holders = array();
        
        $database = $this->getApplication()->getDefaultDatabase();
        $doHolder = $database->getDataObject("holder");
        $doDevice = $database->getDataObject("device");
        $doClientHolder = $database->getDataObject ("clientholder");
        $doLastReport = $database->getDataObject ("lastreport");
        $doReportType = $database->getDataObject ("reporttype");
        $doHolder->addJoin ($doDevice, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doLastReport->addJoin ($doReportType, DataObject::JOINTYPE_LEFT);
        $doHolder->addJoin ($doLastReport, DataObject::JOINTYPE_LEFT, "holderid");
        $doHolder->addSelectField("holder.*");
        $doHolder->addSelectField("device.deviceid", "deviceid");
        $doHolder->addSelectField("clientholder.owner", "ownerid");
        $doHolder->addSelectFields(array("reportid", "reportclasstypeid", "deviceid", "date", "inputdate", "data"), "%s", "lastreport");
        $doHolder->addSelectFields(array("reporttypeid", "description"), "reporttype_%s", "reporttype");
        $doHolder->addWhereCondition ("clientholder.clientid = " . $this->getSession()->clientId);
        $doHolder->addWhereCondition ("holder.active = true");
        $doHolder->find();
        while ($doHolder->fetch())
        {
            $holder = new Holder();
            $holder->completeFromFieldsArray($doHolder->getFields());
            
            switch ( $doHolder->reportclasstypeid ) 
            {
                case Report::CLASSTYPE_POSITION:
                    $lastReport = new PositionReport();
                    break;
            }
            
            $lastReport->completeFromFieldsArray($doHolder->getFields());
            $holder->setLastReport($lastReport);
            $holders[] = $holder;
        }
        
        return $holders;
    }
}

?>
