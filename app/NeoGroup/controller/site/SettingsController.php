<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\site\SiteController;
use NeoGroup\view\SettingsView;

class SettingsController extends SiteController
{
    public function indexAction ()
    {
        $settingsView = new SettingsView();
        $settingsView->render();
    }
}

?>