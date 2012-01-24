<?php
/** Controller for displaying individual's finds on the database.
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_MyschemeController extends Pas_Controller_Action_Admin {

    /**
    *
    * @var object $_auth
    */
    protected $_auth;

    public function init() {
    $this->_helper->_acl->allow('member',null);
    $this->_auth = Zend_Registry::get('auth');
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()
         ->setAutoDisableLayout(true)
         ->addContext('csv',array('suffix' => 'csv'))
         ->addContext('kml',array('suffix' => 'kml'))
         ->addContext('rss',array('suffix' => 'rss'))
         ->addContext('atom',array('suffix' => 'atom'))
         ->addActionContext('record', array('xml','json','rss','atom'))
         ->addActionContext('index', array('xml','json','rss','atom'))
         ->initContext();
    }

    const REDIRECT = '/database/myscheme/';

    /** Protected function for personal details
     *
     */
    protected function _getDetails() {
    $user = new Pas_User_Details();
    return $user->getPerson();
    }

    /** Redirect as no root access allowed
     *
     */
    public function indexAction() {
    $this->_flashMessenger->addMessage('No access to index page');
    $this->_redirect('/database/');
    }

    /** List of user's finds that they have entered. Can be solr'd
     *
     */
    public function myfindsAction() {
    $form = new SolrForm();
    $this->view->form = $form;

    $params = $this->_getAllParams();

    $search = new Pas_Solr_Handler('beowulf');
    $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county')
    );
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

    if ($form->isValid($form->getValues())) {
    $params = $this->array_cleanup($form->getValues());

    $this->_helper->Redirector->gotoSimple('myfinds','myscheme','database',$params);
    } else {
    $form->populate($form->getValues());
    $params = $form->getValues();
    }
    } else {

    $params = $this->_getAllParams();
    $form->populate($this->_getAllParams());


    }

    if(!isset($params['q']) || $params['q'] == ''){
        $params['q'] = '*';
    }
    $params['createdBy'] =  $this->_getDetails()->id;
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
    }

    private function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','csrf');
    foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    if($key == $match){
            unset($array[$key]);
    }
    }
    }
    return $array;
    }
    /** Finds recorded by an institution assigned to the user
     *
    */
    public function myinstitutionAction() {
    $form = new SolrForm();
    $this->view->form = $form;

    $params = $this->_getAllParams();

    $search = new Pas_Solr_Handler('beowulf');
    $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county',
        )
    );
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

    if ($form->isValid($form->getValues())) {
    $params = $this->array_cleanup($form->getValues());

    $this->_helper->Redirector->gotoSimple('myinstitution','myscheme','database',$params);
    } else {
    $form->populate($form->getValues());
    $params = $form->getValues();
    }
    } else {

    $params = $this->_getAllParams();
    $form->populate($this->_getAllParams());


    }

    if(!isset($params['q']) || $params['q'] == ''){
        $params['q'] = '*';
    }
    $params['institution'] =  $this->_getDetails()->institution;
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
    }
    /** Display all images that a user has added.
     *
     */
    public function myimagesAction() {
    $form = new SolrForm();
    $form->removeElement('thumbnail');
    $this->view->form = $form;

    $params = $this->_getAllParams();
    $params = $this->_getAllParams();

    $search = new Pas_Solr_Handler('beoimages');
    $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod', 'imagedir',
    	'filename', 'thumbnail', 'old_findID',
    	'county','licenseAcronym','findID')
    );
     if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())
                && !is_null($this->_getParam('submit'))){

    if ($form->isValid($form->getValues())) {
    $params = $this->array_cleanup($form->getValues());

    $this->_helper->Redirector->gotoSimple('myimages','myscheme','database',$params);
    } else {
    $form->populate($form->getValues());
    $params = $form->getValues();
    }
    } else {

    $params = $this->_getAllParams();
    $form->populate($this->_getAllParams());


    }

    if(!isset($params['q']) || $params['q'] == ''){
        $params['q'] = '*';
    }
    $search->setFacets(array('broadperiod','county'));
    $params['createdBy'] = $this->_getDetails()->id;
    $search->setParams($params);
    $search->execute();
    $search->_processFacets();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
    }
}