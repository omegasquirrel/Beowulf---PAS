<?php
/** Form for solr based single word search
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SolrForm extends Twitter_Form
{
public function __construct($options = null)
{
    parent::__construct($options);



	$this->setName('solr')->removeDecorator('HtmlTag');

	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search content: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a search term');

        $thumbnail = new Zend_Form_Element_Checkbox('thumbnail');
        $thumbnail->setLabel('Only with images?')
                ->setUnCheckedValue(null);

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

	$this->addElements(array($q, $thumbnail, $submit, $hash ));




	}
}