<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Katiebear
 */
class Analytics_IndexController extends Pas_Controller_Action_Admin {
    
    protected $_ID;
    
    protected $_pword;
    
    protected $_service;

    
    public function init() {
 	$this->_helper->_acl->allow(null); 
	$this->_ID = $this->_helper->config()->webservice->google->username;
	$this->_pword = $this->_helper->config()->webservice->google->password;
	$this->_service = Zend_Gdata_Analytics::AUTH_SERVICE_NAME;
    }
    
    public function indexAction() {
	$client = Zend_Gdata_ClientLogin::getHttpClient($this->_ID, $this->_pword, $this->_service);
	$analytics = new Zend_Gdata_Analytics($client); 
	$query = $analytics->newDataQuery()->setProfileId(25726058) 
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_TIME_ON_SITE)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_NEW_VISITS)
		  ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)
//		  ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_MEDIUM) 
//		  ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE) 
		  ->setStartDate('2012-01-01')   
		  ->setEndDate('2012-12-31')   
		  ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS, true) 
		  ->setMaxResults(50);
	$this->view->result = $analytics->getDataFeed($query);
    }
}

