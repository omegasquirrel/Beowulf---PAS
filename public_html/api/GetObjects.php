<?php
class GetObjects {
public function get($params){
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
	return $search->_processResults();
    }
}