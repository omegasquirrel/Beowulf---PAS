<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FootprintLookup
 *
 * @author danielpett
 */
class Pas_Edina_FootprintLookup extends Pas_Edina {

    const METHOD = 'footprintLookup?';

    protected $_footprints;

    public function setFootprints(array $footprints){
        if(!is_array($footprints)){
            throw new Pas_Edina_Exception('The footprint IDs must be an array');
        } else {
            return $this->_footprints = implode(',',$footprints);
        }
    }

    public function get() {
        $params = array(
            'identifier' => $this->_footprints
        );
    return parent::get(self::METHOD, $params);
    }

}