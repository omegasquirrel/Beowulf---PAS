<?php

class Api_V1Controller 
	extends REST_Controller {

	public function init() {
		$this->_helper->acl->allow('public',null);
		$this->view->version = "Api Version 1.0";
		$this->view->host = $this->view->serverUrl();
		$this->view->author = 'Daniel Pett';
		$this->view->licence = 'CC BY-SA';
		$this->view->urlCalled = $this->view->curUrl();
		$this->view->documentation = $this->view->serverUrl() . '/api/';
		$this->_helper->layout->disableLayout();
	}
 
    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */
    public function indexAction()
    {
        $this->view->message = 'The index action has been called. Nothing to see here.';
        $this->_response->ok();
    }

    /**
     * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
     */
    public function headAction()
    {
        $this->view->message = 'Head action has been called';
        $this->_response->ok();
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
    	throw new Exception('boo',400);
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	
	$search->setFields($fields->getFields());
    
	$search->setFacets(array(
    'objectType','county', 'broadperiod',
    'institution', 'rulerName', 'denominationName', 
    'mintName', 'materialTerm', 'workflow'));

	$search->setParams($params);
	$search->execute();
	$this->view->paginator = $this->_getPaginations($search->_createPagination());
	
	$this->view->stats = $search->_processStats();
	$this->view->records = $search->_processResults();
        $this->_response->ok();
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        $this->view->params = $this->_request->getParams();
        $this->view->message = 'Resource Created';
        $this->_response->created();
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
        $id = $this->_getParam('id', 0);

        $this->view->id = $id;
        $this->view->params = $this->_request->getParams();
        $this->view->message = sprintf('Resource #%s Updated', $id);
        $this->_response->ok();
    }

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction()
    {
        $id = $this->_getParam('id', 0);

        $this->view->id = $id;
        $this->view->message = sprintf('Resource #%s Deleted', $id);
        $this->_response->ok();
    }
    
    public function _getPaginations($paginator){
    	$meta = array(
    	 'currentPage' => $paginator->getCurrentPageNumber(), 
    	 'totalResults' => $paginator->getTotalItemCount(), 
    	 'resultsPerPage' => $paginator->getItemCountPerPage()
    	);
    	return $meta;
    }
}