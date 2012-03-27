<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nms
 * This class is just for the use of Norfolk. Complete waste of time implementing
 * it.
 *
 * @author danielpett
 */
class Pas_Exporter_Nms extends Pas_Exporter_Generate {

    protected $_format = 'nms';


    protected $_nmsFields = array(
	'id','old_findID','description', 'fourFigure',
        'gridref', 'county',
	'district', 'parish','knownas',
        'finder', 'smrRef','otherRef',
        'identifier'
        );

    public function __construct() {
        parent::__construct();
    }

    public function create(){
    $this->_search->setFields($this->_nmsFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    return  $this->_clean($this->_search->_processResults());
    }

    protected function _clean($data){
        foreach($data as $dat){

        foreach($dat as $k => $v){
            $record[$k] = trim(strip_tags(str_replace('<br />',array( ""), utf8_decode( $v ))));
        }
        $finalData[] = $record;
        }
    return $finalData;
    }

}

