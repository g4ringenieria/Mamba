<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\model\User;
use NeoGroup\view\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $user = new User($this->getSession()->userId);
        $user->find(true);
        $accountView = new AccountView();
        $accountView->setUser($user);
        $accountView->render();
    }
}

?>