<?php

namespace NeoGroup\view;

use NeoGroup\model\Language;
use NeoGroup\model\Profile;
use NeoGroup\model\TimeZone;
use NeoGroup\model\User;
use NeoGroup\view\component\DisplayField;
use NeoGroup\view\component\Form;
use NeoGroup\view\component\Panel;
use NeoGroup\view\component\PasswordField;
use NeoGroup\view\component\SelectField;
use NeoGroup\view\component\TextField;
use NeoPHP\web\html\Tag;

class AccountView extends SiteView
{
    private $user;
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    protected function buildContent($page) 
    {   
        $form = new Form();
        $form->setColumns(3);
        $form->addField (new DisplayField(str_pad($this->user->getId(), 4, "0", STR_PAD_LEFT)), "Id");
        $form->addField (new TextField(array("value"=>$this->user->getFirstname())), "Nombre");
        $form->addField (new TextField(array("value"=>$this->user->getLastname())), "Apellido");
        $form->addField (new DisplayField($this->user->getUsername()), "Nombre de Usuario");        
        $form->addField (new PasswordField(array("value"=>$this->user->getPassword())), "Contraseña");
        $form->addField (new PasswordField(array("value"=>$this->user->getPassword())), "Contraseña (rep)");
        $form->addField (new DisplayField($this->user->getProfile()->getDescription()), "Perfil");
        $form->addField (new SelectField($this, array("value"=>$this->user->getLanguage()->getId(), "displayvalue"=>$this->user->getLanguage()->getDescription(), "options"=>Language::findAll())), "Idioma");
        $form->addField (new SelectField($this, array("value"=>$this->user->getTimeZone()->getId(), "displayvalue"=>$this->user->getTimeZone()->getDescription(), "options"=>TimeZone::findAll())), "Zona horaria");
        $page->add (new Tag("div", array("class"=>"container"), new Panel("Mi Cuenta", $form)));
    }
}

?>