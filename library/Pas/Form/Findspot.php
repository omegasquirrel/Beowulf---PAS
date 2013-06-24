<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinFormLoader
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_Form_Findspot {

  	protected $_view;


    public function __construct() {
    	$this->_view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
    }

    protected function _getIdentity(){
    $user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->id;
    } else {
    	throw new Pas_Exception_BadJuJu('No user credentials found', 500);
    }
    }



    public function populate($data){
    	
	$this->_view->form->populate($data);

	
    if(array_key_exists('county', $data)) {
    
    $districts = new Places();
    $district = $districts->getDistrictList($data['county']);
    if($district) {
    $this->_view->form->district->addMultiOptions(array('Available districts' => $district));
    }
    
    if(array_key_exists('district', $data)) {
    $parishModel = new OsParishes();
    $parishes = $parishModel->getParishesToDistrict($data['district']);
    $this->_view->form->parish->addMultiOptions(array('Available parishes' => $parishes));
    }
    
    if(array_key_exists('county', $data)) {
    $countyModel = new OsCounties();
    $regions = $countyModel->getCountyToRegion($data['county']);
    $this->_view->form->regionID->addMultiOptions(array('Available regions' => $regions));
    }
    }
    
    if(array_key_exists('landusevalue', $data)) {
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($data['landusevalue']);
    $this->_view->form->landusecode->addMultiOptions(array(NULL => 'Choose code',
    	'Available landuses' => $landusecode_options));
     }
     
    if(array_key_exists('landowner', $data)) {
    $finders = new Peoples();
    $finders = $finders->getName($data['landowner']);
    foreach($finders as $finder) {
    $form->landownername->setValue($finder['term']);
    }
    }
    }

}


