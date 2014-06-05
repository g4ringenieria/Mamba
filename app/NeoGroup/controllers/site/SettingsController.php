<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;

class SettingsController extends SiteController
{
    public function indexAction ()
    {
        $view = $this->createView("site/settings");        
        $view->render();
    }
}

?>