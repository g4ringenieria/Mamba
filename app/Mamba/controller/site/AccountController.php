<?php

namespace Mamba\controller\site;

use Exception;
use Mamba\controller\site\SiteController;
use Mamba\model\Language;
use Mamba\model\TimeZone;
use Mamba\model\User;
use Mamba\view\AccountView;

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
        $accountView->setUser(User::findById($this->getSession()->userId, true));
        $accountView->render();
    }
}

?>