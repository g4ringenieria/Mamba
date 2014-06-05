<?php

namespace NeoGroup\models;

use NeoPHP\mvc\Model;

/**
 * @Table (tableName="holderstatus")
 */
class HolderStatus extends Model
{
    /**
     * @Column (columnName="lastReportId", relatedTableName="report")
     */
    private $lastReport;

    public function getLastReport ()
    {
        return $this->lastReport;
    }

    public function setLastReport (Report $lastReport)
    {
        $this->lastReport = $lastReport;
    }
}

