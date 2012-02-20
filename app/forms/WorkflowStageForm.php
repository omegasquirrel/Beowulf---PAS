<?php

class WorkflowStageForm extends Pas_Form
{

public function __construct($options = null)
{

	parent::__construct($options);

	$this->setName('workflow');
	$id = new Zend_Form_Element_Hidden('id');
	$id->removeDecorator('label');
	
	$wfstage = new Zend_Form_Element_Radio('wfstage');
	$wfstage->setRequired(false)
	->addMultiOptions(array('1' => 'Quarantine','2' => 'Review','4' => 'Validation','3' => 'Published'))
	->addFilter('StripTags')
	->addFilter('StringTrim');
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton');
	$submit->clearDecorators();
	
    $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
    ));
	
    $this->setLegend('Workflow status');
	
    $this->addDecorator('FormElements')
		 ->addDecorator('Form')
		 ->addDecorator('Fieldset');
	
	
	$this->addElements(array($id,$wfstage,$submit));
    
	parent::init();
	}
}