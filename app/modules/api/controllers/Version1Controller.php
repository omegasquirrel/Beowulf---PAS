<?php

class Api_Version1Controller extends REST_Controller
{


	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
    }
    
   
	
	public function indexAction(){
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler('beowulf');
	$context = $this->_helper->contextSwitch->getCurrentContext();
	$fields = new Pas_Solr_FieldGeneratorFinds($context);
	$search->setFields($fields->getFields());
	$search->setParams($params);
	$search->execute();
	$this->view->paginator = $this->createPagination($search->_createPagination());
	$this->view->stats = $search->_processStats();
	$this->view->results = $search->_processResults();
	$this->_response->ok();
    }
    
    
    private function createPagination($paginator){
    	$pagination = array(
    	'currentPage' => $paginator->getCurrentPageNumber(),
    	'totalResults' => $paginator->getTotalItemCount(), 
    	'resultsPerPage' => $paginator->getItemCountPerPage()
    	);
    	return $pagination;
    }
    
	public function headAction()
    {
    	$this->_response->ok();
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
    	$this->getResponse()
            ->appendBody("From getAction() returning the requested article");
            $this->_response->ok();
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        $this->_response->unavailable();
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
        $this->_response->unavailable();
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
}