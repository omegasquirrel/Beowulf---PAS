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
    $user = new Pas_UserDetails();
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
    $params = $this->_getAllParams();
    $params['createdBy'] =  $this->_getDetails()->id;
    $search = new Pas_Solr_Handler('beowulf');
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
    }

    /** Finds recorded by an institution assigned to the user
     *
    */
    public function myinstitutionAction() {
    $params = $this->_getAllParams();
    $params['institution'] =  $this->_getDetails()->institution;
    $search = new Pas_Solr_Handler('beowulf');
    $search->setParams($params);
    $search->execute();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();

    }
    /** Display all images that a user has added.
     *
     */
    public function myimagesAction() {
    $params = $this->_getAllParams();
    $params['createdBy'] = $this->_getDetails()->id;
    $search = new Pas_Solr_Handler('beoimages');
    $search->setFields(array('id','identifier','objecttype','title',
        'broadperiod','imagedir','filename'));
    $search->setFacets(array('broadperiod','county'));
//    $search->setHighlights(array('broadperiod'));
    $search->setParams($params);
    $search->execute();
//    $search->_processFacets();
//    $search->getHighlights();
    $this->view->paginator = $search->_createPagination();
    $this->view->results = $search->_processResults();
    }
}