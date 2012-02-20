<?php
/** Form for linking references to finds.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ReferenceFindForm extends Pas_Form {
	
public function __construct($options = null) {
	parent::__construct($options);
	$this->setName('addreference');



	$title = new Zend_Form_Element_Text('publicationtitle');
	$title->setLabel('Publication title: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a title')
		->setAttrib('size', 60)
		->setDescription('As the bibliographic details that have been entered are such a mess, 
		this is slightly tricky. Try one word from the title of the book/journal or an author surname. 
		Then click on the one that comes up.');

	$id = new Zend_Form_Element_Hidden('pubID');
	$id->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'));

	$pages = new Zend_Form_Element_Text('pages_plates');
	$pages->setLabel('Pages or plate number: ')
		->addFilters(array('StripTags', 'StringTrim'))	
		->setAttrib('size',9);

	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Reference number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size', 15);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array(
	$title, $id, $pages,
	$reference, $submit));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('publicationtitle','pubID','pages_plates',
            'reference'), 'details');
	$this->details->setLegend('Add a new reference');
	$this->addDisplayGroup(array('submit'),'submit');
	parent::init();
	}
}