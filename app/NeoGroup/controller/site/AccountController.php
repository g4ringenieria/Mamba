<?php

namespace NeoGroup\controller\site;

use Exception;
use NeoGroup\controller\site\SiteController;
use NeoGroup\model\Language;
use NeoGroup\model\TimeZone;
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
    
    public function saveAccountAction ($firstname, $lastname, $password, $passwordrepeat, $languageid, $timezoneid)
    {
        $accountView = new AccountView();
        $accountView->setUser(User::findById($this->getSession()->userId, true));
        try
        {
            $user = new User($this->getSession()->userId);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword($password);
            $user->setLanguage(new Language($languageid));
            $user->setTimeZone(new TimeZone($timezoneid));
            $user->update();
            
            $accountView->setState(AccountView::STATE_SAVESUCCESS);
        }
        catch (Exception $ex)
        {
            $accountView->setState(AccountView::STATE_SAVEFAILURE, $ex->getMessage());
        }
        $accountView->render();
    }
}

?>