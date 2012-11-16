<?php
/** Controller for displaying Roman articles within the coin guide
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_CalendarController extends Pas_Controller_Action_Admin {
	
	
	
	/** Set up the ACL and contexts
	*/	
	public function init() {	
	$this->_helper->_acl->allow('flos',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	
	
	/** Display index pages for the individual
	*/	
	public function indexAction() {	
    $gcal = new Pas_Calendar_Mapper();
    $this->view->listFeed = $gcal->getCalendarList();
    $this->view->eventFeed = $gcal->getEventFeed();
	}
	
	public function eventAction()
	{
		$id = urldecode($this->_getParam('id'));
		$gcal = new Pas_Calendar_Mapper();
		$this->view->event = $gcal->getEvent($id);
	}
	
}