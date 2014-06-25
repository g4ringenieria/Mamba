<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;
use NeoGroup\views\site\SettingsView;

class SettingsController extends SiteController
{
    public function indexAction ()
    {
        $settingsView = new SettingsView();
        $settingsView->render();
    }
}

?>