<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SensitiveFields
 *
 * @author danielpett
 */
class Pas_Solr_SensitiveFields {


    protected $_allowed = array('fa','flos','admin','treasure', 'research', 'hero');

    protected $_personal = array('fa','flos','admin','treasure');

    public function cleanData( $data, $role, $core ){

    if(!in_array($role, $this->_allowed) && $core == 'beowulf'){

       return $this->_processGeoData($data);
    } else {
        return $data;
    }

    }

    protected function _processGeoData($data){

        if(is_array($data)){
		$clean = array();
            foreach($data as $record){
                if(!is_null($record['knownas'])){
                    $record['gridref'] = NULL;
                    $record['parish'] = 'Restricted Access';
                    $record['fourFigure'] = NULL;
                    $record['easting'] = NULL;
                    $record['northing'] = NULL;
                    $record['latitude'] = NULL;
                    $record['longitude'] = NULL;
                    $record['woeid'] = NULL;

                } else if(!is_null($record['gridref']) && is_null($record['knownas'])) {
                    $geo = new Pas_Geo_Gridcalc($record['fourFigure']);
                    $coords = $geo->convert();

                    $record['gridref'] = $coords['fourFigureGridRef'];
                    $record['fourFigure'] = $coords['fourFigureGridRef'];
                    $record['latitude'] = $coords['decimalLatLon']['decimalLatitude'];
                    $record['longitude'] = $coords['decimalLatLon']['decimalLongitude'];
                    $record['easting'] = $coords['easting'];
                    $record['northing'] = $coords['northing'];
                    $record['woeid'] = NULL;

                }
                if(!in_array($role, $this->_personal)){
                unset($record['finder']);
            }
            $clean[] = $record;
            }

        }
       
        return $clean;

    }

}


