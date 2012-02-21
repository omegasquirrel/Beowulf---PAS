<?php

/** Form for filtering user names in the admin interfaces
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class UserFilterForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setMethod('post');  
	
	$this->setName('filterusers');
	

	$username = new Zend_Form_Element_Text('username');
	$username->setLabel('Filter by username')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 15);

	$name = new Zend_Form_Element_Text('fullname');
	$name->setLabel('Filter by name')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20);

	$role = new Zend_Form_Element_Select('role');
	$role->setLabel('Filter by role')
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addMultiOptions(array(NULL => NULL,'Choose role' => array(
		'admin' => 'Admin', 'hero' => 'HER officer', 'flos' => 'Finds Liaison',
		'member' => 'Member', 'fa' => 'Finds Adviser', 'research' => 'Researcher')));

	$login = new ZendX_JQuery_Form_Element_DatePicker('lastLogin');
	$login->setLabel('Filter last login: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20);

	$visits = new Zend_Form_Element_Text('visits');
	$visits->setLabel('Filter by visit count: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttribs(array('size' => 5, 'maxlength' => '6'))
		->addValidator('Int');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	$this->addElements(array(
	$username, $name, $role,
	$login, $visits, $submit,
	$hash)
	);
	 
	parent::init(); 
	}
}