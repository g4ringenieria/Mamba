<?php

namespace Mamba\view\administration;

use Mamba\model\TimeZone;
use Mamba\view\component\Button;
use Mamba\view\component\Form;
use Mamba\view\component\Panel;
use Mamba\view\component\TextField;
use Mamba\view\SiteView;
use NeoPHP\web\html\Tag;

class TimeZoneFormView extends SiteView
{
    private $timezone;
    
    public function setTimezone (TimeZone $timezone)
    {
        $this->timezone = $timezone;
    }
    
    protected function buildContent($content)
    {
        $content->add (new Tag("div", array("class"=>"container"), new Panel(array("title"=>$this->timezone != null? "Edición de Zone Horaria" : "Creación de Zona Horaria", "content"=>$this->createForm()))));
    }
    
    protected function createForm ()
    {
        $idHiddenField = new Tag("input", array("type"=>"hidden", "name"=>"timezoneid"));
        $descriptionTextField = new TextField(array("name"=>"description"));
        $timezoneTextField = new TextField(array("name"=>"tz"));
        if ($this->timezone != null)
        {
            $idHiddenField->setAttribute("value", $this->timezone->getId());
            $descriptionTextField->setAttribute("value", $this->timezone->getDescription());
            $timezoneTextField->setAttribute("value", $this->timezone->getTimezone());
        }        
        $form = new Form(array("method"=>"post", "action"=>($this->timezone != null)? "updateTimeZone" : "createTimeZone"));
        $form->setType (Form::TYPE_HORIZONTAL);
        $form->add($idHiddenField);
        $form->addField($descriptionTextField, array("label"=>"Descripción"));
        $form->addField($timezoneTextField, array("label"=>"Corrimiento"));
        $form->add(new Button("Guardar", array("type"=>"submit", "class"=>"primary")));
        return $form;
    }
}

?>