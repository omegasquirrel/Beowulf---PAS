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
class Pas_MapIt_Area extends Pas_Mapit {
	
	const APIMETHOD = 'area';

    protected $_types = null;

    protected $_id;

    protected $_geometry = null;

    protected $_methods = array(
        'geometry',
        'children',
        'touches',
        'overlaps',
        'covers',
        'covered',
        'coverlaps',
    	'example_postcode'
    );
    
    protected $_system;
    
    protected $_method;
    
    protected $_format;
    
    protected $_formats = array('json','wkt','kml','geojson');
 
    public function get() {
    	$params = array(
    	$this->_id,
    	$this->_system,
    	$this->_method
    	);
        parent::get(self::APIMETHOD, $params);
    }
    
    public function setFormat($format){
        if(in_array($format, $this->_formats)){
            $this->_format = $format;
        } else {
            throw new Pas_MapIt_Exception('That format is not allowed');
        }
    }

    public function getFormat(){
    	return $this->_format;
    }
    
    public function setId($id){
    if(is_numeric($id)){
    	$this->_id = $id;
    } else {
        throw new Pas_MapIt_Exception('The id must be an integer');
    }
    }
    
    public function getId(){
    	return $this->_id;
    }
	
	/**
	 * @param $_method the $_method to set
	 */
	public function setMethod($method) {
		if(in_array($method, $this->_methods)){
		$this->_method = $_method;
		} else {
			throw new Pas_MapIt_Exception('That method does not exist');
		}	
	}
    
	/**
	 * @return the $_method
	 */
	public function getMethod() {
		return $this->_method;
	}

	
	
	
}
