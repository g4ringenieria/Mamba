<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\model\User;
use NeoGroup\view\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $accountView = new AccountView();
        $accountView->setUser(User::findById($this->getSession()->userId, true));
        $accountView->render();
    }
}

?>