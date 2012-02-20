<?php
/** Form for basic what where when search
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Replace functions with solr when ready
*/

class WhatWhereWhenForm extends Pas_Form {


	public function __construct($options = null) {
	$periods = new Periods();
	$period_options = $periods->getPeriodFromWords();
	
	$counties = new Counties();
	$counties_options = $counties->getCountyname2();

	parent::__construct($options);


	$this->setName('whatwherewhen');
	
	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a valid string!');

	//Objecttype - autocomplete from thesaurus
	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('What: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a valid string!');

	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('When: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose period from' => $period_options))
		->addValidator('InArray', false, array($period_options));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Where: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $counties_options))
		->addValidator('InArray', false, array($counties_options));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!')
	->setAttribs(array('class'=> 'large'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$this->addElements(array(
	$old_findID, $objecttype, $county,
	$broadperiod, $submit));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'old_findID', 'objecttype', 'broadperiod',
	'county','submit'), 'Search');
	$this->Search->removeDecorator('DtDdWrapper');
	$this->Search->removeDecorator('HtmlTag');
	$this->Search->addDecorators(array(array('HtmlTag', array('tag' => 'ul','id' => 'www'))
	))->setLegend('What/Where/When search')
	->addDecorator('FieldSet');
	
	parent::init();
	}
}
