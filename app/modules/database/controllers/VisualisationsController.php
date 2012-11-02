<?php
/** Controller for displaying information about coins
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_VisualisationsController extends Pas_Controller_Action_Admin {

    public function init() {	
    $this->_helper->_acl->allow(NULL);
    $this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/d3.v2.js',$type='text/javascript');
    }
    
    /** Redirect as no direct access to the coins index page
    */
    public function indexAction() {
    }
    
    public function getData($params){
    	
    }
    
    public function findspotsAction(){
    	
    }
    
    public function objectsAction(){
    }

}