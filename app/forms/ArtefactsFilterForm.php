<?php
/** Form for filtering finds
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Will need changing for the solr version
*/

class ArtefactsFilterForm extends Pas_Form {

public function __construct($options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();

	$counties = new Counties();
	$county_options = $counties->getCountyName2();

    parent::__construct($options);

 	$this->setMethod('get');
	$this->setName('filterfinds');

	$decorator =  array('TableDecInput');

	$objectType = new Zend_Form_Element_Select('objectType');
	$objectType->setLabel('Filter by object type')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Alpha', false, array('allowWhiteSpace' => true))
	->addErrorMessage('Come on it\'s not that hard, enter a title!');



	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Filter by broadperiod')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('stringLength', false, array(1,200));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('stringLength', false, array(1,200));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter:');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array(
	$objectType, $broadperiod,
	$county, $submit));
        
	parent::init();
	}

}