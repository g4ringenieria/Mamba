<?php

namespace NeoGroup\controllers\site;

use NeoGroup\controllers\SiteController;
use NeoGroup\views\site\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $view = new AccountView();
        $view->render();
    }
}

?>
