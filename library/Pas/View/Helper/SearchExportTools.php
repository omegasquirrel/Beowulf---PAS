<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * SearchExportTools helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_SearchExportTools extends Zend_View_Helper_Abstract {
	
	protected $_user;
	
	protected $_allowed = array('flos','member','fa','admin','treasure');
	
	public function __construct(){
		$user = new Pas_User_Details();
		$this->_user = $user->getPerson();
	}
	
	protected function _generateHtml(){
		
	}
	
	protected function _generateHtmlMessage(){
		$html = '<a href="/users/register/" title="Register and get better access"';
		$html .= ' class="btn btn-small btn-info">If you register you can export data</a>';
		return $html;
	}
	/**
	 * 
	 */
	public function searchExportTools() {
		if(in_array($user->role,$this->_allowed)){
			return $this->_generateHtml();
		} else {
			return $this->_generateHtmlMessage();
		}
	}
	
	
}

