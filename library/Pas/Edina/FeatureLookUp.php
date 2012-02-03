<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FeatureLookUp
 *
 * @author danielpett
 */
class Pas_Edina_FeatureLookUp extends Pas_Edina {

    const METHOD = 'featureLookup?';

    protected $_id;

    public function get() {

        $params = array('id' => $this->_id);

        return parent::get(self::METHOD, $params);
    }

    public function setId($id){
        if(!is_int){
            throw new Pas_Edina_Exception('Entity ID must be an integer');
        } else {
            return $this->_id = $id;
        }
    }
}
