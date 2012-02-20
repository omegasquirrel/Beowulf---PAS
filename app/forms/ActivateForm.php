<?php

/** Form for activating an account
*
* @category   	Pas
* @package    	Pas_Form
* @copyright  	Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @todo 	  	Check still active
* @author		Daniel Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class ActivateForm extends Pas_Form {


    public function init() {

        $username = $this->addElement('text', 'username',
            array('label' => 'Username'));
        $username = $this->getElement('username')
                  ->addValidator('Alnum')
                  ->setRequired(true)
                  ->addFilter('StringTrim')
                  ->addValidator('Authorise');
        $username->getValidator('Alnum')
                 ->setMessage('Your username should include letters and numbers only');



        $submit = $this->addElement('submit', 'Login');


	$this->setLegend('Activate your account on Beowulf: ');
	parent::init();
	}

}