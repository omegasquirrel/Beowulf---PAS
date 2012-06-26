<?php
/** Form for filtering Scheduled Monuments
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SearchSortForm extends Pas_Form
{
public function __construct($options = null) {

	
	parent::__construct($options);
	$this->setName('filtersearch');
	
	$filters = new Zend_Form_Element_Select('filters');
	$filters->setLabel('Sort by: ')
		->addFilters(array('StringTrim', 'StripTags'))
		->addMultiOptions(array(
			NULL => 'Choose sort direction',
			'Available options' => array('objecttype'))) 
		->addValidator('InArray', false, array(array_keys($county_options)));;
	
	$images = new Zend_Form_Element_Checkbox('thumbnail');
	$images->setLabel('With images: ')
		->addFilters(array('StringTrim', 'StripTags'));
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter:');
	
	$this->addElements(array(
	$monumentName, $county, $district,
	$parish, $submit, $hash));
	parent::init();  
	}
}