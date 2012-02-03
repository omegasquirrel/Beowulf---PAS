<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NameAndFeatureSearch
 *
 * @author danielpett
 */
class Pas_Edina_NameAndFeatureSearch extends Pas_Edina {

    const METHOD = 'nameAndFeatureSearch?';

    protected $_name;

    protected $_type;

    protected $_required = array('name','featureType');

    public function get() {
    $params = array(
            'name' => $this->_name,
            'featureType' => $this->_type
     );
    $this->_requiredKeys($params);
    
    return parent::get(self::METHOD, $params);
    }


    protected function _requiredKeys($array){
        foreach($array as $k => $v){
            if(!array_key_exists($k, $this->_required)){
                throw new Pas_Edina_Exception('You are missing a required term');
            }
        }
    }

    public function setNames(array $names){
    if(is_array($names)){
        $this->_name = implode(',',$names);
    }    else {
        throw new Pas_Edina_Exception('The search names must be an array');
    }
    }

    public function setFeatureType($type){
        $featureTypes = new Pas_Edina_FeatureTypes();
        $types = $featureTypes->getTypesList();

        if(!in_array($type, $types)){
            throw new Pas_Edina_Exception('That type is not supported');
        } else {
        return $this->_type = $type;
        }
    }

}