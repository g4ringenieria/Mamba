<?php

namespace Mamba\view\administration;

use Mamba\model\TimeZone;
use Mamba\view\SiteView;

class TimeZoneFormView extends SiteView
{
    private $timezone;
    
    public function setTimezone (TimeZone $timezone)
    {
        $this->timezone = $timezone;
    }
    
    protected function buildContent($content)
    {
        
    }
}

?>