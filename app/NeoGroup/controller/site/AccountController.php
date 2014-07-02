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
        
        $client = new Client();
        $client->setId($user->getClient()->getId());
        $client->find();
        $user->setClient($client);
        
        $profile = new Profile();
        $profile->setId($user->getProfile()->getId());
        $profile->find();
        $user->setProfile($profile);
        
        $timezone = new TimeZone();
        $timezone->setId($user->getTimeZone()->getId());
        $timezone->find();
        $user->setTimeZone($timezone);
        
        $language = new Language();
        $language->setId($user->getLanguage()->getId());
        $language->find();
        $user->setLanguage($language);
        
        $contactPeer = new ContactPeer();
        $contacts = $contactPeer->getContactsForUserId($user->getTimeZone()->getId());
        $user->setContacts($contacts);
        
        $accountView = new AccountView();
        $accountView->setUser($user);
        $accountView->render();
    }
}

?>
