<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Generations
 *
 * @author danielpett
 */
class Pas_MapIt_Generations extends Pas_Mapit {
    
	const APIMETHOD = 'generations';
	
	public function get(){
		return parent::get(self::APIMETHOD, $params = array());
	}
}
