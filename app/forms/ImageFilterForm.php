<?php

/** Form for filtering images
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ImageFilterForm extends Pas_Form {

public function __construct($options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFrom();

	$counties = new Counties();
	$county_options = $counties->getCountyName2();

	parent::__construct($options);


	$this->setName('filterusers');



	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Filter by ID #: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size', 11)
	->addValidator('StringLength', false, array(1,200));


	$label = new Zend_Form_Element_Text('label');
	$label->setLabel('Filter by image label:')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Come on it\'s not that hard, enter a title!')
	->setAttrib('size', 25);


	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Filter by broadperiod: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
	->addValidator('InArray', false, array(array_keys($periodword_options)));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('StringLength', false, array(1,200))
	->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
	->addValidator('InArray', false, array(array_keys($county_options)));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');

	$this->addElements(array(
	$old_findID, $label,$broadperiod,
	$county, $submit));
	parent::init();
	}
}