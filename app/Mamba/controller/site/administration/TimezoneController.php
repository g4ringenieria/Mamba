<?php

namespace Mamba\controller\site\administration;

use Mamba\controller\site\SiteController;
use Mamba\model\TimeZone;
use Mamba\view\administration\TimeZoneCRUDView;
use Mamba\view\administration\TimeZoneFormView;

class TimezoneController extends SiteController
{
    public function indexAction ()
    {
        $this->renderTimeZonesCRUDView ();
    }
    
    public function showTimeZoneFormAction ($timezoneid = null)
    {
        $view = new TimeZoneFormView();
        if (!empty($timezoneid))
            $view->setTimezone (TimeZone::findById($timezoneid));
        $view->render();
    }
    
    public function deleteTimeZoneAction ($timezoneid)
    {
        $timezone = new TimeZone($timezoneid);
        $timezone->delete();
        $this->renderTimeZonesCRUDView ();
    }
    
    protected function renderTimeZonesCRUDView ()
    {
        $view = new TimeZoneCRUDView();
        $view->setTimezones(TimeZone::findAll());
        $view->render();
    }
}

?>