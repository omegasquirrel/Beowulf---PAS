<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OaiResponse
 *
 * @author danielpett
 */
class Pas_Solr_OaiResponse {

    const LISTLIMIT = 30;

    public function getRecord($id){

    }

    public function getRecords($cursor = 0, $set = null, $from, $until) {

    $fields = array(
        'id', 'old_findID', 'creator',
        'description', 'broadperiod', 'thumbnail',
        'imagedir','filename', 'created',
        'institution'

    );
    $select = array(
    'query'         => '*:*',
    'start'         => $cursor,
    'rows'          => self::LISTLIMIT,
    'fields'        => $fields,
    'sort'          => array('created' => 'asc'),
    'filterquery' => array(),
    );

    if(!in_array($this->getRole(),$this->_allowed)) {
    $select['filterquery']['workflow'] = array('query' => 'workflow:[3 TO 4]');
    }
    if(!is_null($set)){
    $select['filterquery']['set'] = array( 'query' => 'institution:' . $set );
    }
    if(isset($from)){
    $select['filterquery']['from'] = array( 'query' => 'created:[' . $this->todatestamp($from) . 'TO * ]' );
    }

    if(isset($until)){
    $select['filterquery']['until'] = array( 'query' => 'created:[* TO ' . $this->todatestamp($until) . ']' );
    }

    $cachekey = md5($q . $this->getRole());
    if (!($this->_cache->test($cachekey))) {
    $query = $this->_solr->createSelect($select);
    $resultset = $this->_solr->select($query);
    $data = array();
    $data['numberFound'] = $resultset->getNumFound();
    foreach($resultset as $doc){
	$fields = array();
            foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	$data['images'][] = $fields;
	}
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($cachekey);
	}
    return $data;
    }

    public function fromString($date_string) {
	if (is_integer($date_string) || is_numeric($date_string)) {
	return intval($date_string);
	} else {
	return strtotime($date_string);
	}
	}

    /** Format the date and return as unix stamp
	*
	* @param string $date_string
	*/
	public function todatestamp($date_string) {
	$date = $this->fromString($date_string);
	$ret = date('Y-m-d\TH:i:s\Z', $date);
	return $ret;
	}
}
