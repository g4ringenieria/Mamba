<?php

namespace NeoGroup\controllers\site;

use NeoPHP\data\DataObject;
use NeoGroup\controllers\SiteController;
use NeoGroup\models\Holder;

class DashboardController extends SiteController
{
    public function indexAction ()
    {
        $holders = $this->getHolders();
        $dashboardView = $this->createView("site/dashboard");        
        $dashboardView->setHolders($holders);
        $dashboardView->render();
    }
    
    private function getHolders ()
    {
        $database = $this->getApplication()->getDefaultDatabase();
        $doHolder = $database->getDataObject("holder");
        $doHolder->addSelectField("holder.*");
        $doClientHolder = $database->getDataObject ("clientholder");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addSelectField("clientholder.owner", "ownerid");
        $doHolder->addWhereCondition ("clientholder.clientid = " . $this->getSession()->clientId);
        $doReport = $database->getDataObject ("report");
        $doHolder->addSelectFields(array("reportid", "deviceid", "longitude", "latitude", "date", "inputDate", "course", "speed", "location", "odometer"), "holderstatus_report_%s", "report");
        $doEvent = $database->getDataObject ("event");
        $doReport->addJoin ($doEvent, DataObject::JOINTYPE_LEFT);
        $doHolder->addSelectFields(array("eventid", "description"), "holderstatus_report_event_%s", "event");
        $doHolderStatus = $database->getDataObject ("holderstatus");
        $doHolderStatus->addJoin ($doReport, DataObject::JOINTYPE_LEFT);
        $doHolder->addJoin ($doHolderStatus, DataObject::JOINTYPE_LEFT, "holderid");
        $doHolder->find();
        $holders = array();
        while ($doHolder->fetch())
        {
            $holder = new Holder();
            $holder->completeFromFieldsArray($doHolder->getFields());
            $holders[] = $holder;
        }
        return $holders;
    }
}

?>
