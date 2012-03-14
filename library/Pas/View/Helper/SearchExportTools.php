<?php
/** View helper for generating the links for exporting data
 * @category Pas
 * @package Pas_View_Helper
 * @since 14/3/2012
 * @copyright Daniel Pett
 * @author dpett
 * @version 1
 * @license GNU Public
 */

/**
 * SearchExportTools helper
 *
 * @uses viewHelper Pas_View_Helper
 * @uses viewHelper Zend_View_Helper_Url
 */
class Pas_View_Helper_SearchExportTools extends Zend_View_Helper_Abstract {
	
	/** The user object
	 * 
	 * @var unknown_type
	 */
	protected $_user;
	
	/** Roles allowed to see the download links
	 * 
	 * @var unknown_type
	 */
	protected $_allowed = array('flos','member','fa','admin','treasure');
	
	/** Construct the user object
	 * 
	 */
	public function __construct(){
		$user = new Pas_User_Details();
		$this->_user = $user->getPerson();
	}
	
	/** Generate authenticated data
	 * @access protected
	 * @return string
	 */
	protected function _generateHtml(){
	$params = Zend_Controller_Front::getInstance()->getRequest()->getUserParams();
	unset($params['controller']);
	unset($params['action']);
	unset($params['page']);
	
	$kmlRoute = array_merge($params,array('controller' => 'ajax','action' => 'kml'));
	$csvRoute = array_merge($params,array('controller' => 'ajax','action' => 'csv'));
	$gisRoute = array_merge($params,array('controller' => 'ajax','action' => 'gis'));
	$heroRoute = array_merge($params,array('controller' => 'ajax','action' => 'her'));

	$class = 'btn btn-small';
	$html = ' <a class="'. $class . '" href="';
	$html .= $this->view->url($kmlRoute,null,false);
	$html .= '">Export all results as KML <i class="icon-download-alt"></i></a> ';
	$html .= '<a class="' . $class . '" href="';
	$html .= $this->view->url($csvRoute	,null,false);
	$html .= '">Export as CSV <i class="icon-download-alt"></i></a> ';
//	$html .= '<a class="' . $class . '" href="#">Export for HER import <i class="icon-download-alt"></i></a>';
//	$html .= '<a href="#" class="' . $class . '">Export for GIS <i class="icon-download-alt"></i></a>';
	return $html;	
	}

	/** Create the unauthenticated message
	 * @access protected
	 * @return string
	 */
	protected function _generateHtmlMessage(){
		$html = '<a class="btn btn-info btn-small" href="';
		$html .= $this->view->url(array('module' => 'users'),null,true);
		$html .= '">Login or register so you can export data</a>';
		return $html;
	}
	
	/** Create the correct html rendering based on user roles and identity
	 * @access public
	 * @return string
	 */
	public function searchExportTools() {
		if(in_array($this->_user->role, $this->_allowed)){
			return $this->_generateHtml();
		} else {
			return $this->_generateHtmlMessage();
		}
	}
	
	
}

