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
        $form = new Form($this, array("method"=>"POST"));
        $form->setColumns(3);
        $form->addField (new DisplayField(str_pad($this->user->getId(), 4, "0", STR_PAD_LEFT)), array("label"=>"Id"));
        $form->addField (new TextField(array("name"=>"firstname", "value"=>$this->user->getFirstname())), array("label"=>"Nombre"));
        $form->addField (new TextField(array("name"=>"lastname", "value"=>$this->user->getLastname())), array("label"=>"Apellido"));
        $form->addField (new DisplayField($this->user->getUsername()), array("label"=>"Nombre de Usuario"));
        $form->addField (new PasswordField(array("name"=>"password", "value"=>$this->user->getPassword())), array("label"=>"Contraseña"));
        $form->addField (new PasswordField(array("name"=>"passwordrepeat", "value"=>$this->user->getPassword())), array("label"=>"Contraseña (rep)"));
        $form->addField (new DisplayField($this->user->getProfile()->getDescription()), array("label"=>"Perfil"));
        $form->addField (new SelectField($this, array("name"=>"languageid", "value"=>$this->user->getLanguage()->getId(), "options"=>Language::findAll())), array("label"=>"Idioma"));
        $form->addField (new SelectField($this, array("name"=>"timezoneid", "value"=>$this->user->getTimeZone()->getId(), "options"=>TimeZone::findAll())), array("label"=>"Zona horaria"));
        $form->add(new Button("Guardar cambios", array("class"=>"primary", "action"=>"saveAccount")));
        $page->add (new Tag("div", array("class"=>"container"), new Panel("Mi Cuenta", $form)));
        
        $this->addScript ('
            $("form").submit(function(event)
            {
                var $firstnameField = $("input[name=firstname]");
                var $lastnameField = $("input[name=lastname]");
                var $passwordField = $("input[name=password]");
                var $passwordrepeatField = $("input[name=passwordrepeat]");

                $firstnameField.clearFieldState();
                $lastnameField.clearFieldState();
                $passwordField.clearFieldState();
                $passwordrepeatField.clearFieldState();

                if ($firstnameField.val() == "")
                {
                    $firstnameField.addFieldError ("El campo es requeridos");
                    event.preventDefault();
                }
                if ($lastnameField.val() == "")
                {
                    $lastnameField.addFieldError ("El campo es requerido");
                    event.preventDefault();
                }
                if ($passwordField.val() == "")
                {
                    $passwordField.addFieldError ("El campo es requerido");
                    event.preventDefault();
                }
                if ($passwordField.val() != $passwordrepeatField.val())
                {
                    $passwordField.addFieldError ("Las contraseñas no coinciden");
                    $passwordrepeatField.addFieldError();
                    event.preventDefault();
                }
            });
        ');
    }
}

?>