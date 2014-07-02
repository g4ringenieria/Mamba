<?php

namespace NeoGroup\controller\site;

use NeoGroup\controller\SiteController;
use NeoGroup\model\User;
use NeoGroup\model\Client;
use NeoGroup\model\Profile;
use NeoGroup\model\TimeZone;
use NeoGroup\model\Language;
use NeoGroup\model\ContactPeer;
use NeoGroup\view\AccountView;

class AccountController extends SiteController
{
    public function indexAction ()
    {
        $user = new User();
        $user->setId($_SESSION['userId']);
        $user->find();
        $user->getClient()->find();
        $user->getProfile()->find();
        $user->getTimeZone()->find();
        $user->getLanguage()->find();
        $user->setContacts(ContactPeer::getContactsForUserId($user->getTimeZone()->getId()));
        
        $accountView = new AccountView();
        $accountView->setUser($user);
        $accountView->render();
    }
}

?>
