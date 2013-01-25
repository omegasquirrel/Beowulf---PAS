<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VisitorsController
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Analytics_SitespeedController 
    extends Pas_Controller_Action_Admin {
   
    public function init(){
        $this->_helper->Acl->allow(null);
        $this->_ID = $this->_helper->config()->webservice->google->username;
		$this->_pword = $this->_helper->config()->webservice->google->password;
    }
    
    public function metricsAction(){
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_HOSTNAME  			
    		)
    		);
    	$analytics->setMax(10);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_SPEED_AVG_PAGE_LOAD_TIME);
    	switch($this->_getParam('segment')){
    		case 'mobile':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    			break;
    		case 'tablet':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_TABLET_TRAFFIC);
    			break;
    		default:
    			break;
    	}
    	$this->view->results = $analytics->getData();
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_AVG_DOMAIN_LOOKUP_TIME
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_HOSTNAME  			
    		)
    		);
    	$analytics->setMax(10);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_AVG_DOMAIN_LOOKUP_TIME);
    	switch($this->_getParam('segment')){
    		case 'mobile':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    			break;
    		case 'tablet':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_TABLET_TRAFFIC);
    			break;
    		default:
    			break;
    	}
    	$this->view->lookup = $analytics->getData();
    }
    
    public function mapAction(){
    	
    }
    
    public function continentAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_CONTINENT    			
    		)
    		);
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	switch($this->_getParam('segment')){
    		case 'mobile':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    			break;
    		case 'tablet':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_TABLET_TRAFFIC);
    			break;
    		default:
    			break;
    	}
    	$this->view->results = $analytics->getData();
    }
    
    public function countryAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_COUNTRY    			
    		)
    		);
    	$analytics->setMax(120);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	switch($this->_getParam('segment')){
    		case 'mobile':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    			break;
    		case 'tablet':
    			$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_TABLET_TRAFFIC);
    			break;
    		default:
    			break;
    	}
    	$this->view->results = $analytics->getData();
    }
    
    public function mobileAction()
    {
    	$analytics = new Pas_Analytics_Gateway($this->_ID, $this->_pword);
    	$analytics->setProfile(25726058);
    	$timeframe = new Pas_Analytics_Timespan($this->_getParam('timespan'));
    	$dates = $timeframe->getDates();
    	$analytics->setStart($dates['start']);
    	$analytics->setEnd($dates['end']);
    	$analytics->setMetrics(array(
    		Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS
    		)
    		);
    	$analytics->setDimensions(array(
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_BRANDING,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_INFO,
    		Zend_Gdata_Analytics_DataQuery::DIMENSION_MOBILE_DEVICE_MODEL,   			
    		)
    		);
    	$analytics->setMax(500);
    	$analytics->setSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS);
    	$analytics->setSortDirection(true);
    	$analytics->setSegment(Pas_Analytics_Gateway::SEGMENT_MOBILE_TRAFFIC);
    	$this->view->results = $analytics->getData();
    }
    
}