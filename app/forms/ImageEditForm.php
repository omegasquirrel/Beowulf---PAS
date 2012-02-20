<?php
/** Form for editing and adding images
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ImageEditForm extends Pas_Form {
	
	public function __construct($options = null) {
		
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	parent::__construct($options);

	$this->setName('imageeditfind');


	$imagelabel = new Zend_Form_Element_Text('label');
	$imagelabel->setLabel('Image label')
	->setRequired(true)
	->setAttrib('size',70)
	->addErrorMessage('You must enter a label')
	->addFilters(array('StringTrim','StripTags'));
		
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => NULL,'Choose a period' => $period_options))
	->addValidator('inArray', false, array(array_keys($period_options)));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose a county' => $county_options))
	->addValidator('inArray', false, array(array_keys($county_options)));

	$copyright = new Zend_Form_Element_Text('imagerights');
	$copyright->setLabel('Image copyright')
	->setAttrib('size',70)
	->addFilters(array('StringTrim','StripTags'));
		
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Image type: ')
	->setRequired(true)
	->addMultiOptions(array('Please choose publish state' => array(
	'digital' => 'Digital image', 'illustration' => 'Scanned illustration')))
	->setValue('digital')
	->addFilters(array('StringTrim','StripTags'));;

	$rotate = new Zend_Form_Element_Radio('rotate');
	$rotate->setLabel('Rotate the image: ')
	->setRequired(false)
	->addValidator('Int')
	->addMultiOptions(array(
	'-90' => '90 degrees anticlockwise', '-180' => '180 degrees anticlockwise', 
	'-270' => '270 degrees anticlockwise', '90' => '90 degrees clockwise',
	'180' => '180 degrees clockwise', '270' => '270 degrees clockwise'));

	$regenerate = new Zend_Form_Element_Checkbox('regenerate');
	$regenerate->setLabel('Regenerate thumbnail: ');

	$filename = new Zend_Form_Element_Hidden('filename');
	$filename->removeDecorator('label')
	->addFilters(array('StringTrim','StripTags'));
		   
	$imagedir = new Zend_Form_Element_Hidden('imagedir');

				
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$imagelabel, $county, $period,
	$copyright, $type, $rotate,
	$regenerate, $filename, $imagedir,
	$submit));

	$this->setMethod('post');
	
	$this->addDisplayGroup(array(
	'label', 'county', 'period',
	'imagerights', 'type', 'rotate',
	'regenerate'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->details->setLegend('Attach an image');

	parent::init();
	}
}