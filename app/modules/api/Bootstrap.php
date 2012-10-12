<?php
/** Bootstrap for the website to run
*
* @category   Zend
* @package    Zend_Application_
* @subpackage Bootstrap
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author     Daniel Pett
* @version    1.0
* @since      22 September 2011
*/

class Api_Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	

public function _initREST()
{
    $frontController = Zend_Controller_Front::getInstance();

    // register the RestHandler plugin
    $frontController->registerPlugin(new REST_Controller_Plugin_RestHandler($frontController));
	$frontController->registerPlugin(new Pas_Controller_Plugin_Errors()); 
	// set custom request object
    $frontController->setRequest(new REST_Request);
    $frontController->setResponse(new REST_Response);

    // add the REST route for the API module only
    $restRoute = new Zend_Rest_Route($frontController, array(), array('api'));
    $frontController->getRouter()->addRoute('rest', $restRoute);
    // add REST contextSwitch helper
    $contextSwitch = new REST_Controller_Action_Helper_ContextSwitch();
    Zend_Controller_Action_HelperBroker::addHelper($contextSwitch);

    // add restContexts helper
    $restContexts = new REST_Controller_Action_Helper_RestContexts();
    Zend_Controller_Action_HelperBroker::addHelper($restContexts);
}
    
}