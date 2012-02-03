<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NameSearch
 *
 * @author danielpett
 */
class Pas_Edina_NameSearch extends Pas_Edina{

    const METHOD = 'nameSearch?';

    protected $_name;

    public function get() {
        $params = array(
            'name' => $this->_name
        );

    return parent::get(self::METHOD, $params);
    }

    public function setNames(array $names){
    if(is_array($names)){
        $this->_name = implode(',',$names);
    }    else {
        throw new Pas_Edina_Exception('The search names must be an array');
    }
    }
//put your code here
}


