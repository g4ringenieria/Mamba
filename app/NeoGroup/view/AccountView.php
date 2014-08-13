<?php

namespace NeoGroup\view;

use NeoGroup\model\Language;
use NeoGroup\model\TimeZone;
use NeoGroup\model\User;
use NeoGroup\view\component\Button;
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
        $form->addField (new TextField(array("name"=>"firstname", "value"=>$this->user->getFirstname())), "Nombre");
        $form->addField (new TextField(array("name"=>"lastname", "value"=>$this->user->getLastname())), "Apellido");
        $form->addField (new DisplayField($this->user->getUsername()), "Nombre de Usuario");        
        $form->addField (new PasswordField(array("name"=>"password", "value"=>$this->user->getPassword())), "Contraseña");
        $form->addField (new PasswordField(array("value"=>$this->user->getPassword())), "Contraseña (rep)");
        $form->addField (new DisplayField($this->user->getProfile()->getDescription()), "Perfil");
        $form->addField (new SelectField($this, array("name"=>"languageid", "value"=>$this->user->getLanguage()->getId(), "options"=>Language::findAll())), "Idioma");
        $form->addField (new SelectField($this, array("name"=>"timezoneid", "value"=>$this->user->getTimeZone()->getId(), "options"=>TimeZone::findAll())), "Zona horaria");
        $form->add(new Button("Guardar cambios", array("class"=>"primary", "action"=>"saveAccount")));
        $page->add (new Tag("div", array("class"=>"container"), new Panel("Mi Cuenta", $form)));
    }
}

?>