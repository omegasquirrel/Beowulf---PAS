<?php
/** Form for submitting an error
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class NotifyFloForm extends Pas_Form {

	public function __construct($options = null) {
	
	$f = new Contacts();
	$flos = $f->getFloEmailsForForm();	
		
	parent::__construct($options);

	$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
	$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
	$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
	$decorator =  array('SimpleInput');
	$decoratorSelect =  array('SelectInput');
	$decorators = array(
	            array('ViewHelper'), 
	    		array('Description', array('tag' => '','placement' => 'append')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
	            array('HtmlTag', array('tag' => 'li')),
			    );

	$this->setName('notifyFlo');
	
	$flo = new Zend_Form_Element_Select('flo');
	$flo->setLabel('Which flo is yours?: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addMultiOptions(array(null => 'Choose your FLO','Available FLOs' => $flos));
	
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Message type: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addMultiOptions(array(
	NULL => 'Choose reason',
	'Choose error type' => array(
	'Can you publish this please?' => 'Can you publish this please?',
	'More info' => 'I have further information',
	'Image problem' => 'Image problem',
	'Grid reference issues' => 'Grid reference issues',
	'Duplicated record' => 'Duplicated record',
	'Data problems apparent' => 'Data problems - what do I do?',
	'Other' => 'Other reason')))
	->addErrorMessage('You must enter an error report type');



	$content = new Pas_Form_Element_RTE('content');
	$content->setLabel('Enter your comment: ')
	->setRequired(true)
	->addFilter('StringTrim')
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim','WordChars','HtmlBody','EmptyParagraph'))
	->addErrorMessage('Please enter something in the comments box!');

						
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('HtmlTag')
	->setLabel('Send message')
	->setAttrib('class','large')
	->removeDecorator('DtDdWrapper');


	$this->addElements(array($content, $flo, $type, $submit, $hash));

	$this->addDisplayGroup(array('flo','type', 'content', ), 'details');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your error report: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}