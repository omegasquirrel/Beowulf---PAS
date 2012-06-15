<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * StatisticsDatabase helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_StatisticsDatabase {
	
	protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    public function __construct(){
    $this->_cache = Zend_Registry::get('cache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
    $this->_solr = new Solarium_Client($this->_solrConfig);
    }
	
	public function statisticsDatabase() {
	return $this->buildHtml($this->getSolrResults());
	}
	
	private function getSolrResults() {
	if (!($this->_cache->test('stats'))) {
	$query = $this->_solr->createSelect();
	$query->setRows(0);
	$stats = $query->getStats();
	$stats->createField('quantity');
	$resultset = $this->_solr->select($query);
	$data = $resultset->getStats();
	$stats = array();
	foreach($data as $result){ 
	$stats['total']=  $result->getSum();
	$stats['records'] = $result->getCount();
	} 
	$this->_cache->save($stats);
	} else {
	$stats = $this->_cache->load('stats');
	}
	return $stats;
	}
	
	public function buildHtml($data){
	$html = '<div id="totals" class="hero-unit">'. number_format($data['total'])
	 . ' objects within ' .	number_format($data['records']) . ' records.</div>';
	return $html;	
	}
}

