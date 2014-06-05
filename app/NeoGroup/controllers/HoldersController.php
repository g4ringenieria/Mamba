<?php

namespace NeoGroup\controllers;

use NeoPHP\data\DataObject;
use NeoGroup\models\Holder;

class HoldersController extends EntityController
{
    public function getResourceAction ($id=null, $query=null)
    {
        $holders = array();
        $database = $this->getApplication()->getDefaultDatabase();
        $doHolder = $database->getDataObject("holder");
        $doHolder->addSelectField("holder.*");
        $doClientHolder = $database->getDataObject ("clientholder");
        $doHolder->addJoin ($doClientHolder, DataObject::JOINTYPE_INNER, "holderid");
        $doHolder->addWhereCondition ("clientholder.clientid = " . $this->getSession()->clientId);
        if (isset($id))
        {
            $doHolder->addWhereCondition ("holder.holderid = " . $id);
        }
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
            $holder->completeFromFieldsArray($doHolder->getFields());
            $holders[] = $holder;
        }
        return $holders;
    }
}

?>
