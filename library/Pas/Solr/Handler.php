<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoreLikeThis
 *
 * @author Daniel Pett
 */
define('SCHEMA_PATH', '/home/beowulf2/solr/solr/');

define('SCHEMA_FILE', '/conf/schema.xml' );

class Pas_Solr_Handler {

    protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    protected $_facets;

    protected $_allowed =  array('fa','flos','admin','treasure');

    protected $_formats = array(
    	'json', 'csv', 'xml',
        'midas', 'rdf', 'n3',
        'rss', 'atom');

    protected $_format;

    protected $_schemaFields;

    protected $_params;

    public function __construct($core){
    $this->_cache = Zend_Registry::get('cache');
    $this->_config = Zend_Registry::get('config');
    $this->_core = $core;
    $this->_solrConfig = $this->_setSolrConfig($this->_core);
    $this->_solr = new Solarium_Client($this->_solrConfig);
    $this->_checkFieldList($this->_core, $this->setFields());
    $this->_checkCoreExists();
    $this->_getSchemaFields();
    }

    private function _getCores() {
    if (!($this->_cache->test('solrCores'))) {
    $dir = new DirectoryIterator(SCHEMA_PATH);
    $cores = array();
    foreach ($dir as $dirEntry) {
            if($dirEntry->isDir() && !$dirEntry->isDot()){
                    $cores[] = $dirEntry->getFilename();
            }
    }
    $this->_cache->save($cores);
    } else {
    $cores = $this->_cache->load('solrCores');
    }
    return $cores;
    }


    private function _getSchemaFields(){
        $file = SCHEMA_PATH . $this->_core . SCHEMA_FILE;
	$key = md5($file);
	if (!($this->_cache->test($key))) {
        if(file_exists($file)){
    	$xml = simplexml_load_file($file);
    	$schemaFields = array();
    	foreach($xml->fields->field as $field){
            $string = get_object_vars($field->attributes());
            //This bit looks honky, couldn't get it to work with object notation
            $schemaFields[] = $string["@attributes"]['name'];
    	}
        }

	$this->_cache->save($schemaFields);
	} else {
	$schemaFields = $this->_cache->load($key);
	}
        $this->_schemaFields = $schemaFields;
        return $this->_schemaFields;
    }

    protected function _checkCoreExists(){
    	if(!in_array($this->_core,$this->_getCores())){
    		throw new Exception('That is not a valid core',500);
    	} else {
    		return true;
    	}
    }

    protected function _setSolrConfig($core){
    $config = $this->_config->solr->toArray();
    if(isset($core)){
    	$config['core'] = $core;
    }
    return $this->_solrConfig = array('adapteroptions' => $config);
    }

    protected function _getRole(){
    $user = new Pas_UserDetails();
    return $user->getPerson()->role;
    }


    public function setFields($fields = NULL){
    if(is_array($fields)){
        $this->_fields = $fields;
    } else {
       $this->_fields = array('*');
    }
    return $this->_fields;
    }

    public function setParams(array $params){
    	if(is_array($params)){
            $this->_params = $params;
    	return $this->_params;
    	}
    }

    public function setHighlights(array $highlights){
        if(is_array($highlights)){
            $this->_highlights = $highlights;
        }
        return $this->_highlights;
    }

    protected function _createHighlighting(){
    $hl = $this->_query->getHighlighting();
    $hl->setFields(implode($this->_highlights,','));
    $hl->setSimplePrefix('<span class="hl">');
    $hl->setSimplePostfix('</span>');
    return $hl;
    }

    public function getHighlights(){
        if($this->_highlights){
            return $this->_resultset->getHighlighting();
        }
    }

    protected function _createFilters(array $params){
        if(is_array($params)){


        if(!is_null($params['d']) && !is_null($params['lon']) && !is_null($params['lat'])){
        $helper = $this->_query->getHelper();
        $this->_query->createFilterQuery('geo')->setQuery(
            $helper->geofilt(
                $params['lat'],
                $params['lon'],
                'coordinates',
                $params['d'])
                );
        unset($params['d']);
        unset($params['lon']);
        unset($params['lat']);
        }
          foreach($params as $key => $value){
            if(!in_array($key, $this->_schemaFields))   {
                unset($params[$key]);
            }
        }
        $this->_checkFieldList($this->_core, array_keys($params));
        foreach($params as $key => $value){
            $this->_query->createFilterQuery($key)->setQuery($key . ':"'
                    . $value . '"');
        }

        } else {
            throw new Pas_Solr_Exception('The search params must be an array');
        }

    }

    public function setFacets($facets){
    	if(is_array($facets)){
    		$this->_facets = $facets;
    		return $this->_facets;
    	}
    }

    public function _createPagination(){
    $paginator = Zend_Paginator::factory($this->_resultset->getNumFound());
    $paginator->setCurrentPageNumber($this->getPage($this->_params))
            ->setItemCountPerPage($this->_getRows($this->_params))
            ->setPageRange(20);
    return $paginator;
    }

    public function _processResults(){
    $data = array();
    foreach($this->_resultset as $doc){
	$fields = array();
	foreach($doc as $key => $value){
            $fields[$key] = $value;
            }
    	$data[] = $fields;
    }
    return $data;
    }

    public function _processFacets(){
    if($this->_facets){
	$facetData = array();
        foreach($this->_facets as $k){
            $facetData[$k] = array();
            $f = $this->_resultset->getFacetSet()->getFacet($k);
            foreach($f as $value => $count) {
            $facetData[$k][ $value ]  = $count;
            }
        }

        return $facetData;
        } else {
            return false;
        }
    }

    protected function _checkFieldList($core = 'beowulf',  $fields){
    if(!is_null($fields)){

        $this->_schemaFields[] = '*';

	foreach($fields as $f){
		if(!in_array($f,$this->_schemaFields)){
                    throw new Pas_Solr_Exception('The field ' . $f
                            . ' is not in the schema');
		}
	}
    } else {
        throw new Pas_Solr_Exception('The fields supplied are not an array');
    }
    }

    protected function _getSort($core, $params){
    	if(array_key_exists('sort',$params)){
    		$this->_checkFieldList($core, array($params['sort']));
    		$field = $params['sort'];
    	} else {
    		$field = 'created';
    	}
    	$allowed = array('desc','asc');
    	if(array_key_exists('direction', $params)) {
    		if(in_array($params['direction'],$allowed)){
    		$direction = $params['direction'];
    		} else {
    			throw new Pas_Solr_Exception('That directional sort does not exist');
    		}
    	} else {
    		$direction = 'desc';
    	}

    	return array($field => $direction);
    }

    public function _getRows($params){
        if(isset($params['show'])){
            $rows = $params['show'];
            if($rows > 50){
                $rows = 50;
            }
        } else {
            $rows = 20;
        }
        return $rows;
    }

    public function _getStart($params){

        if(array_key_exists('page', $params)){
            $start = ($params['page'] - 1) * $this->_getRows($params);
        } else {
            $start = 0;
        }
        return $start;
    }

    public function getPage($params){
        if(array_key_exists('page', $params)){
            $page = $params['page'];
        } else {
            $page = 1;
        }
        return $page;
    }

    public function execute( ){
    $select = array(
    'query'         => '*:*',
    'fields'        => array('*'),
    'filterquery' => array(),
    );

    $select['sort'] = $this->_getSort($this->_core, $this->_params);
//    Zend_Debug::dump($select, 'The sort');
    $select['rows'] = $this->_getRows($this->_params);
    $select['start'] = $this->_getStart($this->_params);

    // get a select query instance based on the config
    $this->_query = $this->_solr->createSelect($select);

    $this->_query->setFields($this->_fields);


    if(!in_array($this->_getRole(),$this->_allowed)) {
    $this->_query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
    if(array_key_exists('parish',$this->_params) && ($this->_core === 'beowulf')){
    $this->_query->createFilterQuery('knownas')->setQuery('knownas:["" TO *]');
	}
    }

    if(!is_null($this->_facets)){
    	$this->_createFacets($this->_facets);
    }

    if(!is_null($this->_params)){
    	$this->_createFilters($this->_params);
        if(array_key_exists('format', $this->_params)){
        $this->_processFormats($this->_params);
    }
    }



    Zend_Debug::dump($this->_query, 'The Query!');

    $this->_resultset = $this->_solr->select($this->_query);
    return $this->_resultset;
//    Zend_Debug::dump($resultset, 'The Resultset');
//    Zend_Debug::dump($this->_createPagination($resultset), 'The pagination');
//    Zend_Debug::dump($this->_processResults($resultset), 'The processed results');
//    Zend_Debug::dump($this->_processFacets($resultset, $this->_facets),'The facet set');
    }


    protected function _createFacets($facets){
    $this->_checkFieldList($this->_core, $this->_facets);
    $facetSet = $this->_query->getFacetSet();
        foreach($this->_facets as $key){
            $facetSet-> createFacetField($key)->setField($key);
        }
    }


    protected function _processFormats(){
        $format = $this->_params['format'];
        if(in_array($format, $this->_allowed)){
            return $this->_format = $format;
        } else {
            return false;
        }
    }

}

