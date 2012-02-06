<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Areas
 *
 * @author danielpett
 */
class Pas_MapIt_Areas extends Pas_MapIt {

    protected $_types = null;

    protected $_ids;

    protected $_geometry = null;

    protected $_methods = array(
        'geometry',
        'children',
        'touches',
        'overlaps',
        'covers',
        'covered',
        'coverlaps'
    );

    protected $_allowedTypes = array(
        'CTY', //(county council)
        'CED', //(county ward)
        'COI', //(Isles of Scilly)
        'COP', //(Isles of Scilly parish)
        'CPC', //(civil parish)
        'DIS', //(district council)
        'DIW', //(district ward)
        'EUR', //(Euro region)
        'GLA', //(London Assembly)
        'LAC', //(London Assembly constituency)
        'LBO', //(London borough)
        'LBW', //(London ward)
        'LGD', //(NI council)
        'LGE', //(NI electoral area)
        'LGW', //(NI ward)
        'MTD', //(Metropolitan district)
        'MTW', //(Metropolitan ward)
        'NIE', //(NI Assembly constituency)
        'OLF', //(Lower Layer Super Output Area, Full)
        'OLG', //(Lower Layer Super Output Area, Generalised)
        'OMF', //(Middle Layer Super Output Area, Full)
        'OMG', //(Middle Layer Super Output Area, Generalised)
        'SPC', //(Scottish Parliament constituency)
        'SPE', //(Scottish Parliament region)
        'UTA', //(Unitary authority),
        'UTE', //(Unitary authority electoral division),
        'UTW', //(Unitary authority ward),
        'WAC', //(Welsh Assembly constituency),
        'WAE', //(Welsh Assembly region),
        'WMC'  //(UK Parliamentary constituency)
    );

    protected $_names = null;

    protected $_format = 'json';

    public function getTypes() {
        return $this->_types;
    }

    public function setTypes($types) {
    if(is_array($types)){
        foreach($types as $type){
            if(strlen($type) <> 3){
                throw new Pas_MapIt_Exception('The area must be a three letter string');
            }
            if(!in_array($type, $this->_allowedTypes)){
                throw new Pas_MapIt_Exception('The area type of ' . $type . ' must be in allowed list');
            }
        }
        $this->_types = implode(',',$types);
    } else {
        throw new Pas_MapIt_Exception('Areas must be an array');
    }
    }

    public function setGeometry($geo){
        if(isset($geo)){
            $this->_geometry = 'geometry';
        }
    }
    public function get() {
        parent::get($method, $params);
    }

    public function setNames($names){
      if(is_array($names)){

      }  else {
          throw new Pas_MapIt_Exception('The names to search on must be an array');
      }
    }

    public function setFormat($format){
        if(in_array($format, $this->_allFormats)){
            $this->_format = $format;
        } else {
            throw new Pas_MapIt_Exception('That format is not allowed');
        }
    }

    public function setIds($ids){
        if(is_array($ids)){
            $this->_ids = implode(',',$ids);
    } else {
        throw new Pas_MapIt_Exception('The ids must be an array');
    }
    }

    public function method($method){


    }


}
