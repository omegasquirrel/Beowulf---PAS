<?php
/** Controller for searching for finds on database
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SearchController extends Pas_Controller_Action_Admin {

	protected $_searches;

	protected $_contexts = array(
	'xml', 'rss', 'json',
	'atom', 'kml', 'georss',
	'ics', 'rdf', 'xcs');

	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_searches = new Searches();
	$this->_helper->_acl->allow('public',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('results', array('rss','atom'));
	$her = array('her');
	$herroles = array('hero','flos','admin','fa');
	$role = $this->getAccount();
	if($role){
	$user = $role->role;
	if(in_array($user,array('hero','flos','admin','fa','treasure','member','research'))) {
	$this->_helper->contextSwitch()
		->addContext('csv',array('suffix' => 'csv'))
		->addActionContext('results', array('csv'));
	}
	}

	if($role){
	$user = $role->role;
	if(in_array($user,$herroles)) {
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
		->addContext('hero',array('suffix' => 'hero'))
		->addActionContext('results', array('hero'));
	}
	}
	$this->_helper->contextSwitch()->initContext();

	if(!in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts )) {
	$this->view->googleapikey = $this->_config->webservicegooglemaps->apikey;
	}
	}


	/** Display the basic what/where/when page.
	*/
	public function indexAction() {
	$form = new WhatWhereWhenForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}


	private function array_cleanup( $array ) {
        $todelete = array('submit','action','controller','module','page','csrf');
	foreach( $array as $key => $value ) {
        foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
        }
        }
        return $array;
        }

	/** Generate the advanced search page
	*/
	public function advancedAction(){
	$form = new AdvancedSearchForm(array('disableLoadDefaultDecorators' => true));
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the byzantine search form
	*/
	public function byzantinenumismaticsAction() {
	$form = new ByzantineNumismaticSearchForm();
	$this->view->byzantineform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the early medieval numismatics form
	*/
	public function earlymednumismaticsAction() {
	$form = new EarlyMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the medieval numismatics page
	*/
	public function mednumismaticsAction() {
	$form = new MedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the post medieval numismatics pages
	*/
	public function postmednumismaticsAction() {
	$form = new PostMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}

	/** Display the roman numismatics pages
	*/
	public function romannumismaticsAction() {
	$form = new RomanNumismaticSearchForm();
	$this->view->formRoman = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the iron age numismatics pages
	*/
	public function ironagenumismaticsAction() {
	$form = new IronAgeNumismaticSearchForm();
	$this->view->formIronAge = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Display the greek and roman provincial pages
	*/
	public function greekromanAction() {
	$form = new GreekRomanSearchForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}


	/** Remove multiple values
	 *
	 * @param array $array
	 * @param string $sub_key
	*/
	private function unique_multi_array($array, $sub_key) {
	$target = array();
	$existing_sub_key_values = array();
	foreach ($array as $key=>$sub_array) {
       if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
           $existing_sub_key_values[] = $sub_array[$sub_key];
           $target[$key] = $sub_array;
       }
	}
	return $target;
	}
	/** Display the map of results
	*/
	public function mapAction() {
	$data = $this->_getAllParams();
	$params = array_filter($data);
	$this->view->params = $params;
		unset($params['controller']);
		unset($params['module']);
		unset($params['action']);
		unset($params['submit']);
		unset($params['csrf']);

	$where = array();
        foreach($params as $key => $value) {
            if(!is_null($value)) {
            $where[] = $key . '/' . urlencode($value);
            }
        }
   	$whereString = implode('/', $where);
	$query = $whereString;
	$this->view->query = $query;
	}

	public function saveAction() {
	$form = new SaveSearchForm();
	$form->submit->setLabel('Save search');
	$this->view->form = $form;
	$lastsearch = $this->_searches->fetchRow($this->_searches->select()->where('userid = ?',
	$this->getIdentityForForms())->order('id DESC'));
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	foreach($querystring as $key => $value) {
	$params[$key] = $value;
	}
	$this->view->params = $params;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$insertData = $form->getValues();
	$insertData['searchString'] = $lastsearch->searchString;
	$saved = new SavedSearches();
	$insert = $saved->add($insertData);
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else  {
	$this->_flashMessenger->addMessage('There are problems with your submission.');
	$form->populate($form->getValues());
	}
	}
	}
	/** Email a search result
	*/
	public function emailAction() {
	$user = $this->_helper->identity->getPerson();
	$lastsearch = $this->_searches->fetchRow($this->_searches->select()->where('userid = ?',
	$user->id)->order('id DESC'));
	if($lastsearch) {
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	foreach($querystring as $key => $value) {
	$params[$key] = $value;
	}
	$this->view->params = $params;
	$form = new EmailSearchForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$to[] = array(
	'email' => $form->getValue('email'),
	'name' => $form->getValue('fullname')
	);
	$from[] = array(
	'email' => $user->email,
	'name' => $user->fullname
	);
	$url = array('url' => $params);
	$assignData = array_merge($form->getValues(), $from[0], $url);
	$this->_helper->mailer($assignData,'sendSearch', $to, null, $from);
	$this->_flashMessenger->addMessage('Your email has been sent to ' . $form->getValue('fullname')
	. '. Thank you for sending them some of our records.');
	$this->_helper->Redirector->gotoSimple('results','search','database',$querystring);
	}  else {
	$form->populate($form->getValues());
	}
	}
	}
	}
	/** Display saved searches
	*/
	public function savedsearchesAction() {
	$allowed = array('fa','flos','admin');
	if(in_array($this->getRole(),$allowed)) {
	$private = 1;
	} else {
	$private = NULL;
	}
	if($this->_getParam('by') == 'me'){
	$this->view->data = $this->_searches->getSavedSearches($this->_helper->identity->getPerson()->id,
		$this->_getParam('page'),$private);
	} else {
	$this->view->data = $this->_searches->getSavedSearches(NULL,$this->_getParam('page'), $private);
	}
	}
	/** Display the solr form
	*/
	public function solrAction(){
	$form = new SolrForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = $this->array_cleanup($form->getValues());
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($form->getValues());
	}
	}
	}

	/** Display the index page.
	*/
	public function resultsAction(){
	$params = $this->_getAllParams();
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
	'id','identifier','objecttype',
        'title','broadperiod','description',
        'old_findID','thumbnail', 'county',
        'imagedir','filename'));
        $search->setFacets(array('objectType','county','broadperiod','institution'));
	$search->setParams($params);
	$search->execute();
        $this->view->facets = $search->_processFacets();
	$this->view->paginator = $search->_createPagination();
	$this->view->results = $search->_processResults();

	}
//EOS
}