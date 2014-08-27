<?php

namespace Mamba\model;

use NeoPHP\data\DataObject;
use NeoPHP\mvc\DatabaseModel;

class ToolPeer extends DatabaseModel
{
    public static function getToolsForProfileId ($profileId)
    {
        $tools = array();
        $doTool = self::getDataObject ("tool");
        $doProfileTool = self::getDataObject ("profiletool");
        $doTool->addJoin ($doProfileTool, DataObject::JOINTYPE_INNER, "toolid");
        $doTool->addWhereCondition("profileid = " . $profileId);
        $doTool->addOrderByField("tool.toolid", DataObject::ORDERBYTYPE_ASC);
        $doTool->find();
        while ($doTool->fetch ()) 
        {
            $tool = new Tool();
            $tool->setFieldValues($doTool->getFields());
            $tools[] = $tool;
        }
        return $tools;
    }
}

?>