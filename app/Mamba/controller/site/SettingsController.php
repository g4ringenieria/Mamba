<?php

namespace Mamba\controller\site;

use Mamba\controller\site\SiteController;
use Mamba\view\SettingsView;

class SettingsController extends SiteController
{
    public function indexAction ()
    {
        $settingsView = new SettingsView();
        $settingsView->render();
    }
}

?>