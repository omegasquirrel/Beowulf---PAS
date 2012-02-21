<?php
/** Form for filtering finds
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Will need changing for the solr version
*/

class FindFilterForm extends Pas_Form {

public function __construct($options = null) {

    $periods = new Periods();
    $periodword_options = $periods->getPeriodFromWords();

    $counties = new Counties();
    $county_options = $counties->getCountyName2();

    parent::__construct($options);

    $this->setName('filterfinds');

    $objecttype = new Zend_Form_Element_Text('objecttype');
    $objecttype->setLabel('Filter by object type')
    ->setRequired(false)
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('Alpha', false, array('allowWhiteSpace' => true))
    ->addErrorMessage('Come on it\'s not that hard, enter a title!')
    ->setAttrib('size', 10);

    $oldfindID = new Zend_Form_Element_Text('old_findID');
    $oldfindID->setLabel('Filter by find ID #')
    ->setRequired(false)
    ->addFilters(array('StripTags','StringTrim'))
    ->setAttrib('size', 11)
    ->addValidator('stringLength', false, array(1,200));

    $broadperiod = new Zend_Form_Element_Select('broadperiod');
    $broadperiod->setLabel('Filter by broadperiod')
    ->setRequired(false)
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('stringLength', false, array(1,200))
    ->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
    ->addValidator('InArray', false, array(array_keys($periodword_options)));

    $county = new Zend_Form_Element_Select('county');
    $county->setLabel('Filter by county')
    ->setRequired(false)
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('stringLength', false, array(1,200))
    ->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
    ->addValidator('InArray', false, array(array_keys($county_options)));

    //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Filter:');

    $hash = new Zend_Form_Element_Hash('csrf');
    $hash->setValue($this->_salt)
    ->setTimeout(60);
    $this->addElement($hash);

    $this->addElements(array(
    $objecttype, $oldfindID, $broadperiod,
    $county, $submit));

    parent::init();

    }
}