<?php
/** Form for searching for early medieval coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedNumismaticSearchForm extends Pas_Form {

    protected function getRole(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

    protected $higherlevel = array('admin', 'flos', 'fa', 'heros', 'treasure');

	protected $restricted = array('public', 'member', 'research');

	public function __construct($options = null) {


	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();

	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();

	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();

	$counties = new Counties();
	$county_options = $counties->getCountyName2();

	$rulers = new Rulers();
	$ruler_options = $rulers->getEarlyMedRulers();

	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();

	$mints = new Mints();
	$mint_options = $mints->getEarlyMedievalMints();

	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();


	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();

	$regions = new Regions();
	$region_options = $regions->getRegionName();

	parent::__construct($options);

	$this->setName('earlymedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!');


	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');

        $workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int');

	if(in_array($this->getRole(),$this->higherlevel)) {
	$workflow->addMultiOptions(array(NULL => 'Available Workflow stages',
            'Choose Worklow stage' => array(
                '1' => 'Quarantine',
                '2' => 'On review',
                '4' => 'Awaiting validation',
                '3' => 'Published')));
	}
	if(in_array($this->getRole(),$this->restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Available Workflow stages',
            'Choose Worklow stage' => array(
                '4' => 'Awaiting validation',
                '3' => 'Published')));
	}


	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
	->setRequired(false)
	->addValidator('Int')
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(
            NULL => 'Choose a rally',
            'Available rallies' => $rally_options));

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(
             NULL => 'Available hoards',
            'Choose a hoard' => $hoard_options));


	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(
            NULL => 'Choose county',
            'Available counties' => $county_options));

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->addMultiOptions(array(NULL => 'Choose district after county'))
	->setRegisterInArrayValidator(false)
	->addFilters(array('StringTrim','StripTags'))
	->setDisableTranslator(true);

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose parish after county'))
	->setDisableTranslator(true);

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->setRegisterInArrayValidator(false)
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Choose a region for a wide result',
            'Choose region' => $region_options));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef'))
	->addFilters(array('StringTrim','StripTags'));

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'));


	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
        ->setRegisterInArrayValidator(false)
        ->setRequired(false)
        ->addFilters(array('StripTags','StringTrim'))
        ->addValidator('Int')
        ->addMultiOptions(array(
            NULL => 'Choose denomination type',
        'Available denominations' => $denomination_options));

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
        ->setRegisterInArrayValidator(false)
        ->setRequired(false)
        ->addValidator(('Int'))
        ->addFilters(array('StripTags','StringTrim'))
        ->addMultiOptions(array(NULL => 'Choose an Early Medieval category',
        'Available categories' => $cat_options));


	$type = new Zend_Form_Element_Select('typeID');
	$type->setLabel('Coin type: ')
	->setRegisterInArrayValidator(false)
        ->setRequired(false)
        ->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'));


	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
        ->setRegisterInArrayValidator(false)
        ->setRequired(false)
        ->addValidator('Int')
        ->addFilters(array('StripTags','StringTrim'))
        ->addMultiOptions(array(NULL => 'Choose primary ruler',
        'Available options' => $ruler_options));


	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
        ->setRegisterInArrayValidator(false)
        ->setRequired(false)
        ->addValidator('Int')
        ->addFilters(array('StripTags','StringTrim'))
        ->addMultiOptions(array(NULL => 'Choose issuing mint',
        'Available mints' => $mint_options));


	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term');


	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
		->addMultiOptions(array(NULL => 'Choose a die axis measurement',
		'Available options' => $axis_options));


	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Early Medieval')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->addFilter('StringToUpper')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->setLabel('Submit');

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
        ->addValidator('Alpha',true)
	->addMultiOptions(array(
            null => 'Choose an institution',
            'Available institutions' => $inst_options));


	$this->addElements(array(
	$old_findID, $type, $description,
	$workflow, $rally, $rallyID,
	$hoard, $hoardID, $county,
	$regionID, $district, $parish,
	$fourFigure, $gridref, $denomination,
	$ruler, $mint, $axis,
	$obverseinsc, $obversedesc, $reverseinsc,
	$reversedesc, $objecttype, $broadperiod,
	$cat, $submit, $institution));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(60);
	$this->addElement($hash);

	$this->addDisplayGroup(array(
            'category', 'ruler', 'typeID',
            'denomination', 'mint','moneyer',
            'axis', 'obverseLegend', 'obverseDescription',
            'reverseLegend','reverseDescription'),
                'numismatics');
	$this->numismatics->setLegend('Numismatic details: ');
	$this->addDisplayGroup(array(
            'old_findID','description','rally',
            'rallyID','hoard','hID','workflow'),
                'details');

	$this->details->setLegend('Object details: ');
	$this->addDisplayGroup(array(
            'county','regionID','district',
            'parish','gridref','fourFigure',
            'institution'), 'spatial');
	$this->spatial->setLegend('Spatial details: ');


	$this->addDisplayGroup(array('submit'), 'submit');

	parent::init();
	}
}