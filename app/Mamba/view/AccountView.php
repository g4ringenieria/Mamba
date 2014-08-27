<?php

namespace Mamba\view;

use Mamba\model\Language;
use Mamba\model\TimeZone;
use Mamba\model\User;
use Mamba\view\component\Alert;
use Mamba\view\component\Button;
use Mamba\view\component\DisplayField;
use Mamba\view\component\Form;
use Mamba\view\component\Panel;
use Mamba\view\component\PasswordField;
use Mamba\view\component\SelectField;
use Mamba\view\component\TextField;
use NeoPHP\web\html\Tag;

class AccountView extends SiteView
{
    const STATE_DEFAULT = 0;
    const STATE_SAVESUCCESS = 1;
    const STATE_SAVEFAILURE = 2;
    
    private $user;
    private $state = self::STATE_DEFAULT;
    private $stateMessage = "";
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function setState($state, $stateMessage = "")
    {
        $this->state = $state;
        $this->stateMessage = $stateMessage;
    }
    
    protected function buildContent($page) 
    {   
        $form = new Form(array("method"=>"POST"));
        $form->setColumns(3);
        switch ($this->state)
        {
            case self::STATE_SAVESUCCESS:
                $form->add (new Alert ("Datos de la cuenta guardados correctamente !!", array("type"=>"success", "dismissable"=>true)));
                break;
            case self::STATE_SAVEFAILURE:
                $form->add (new Alert ("Fallo al guardar los datos de la cuenta: " . $this->stateMessage, array("type"=>"danger", "dismissable"=>true)));
                break;
        }
        $form->addField (new DisplayField(str_pad($this->user->getClient()->getId(), 4, "0", STR_PAD_LEFT)), array("label"=>"Cliente Id"));
        $form->addField (new DisplayField($this->user->getClient()->getName()), array("label"=>"Cliente Nombre"));
        $form->addField (new DisplayField(""), array("label"=>""));
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
        $page->add (new Tag("div", array("class"=>"container"), new Panel(array("title"=>"Mi Cuenta", "content"=>$form))));
        
        $this->addScript ('
            $("form").submit(function(event)
            {
                $(".has-error").removeClass("has-error");
                $(".help-block").addClass("hidden").html("");
                
                var $firstnameField = $("input[name=firstname]");
                if ($firstnameField.val() == "")
                {
                    $firstnameField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("El campo es requerido<br>");
                    event.preventDefault();
                }
                
                var $lastnameField = $("input[name=lastname]");
                if ($lastnameField.val() == "")
                {
                    $lastnameField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("El campo es requerido<br>");
                    event.preventDefault();
                }
                
                var $passwordField = $("input[name=password]");
                var $passwordrepeatField = $("input[name=passwordrepeat]");
                if ($passwordField.val() == "")
                {
                    $passwordField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("El campo es requerido<br>");
                    event.preventDefault();
                }
                if ($passwordField.val() != $passwordrepeatField.val())
                {
                    $passwordField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("Las contraseñas deben coincidir<br>");
                    $passwordrepeatField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("Las contraseñas deben coincidir<br>");
                    event.preventDefault();
                }
                if ($passwordField.val().length < 8)
                {
                    $passwordField.closest(".form-group").addClass("has-error").find(".help-block").removeClass("hidden").append("La contraseña debe tener un minimo de 8 caracteres<br>");
                    event.preventDefault();
                }
            });
        ');
    }
}

?>