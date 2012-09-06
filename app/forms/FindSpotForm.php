<?php
/** Form for manipulating findspots data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class FindSpotForm extends Pas_Form {

public function __construct($options = null) {
	// Construct the select menu data
	$counties = new Counties();
	$county_options = $counties->getCountyName2();

	$origins = new MapOrigins();
	$origin_options = $origins->getValidOrigins();

	$landusevalues = new Landuses();
	$landuse_options = $landusevalues->getUsesValid();

	$landusecodes = new Landuses();
	$landcodes_options = $landusecodes->getCodesValid();
	$this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');

	parent::__construct($options);

	$this->setName('findspots');

	// Object specifics
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose county','Valid counties' => $county_options))
	->addValidator('InArray', false, array(array_keys($county_options)));

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose district after county'));

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose parish after district'));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->setRegisterInArrayValidator(false)
	->addValidator('Digits')
	->addMultiOptions(array(NULL => 'Choose region after county'));

	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef'))
	->addFilters(array('StripTags', 'StringTrim', 'StringToUpper', 'StripSpaces'))
	->setAttribs(array('placeholder' => 'A grid reference is in the format SU123123', 'class' => 'span4'));

	$gridrefsrc = new Zend_Form_Element_Select('gridrefsrc');
	$gridrefsrc->setLabel('Grid reference source: ')
	->addMultioptions(array(NULL => NULL,'Choose source' => $origin_options))
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('InArray', false, array(array_keys($origin_options)))
	->addValidator('Int');

	$gridrefcert = new Zend_Form_Element_Radio('gridrefcert');
	$gridrefcert->setLabel('Grid reference certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''));

	if($action === 'edit'){
	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidator('NotEmpty','ValidGridRef')
	->addValidator('Alnum')
	->addFilters(array('StripTags', 'StringTrim'))
	->disabled = true;

	$easting = new Zend_Form_Element_Text('easting');
	$easting->setLabel('Easting: ')
	->addValidator('NotEmpty','Digits')
	->addFilters(array('StripTags', 'StringTrim'))
	->disabled = true;

	$northing = new Zend_Form_Element_Text('northing');
	$northing->setLabel('Northing: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;

	$map10k = new Zend_Form_Element_Text('map10k');
	$map10k->setLabel('10 km map: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Alnum')
	->disabled = true;

	$map25k = new Zend_Form_Element_Text('map25k');
	$map25k->setLabel('25 km map: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Alnum')
	->disabled = true;

	$declong = new Zend_Form_Element_Text('declong');
	$declong->setLabel('Longitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;


	$declat = new Zend_Form_Element_Text('declat');
	$declat->setLabel('Latitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;

	$woeid = new Zend_Form_Element_Text('woeid');
	$woeid->setLabel('Where on Earth ID: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;

	$elevation = new Zend_Form_Element_Text('elevation');
	$elevation->setLabel('Elevation: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;
	}

	$depthdiscovery = new Zend_Form_Element_Select('depthdiscovery');
	$depthdiscovery->setLabel('Depth of discovery')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->addMultiOptions(array(NULL => 'Depth levels','Approximate depth' => array(
	'10' => '0 - 10cm', '20' => '10 - 20cm', '30' => '20 - 30cm',
	'40' => '30 - 40cm', '50' => '40 - 50cm',
	'60' => 'Over 60 cm')));

	$soiltype = new Zend_Form_Element_Select('soiltype');
	$soiltype->setLabel('Type of soil around findspot: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->addMultiOptions(array(NULL => NULL));


	$landusevalue = new Zend_Form_Element_Select('landusevalue');
	$landusevalue->setLabel('Landuse type: ')
	->addValidators(array('NotEmpty'))
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose landuse',
            'Valid landuses' => $landuse_options));

	$landusecode = new Zend_Form_Element_Select('landusecode');
	$landusecode->setLabel('Specific landuse: ')
	->setRegisterInArrayValidator(false)
	->addValidators(array('NotEmpty'))
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Specific landuse will be enabled after type'));


	$address = new Zend_Form_Element_Textarea('address');
	$address->setLabel('Address: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',5)
	->setAttrib('cols',40)
	->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'))
	->setAttribs(array('placeholder' => 'This data is not shown to the public'))
	->setAttrib('class','privatedata span6');

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->setLabel('Postcode: ')
	->addValidators(array('NotEmpty', 'ValidPostCode'))
	->addFilters(array('StripTags', 'StringTrim','StringToUpper'));

	$knownas = new Zend_Form_Element_Text('knownas');
	$knownas->setLabel('Findspot to be known as: ')
	->setAttribs(array('placeholder' => 'If you fill in this, it will hide the grid references and parish', 'class' => 'span6 privatedata'))
	->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$landownername = new Zend_Form_Element_Text('landownername');
	$landownername->setLabel('Landowner: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('class','privatedata')
	->setAttribs(array('placeholder' => 'This data is not shown to the public'))
	->addFilters(array('StripTags', 'StringTrim'));

	$landowner = new Zend_Form_Element_Hidden('landowner');
	$landowner->addFilters(array('StripTags', 'StringTrim'));;

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Findspot description: ')
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400, 'class' => 'privatedata span6'))
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$comments = new Pas_Form_Element_RTE('comments');
	$comments->setLabel('Findspot comments: ')
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400, 'class' => 'privatedata span6'))
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));


	$submit = new Zend_Form_Element_Submit('submit');

	

	if($action == 'edit') {
	$this->addElements(array(
	$county, $district, $parish,
	$knownas, $description, $comments,
	$regionID, $gridref, $fourFigure,
	$easting, $northing, $map10k,
	$map25k, $declong, $declat,
	$woeid, $elevation, $address,
	$gridrefsrc, $gridrefcert, $depthdiscovery,
	$postcode, $landusevalue, $landusecode,
	$landownername, $landowner,	$submit,
	));
	} else {
	$this->addElements(array(
	$county, $district, $parish,
	$knownas, $depthdiscovery, $description,
	$comments, $regionID, $gridref,
	$gridrefsrc, $gridrefcert,
	$address, $postcode, $landusevalue,
	$landusecode, $landownername, $landowner,
	$submit, ));
	}


	$this->addDisplayGroup(array(
	'county', 'regionID', 'district',
	'parish', 'knownas',
	'address', 'postcode', 'landownername',
	'landowner'),
	'details');
	
	$this->details->setLegend('Findspot information');
	
	if($action == 'edit') {
	$this->addDisplayGroup(array(
	'gridref', 'gridrefcert', 'gridrefsrc',
	'fourFigure', 'easting', 'northing',
	'map25k', 'map10k',	'declat',
	'declong', 'woeid', 'elevation',
	'landusevalue', 'landusecode', 'depthdiscovery',
	),'spatial');
	} else {
	$this->addDisplayGroup(array(
	'gridref','gridrefcert', 'gridrefsrc',
	'landusevalue', 'landusecode', 'depthdiscovery',
	'soiltype'), 'spatial');
	}

	$this->spatial->setLegend('Spatial information');

	$this->addDisplayGroup(array('description','comments'),'commentary');
	
	$this->commentary->setLegend('Findspot comments');

	$this->addDisplayGroup(array('submit'), 'submit');

    parent::init();
	}
}