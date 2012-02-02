<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostCodeSearch
 *
 * @author danielpett
 */
class Pas_Edina_PostCodeSearch extends Pas_Edina {

    const METHOD = 'postCodeSearch?';

    protected $_postCode;

    protected $_validator;

    protected $_format;

    public function __construct() {
        $this->_validator = new Pas_Validate_ValidPostCode;
        parent::__construct();
    }

    public function setPostCode($postCode) {
        $this->_postCode = $postCode;
    }


    protected function validatePostCode(){
        if(!$this->_validator->isValid($this->_postCode)){
            throw new Pas_Edina_Exception('Invalid postcode given');
        } else {
            $this->_postCode = str_replace(' ', '', $this->_postCode);
        }
    }

    public function getFormat() {
        return $this->_format;
    }

    public function setFormat($format = 'json') {
        $this->_format = $format;
    }


    public function get(){
        $params = array(
          'postCode' => $this->_postCode,
          'gazetteer' => 'unlock',
          'format' => $this->_format
        );
    return parent::get(self::METHOD, $params);
    }
}
