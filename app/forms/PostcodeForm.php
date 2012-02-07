<?php
/** Form for solr based single word search
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostcodeForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

	$decorators = array(
            array('ViewHelper'),
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('solr')->removeDecorator('HtmlTag');

	$q = new Zend_Form_Element_Text('postcode');
	$q->setLabel('Search content: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a search term')
		->setDecorators($decorators);
        $thumbnail = new Zend_Form_Element_Checkbox('thumbnail');
        $thumbnail->setLabel('Only with images?')
                ->setUnCheckedValue(null)
                ->setDecorators($decorators);

        $distance = new Zend_Form_Element_Select('distance');
        $distance->setLabel('Distance from postcode')
                ->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a search term')
                ->addMultiOptions(array(1 => '1 km', 2 => '2km',3 => '3km',4 => '4km',
                    5 => '5km'))
                ->setDescription('Distance in KM')
                ->addValidator('Int')
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!')
		->setAttribs(array('class'=> 'large'))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);

	$this->addElements(array($q, $distance, $thumbnail, $submit, $hash ));


	$this->addDisplayGroup(array('postcode', 'distance', 'thumbnail', 'submit'), 'Search');
	$this->Search->removeDecorator('DtDdWrapper');
	$this->Search->removeDecorator('HtmlTag');
	$this->Search->addDecorators(array(array('HtmlTag', array('tag' => 'ul','id' => 'www'))))
	->addDecorator('FieldSet');

	}
}