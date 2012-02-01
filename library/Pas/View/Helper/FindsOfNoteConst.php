<?php
/**
 *
 * @author dpett
 * @version
 */

/**
/** A view helper for getting a count of SMR records within a constituency
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_FindsOfNoteConst extends Zend_View_Helper_Abstract {


	/**
	 *
	 */
	protected $_cache;

	protected $_config;

	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
	}

        /** Build and return finds of note count
         *
         * @param type $const
         * @return type
         */
        public function findsOfNoteConst($constituency) {
	return $this->getData($constituency);
	}


        public function getGeometry($constituency){
        $geo = new Pas_Twfy_Geometry();
        return $geo->get($constituency);
        }

        public function getSolr($constituency){
        $geometry = $this->getGeometry($constituency);
        $bbox = array(
            $geometry->min_lat,
            $geometry->min_lon,
            $geometry->max_lat,
            $geometry->max_lon);
	$search = new Pas_Solr_Handler('beowulf');
        $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county')
        );
	$search->setParams(array('note' => '1','bbox' => implode(',',$bbox)));
        $search->execute();
        return $search->getNumber();
        }


        /** Get the finds in that constituency
         * @todo change to solr
         * @param type $const
         * @return boolean
         */
	public function getData($constituency) {

	$data = $this->getSolr($constituency);

        return $this->buildHtml($data);
        
	}

        /**Build the html
         *
         */
	public function buildHtml($data){
	if($data > 0){
	return '<p>There are ' . $data  . ' finds of note in this constituency.</p>';
	} else {
	return false;
	}
	}
}

