<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Postcode
 *
 * @author danielpett
 */
class Pas_MapIt_Postcode extends Pas_Mapit {

    protected $_validator;

    protected $_postcode;

    public function __construct($postcode) {
        $this->_validator = new Pas_Validate_ValidPostCode();
        $this->setPostcode($postcode);
        parent::__construct();
    }

    protected function setPostcode(){
    if($this->_validator->isValid($postcode)){
         $this->_postcode = str_replace(' ', '', $postcode);
     } else {
         throw new Pas_MapIt_Exception('Invalid post code specified');
    }
    }

    public function get(){
    $params = array(
         'postcode' => $this->_postcode
    );
    return parent::get($params);
    }
}
