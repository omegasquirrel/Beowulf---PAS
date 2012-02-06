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

    public function __construct() {
        $this->_validator = new Pas_Validate_ValidPostCode();
        parent::__construct();
    }

    protected function setPostcode($postcode){
    if($this->_validator->isValid($postcode)){
         $this->_postcode = str_replace(' ', '', $postcode);
     } else {
         throw new Pas_MapIt_Exception('Invalid post code specified');
    }
    }

    public function getPostcode() {
        return $this->_postcode;
    }


    public function get(){
    $params = array(
         'postcode' => $this->_postcode
    );
    return parent::get($params);
    }
}
