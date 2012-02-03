<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UniqueNameSearch
 *
 * @author danielpett
 */
class Pas_Edina_UniqueNameSearch extends Pas_Edina{

    const METHOD = 'uniqueNameSearch?';

    protected $_name;

    public function get() {
    $params = array(
        'name' => $this->_name
    );
    return parent::get(self::METHOD, $params);
    }

    public function setName($name){
        if(!is_string($name)){
            throw new Pas_Edina_Exception('The unique name should be a string');
        } else {
            return $this->_name = $name;
        }
    }
}

