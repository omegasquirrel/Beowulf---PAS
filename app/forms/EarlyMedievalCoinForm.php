<?php
/** Form for entering data about Early Medieval coins
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoinForm extends Pas_Form {

	public function __construct($options = null) {
	// Construct the select menu data

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();

	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new Dieaxes();
	$die_options = $dies->getAxes();

	$wears = new Weartypes();
	$wear_options = $wears->getWears();

	$rulers = new Rulers();
	$ro = $rulers->getEarlyMedRulers();

	$mints = new Mints();
	$mo = $mints->getEarlyMedievalMints();

	parent::__construct($options);

	$this->setName('earlymedievalcoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->addValidators(array('NotEmpty'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination' => $denomination_options));

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->addValidator('Int');

	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Category of coin: ')
	->addValidators(array('NotEmpty'))
	->addMultiOptions(array(NULL => NULL,'Choose category' => $cat_options))
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int');

	$ruler_id= new Zend_Form_Element_Select('ruler');
	$ruler_id->setLabel('Ruler: ')
	->setRegisterInArrayValidator(false)
	->addMultiOptions(array(NULL => NULL,'Please choose a ruler' => $ro))
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->setOptions(array('separator' => ''));

	$mint_id = new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Please choose a mint','Available mints' => $mo));


	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
	->setRegisterInArrayValidator(false)
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Choose coin status','Available status' => $status_options));

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->setOptions(array('separator' => ''));


	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
	->setRegisterInArrayValidator(false)
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options))
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int');

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
	->setAttrib('size',60)
	->addFilters(array('StripTags','StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
	->setAttrib('size',60)
	->addFilters(array('StripTags','StringTrim'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','EmptyParagraph','StringTrim'))
	->setAttrib('class','expanding')
	->addFilters(array('StripTags','StringTrim'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','EmptyParagraph','StringTrim'))
	->setAttrib('class','expanding');

	$rev_mm = new Zend_Form_Element_Textarea('reverse_mintmark');
	$rev_mm->setLabel('Reverse mintmark: ')
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','EmptyParagraph','StringTrim'))
	->setAttrib('class','expanding');

	$initial = new Zend_Form_Element_Textarea('initial_mark');
	$initial->setLabel('Initial mark: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','EmptyParagraph','StringTrim'))
	->setAttrib('class','expanding');

	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $die_options));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->addValidator('Int');

	$typeID = new Zend_Form_Element_Select('typeID');
	$typeID->setLabel('Coin type: ')
	->setRegisterInArrayValidator(false)
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array(
            $ruler_id, $ruler_qualifier, $denomination,
            $denomination_qualifier, $mint_id, $typeID,
            $status, $categoryID, $status_qualifier,
            $degree_of_wear, $obverse_description, $obverse_inscription,
            $reverse_description, $reverse_inscription, $die_axis_measurement,
            $die_axis_certainty, $submit, $rev_mm,
            $initial));

	$this->addDisplayGroup(array(
            'categoryID', 'ruler', 'typeID',
            'ruler_qualifier', 'denomination', 'denomination_qualifier',
            'mint_id', 'status', 'status_qualifier',
            'degree_of_wear', 'obverse_description', 'obverse_inscription',
            'reverse_description', 'reverse_inscription', 'reverse_mintmark',
            'initial_mark', 'die_axis_measurement', 'die_axis_certainty') ,
                'details');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->addDisplayGroup(array('submit'),'submit');
	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

	if($action === 'editcoin') {
		$rulers = new Rulers();
		$ruler_options = $rulers->getEarlyMedRulers();

		$ruler_id->addMultiOptions(array(
                    NULL => 'Choose ruler',
                    'Available rulers' => $ruler_options));

                $mints = new Mints();
		$mint_options = $mints->getEarlyMedievalMints();
		$mint_id->addMultiOptions(array(
                    NULL => 'Choose Early Medieval mint',
                    'Available mints' => $mint_options));
	}
	parent::init();
	}
}