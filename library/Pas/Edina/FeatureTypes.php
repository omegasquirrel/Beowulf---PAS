<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FeatureTypes
 *
 * @author danielpett
 */
class Pas_Edina_FeatureTypes extends Pas_Edina {

    const TYPES_EDINA = 'supportedFeatureTypes?';

    public function getTypesList(){

    $types =  $this->_getDataFromEdina();
    $list = array();
    foreach($types->featureTypes as $type){
        $list[] = $type->name;
    }
   
    return $list;
    }

    protected function _getDataFromEdina(){
    $params = array();
    return parent::get(self::TYPES_EDINA, $params);
    }
}



