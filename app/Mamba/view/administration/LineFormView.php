<?php

namespace Mamba\view\administration;

use Mamba\model\Line;
use Mamba\model\ServiceProvider;
use Mamba\component\Button;
use Mamba\component\Form;
use Mamba\component\Panel;
use Mamba\component\TextField;
use Mamba\component\SelectField;
use Mamba\view\SiteView;
use NeoPHP\web\html\Tag;
use NeoPHP\web\http\Parameters;

class LineFormView extends SiteView
{
    private $line;
    
    public function setLine (Line $line)
    {
        $this->line = $line;
    }
    
    protected function buildContent($content)
    {
        $content->add (new Tag("div", array("class"=>"container"), new Panel(array("title"=>$this->line != null? "Edición de Linea" : "Creación de Linea", "content"=>$this->createForm()))));
    }
    
    protected function createForm ()
    {
        $idHiddenField = new Tag("input", array("type"=>"hidden", "name"=>"lineid"));
        $serviceProviderField = new SelectField($this, array("name"=>"serviceprovider", "options"=>ServiceProvider::findAll()));
        $numberTextField = new TextField(array("name"=>"number"));
        $descriptionTextField = new TextField(array("name"=>"description"));
        
        $parameters = Parameters::getInstance();
        $deviceSelector = new SelectField($this, array("placeholder"=>"Equipo", "name"=>"deviceId", "value"=>$parameters->deviceId, "displayvalue"=>$parameters->device_text));
        $deviceSelector->setSourceType(SelectField::SOURCETYPE_REMOTE);
        $deviceSelector->setRemoteUrl($this->getBaseUrl() . "devices/?returnFormat=json&" . session_name() . "=" . session_id());
        $deviceSelector->setDisplayTemplate("{id}: {holder_domain}");
        
        if ($this->line != null)
        {
            $idHiddenField->setAttribute("value", $this->line->getId());
            $serviceProviderField->setValueField($this->line->getServiceProvider()->getId());
            $numberTextField->setAttribute("value", $this->line->getNumber());
            $descriptionTextField->setAttribute("value", $this->line->getDescription());
            $deviceSelector->setValueField($this->line->getDevice()->getId());
        }
        
        $form = new Form(array("method"=>"post", "action"=>($this->line != null)? "updateLine" : "createLine"));
        $form->setType (Form::TYPE_HORIZONTAL);
        $form->add($idHiddenField);
        $form->addField($serviceProviderField, array("label"=>"Prestadora"));
        $form->addField($numberTextField, array("label"=>"Número"));
        $form->addField($descriptionTextField, array("label"=>"Descripción"));
        $form->addField($deviceSelector, array("label"=>"Equipo"));
        $form->add(new Tag("div", array("class"=>"centeredcontent"), new Button("Guardar", array("type"=>"submit", "class"=>"primary"))));
        return $form;
    }
}

?>