<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $view = $this->createView("site/account");        
        $view->render();
    }
}

?>
