<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClosestMatchSearch
 *
 * @author danielpett
 */
class Pas_Edina_ClosestMatchSearch extends Pas_Edina{

    const METHOD = 'closestMatchSearch?';

    protected $_name;

    public function setName($name){
        if(!is_string($name)){
            throw new Pas_Edina_Exception('The name must be a string');
        } else {
            return $this->_name = $name;
        }
    }

    public function get() {
        $params = array(
            'name' => $this->_name
        );
    return parent::get(self::METHOD, $params);
    }
}
