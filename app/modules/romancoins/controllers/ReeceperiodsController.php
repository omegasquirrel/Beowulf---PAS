<?php
/** Controller for displaying Roman reece periods
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_ReeceperiodsController extends Pas_Controller_Action_Admin {
	
	protected $_reeces;
	
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('period',$contexts)
		->initContext();
	$this->_reeces = new Reeces();
    }
    
	/** Set up the index page
	*/	
	public function indexAction() {
	$this->view->reeces = $this->_reeces->getReeceTotals();
	}
	/** Set up the individual period
	*/		
	public function periodAction() {
	if($this->_getParam('id',false)) {
	$id = (int)$this->_getParam('id');
	$this->view->periods = $this->_reeces->getReecePeriodDetail($id);
	$emperors = new Emperors();
	$this->view->reeces = $emperors->getReeceDetail($id);
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getRevTypeReece($id);    
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}