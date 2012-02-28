<?php
class Api_V1Controller extends Zend_Rest_Controller
{

	public function init() {
		$this->_helper->acl->allow('public',null);
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContext('index', array('xml','json'))->initContext();
 		$contextSwitch->setAutoDisableLayout(true);
		$this->_helper->viewRenderer->setNeverRender();	
		$this->view->success = "true";
		$this->view->version = "1.0";
		$this->_helper->layout->disableLayout();
	}
 
    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */ 
    public function indexAction()
    {
	$params = $this->_getAllParams();
	$params['show'] = 50;
	$params['format'] = 'turtle';
	$search = new Pas_Solr_ExportHandler('beocontent');
	$search->setFields(array('*'));
	$search->setParams($params);
	$response = $search->execute();
	$header = $search->getHeader();
	$this->getResponse()->setHeader('Content-type', $header);
	echo $response;
	}
 
    public function listAction()
    {
        $this->_forward('index');
    }
 
    public function getAction()
    {
		$this->_forward('index');
    }
 
    public function newAction() {   	
		$this->_forward('index');
    }
    public function postAction() {
		$this->_forward('index');
    }
    public function editAction() {    	 
		$this->_forward('index');
    }
    public function putAction() {
		$this->_forward('index');
    } 
    public function deleteAction() {
		$this->_forward('index');
    }
}