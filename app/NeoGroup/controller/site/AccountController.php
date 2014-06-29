<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\view\site\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $view = new AccountView();
        $view->render();
    }
}

?>
