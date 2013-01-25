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
    }
}

