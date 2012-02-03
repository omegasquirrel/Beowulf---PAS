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


    /** Construct the validator object
     *
     */
    public function __construct() {
        $this->_validator = new Pas_Validate_ValidPostCode;
        parent::__construct();
    }

    /** Set the postcode
     *
     * @param type $postCode
     */
    public function setPostCode($postCode) {
        $this->_postCode = $postCode;
    }


    /** Validate the postcode
     *
     * @throws Pas_Edina_Exception
     */
    protected function validatePostCode(){
        if(!$this->_validator->isValid($this->_postCode)){
            throw new Pas_Edina_Exception('Invalid postcode given');
        } else {
            $this->_postCode = str_replace(' ', '', $this->_postCode);
        }
    }

    /** Send parameters to parent function
     *
     * @return type
     */
    public function get(){
        $params = array(
          'postCode' => $this->_postCode,
          'gazetteer' => $this->_gazetteer,
          'format' => $this->_format
        );
    return parent::get(self::METHOD, $params);
    }
}
