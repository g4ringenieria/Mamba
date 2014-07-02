<?php

namespace NeoGroup\view;

class AccountView extends SiteView
{
    private $user;
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    protected function getPageTitle ()
    {
        return "Mi Cuenta";
    }
    
    protected function buildPage($page) 
    {
        $page->add ($this->createUserWidget());
    }
    
    protected function createUserWidget() 
    {
        $result = "Nombre: " . $this->user->getFirstname() . " Apellido: " . $this->user->getLastname() . "<br>";
        $result .= "Cliente: " . $this->user->getClient()->getId() . " - " . $this->user->getClient()->getName() . "<br>";
        $result .= "Perfil: " . $this->user->getProfile()->getDescription() . "<br>";   
        $result .= "Zona Horaria: " . $this->user->getTimeZone()->getDescription() . "<br>";
        foreach ( $this->user->getContacts() as $contact ) {
            $result .=  $contact->getContactType()->getDescription() . ": ". $contact->getValue() . "<br>";
        }
        return $result;
    }
}

?>