<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\model\ContactPeer;
use NeoGroup\model\User;
use NeoGroup\view\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $user = new User($this->getSession()->userId);
        $user->find();
        $user->getClient()->find();
        $user->getProfile()->find();
        $user->getTimeZone()->find();
        $user->getLanguage()->find();
        $user->setContacts(ContactPeer::getContactsForUserId($user->getId()));
        
        $accountView = new AccountView();
        $accountView->setUser($user);
        $accountView->render();
    }
}

?>
