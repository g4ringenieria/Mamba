<?php

namespace Mamba\controller\site\administration;

use Mamba\controller\site\SiteController;
use Mamba\model\Line;
use Mamba\model\ServiceProvider;
use Mamba\model\Device;
use Mamba\view\administration\LineCRUDView;
use Mamba\view\administration\LineFormView;

class LineController extends SiteController
{
    public function indexAction ()
    {
        $this->renderLinesCRUDView ();
    }
    
    public function showLineFormAction ($lineid = null)
    {
        $view = new LineFormView();
        if (!empty($lineid))
            $view->setLine (Line::findById($lineid));
        $view->render();
    }
    
    public function createLineAction ( ServiceProvider $serviceProvider, $number, $description, Device $device )
    {
        $line = new Line();
        $line->setServiceProvider($serviceProvider);
        $line->setNumber($number);
        $line->setDescription($description);
        $line->setDevice($device);
        $line->insert();
        $this->renderLinesCRUDView ();
    }
    
    public function updateLineAction ($lineid, ServiceProvider $serviceProvider, $number, $description, Device $device )
    {
        $line = new Line($lineid);
        $line->setServiceProvider($serviceProvider);
        $line->setNumber($number);
        $line->setDescription($description);
        $line->setDevice($device);
        $line->update();
        $this->renderLinesCRUDView ();
    }
    
    public function deleteLineAction ($lineid)
    {
        $line = new Line($lineid);
        $line->delete();
        $this->renderLinesCRUDView ();
    }
    
    protected function renderLinesCRUDView ()
    {
        $view = new LineCRUDView();
        $view->setLines(Line::findAll(true));
        $view->render();
    }
}

?>