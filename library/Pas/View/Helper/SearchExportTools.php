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
 * @todo assert ACL?
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
	protected $_allowed = array('flos','member','fa','admin','treasure', 'research', 'hero');

	/** Construct the user object
	 *
	 */
	public function __construct(){
		$user = new Pas_User_Details();
		$this->_user = $user->getPerson();
	}

        protected function _cleanParams($params){
        if(is_array($params)){
        unset($params['controller']);
	unset($params['action']);
	unset($params['page']);
        return $params;
        } else {
            throw new Pas_Exception_BadJuJu('Parameters have to be an array');
        }
        }
	/** Generate authenticated data
	 * @access protected
	 * @return string
	 */
	protected function _generateHtml(){
	$params = Zend_Controller_Front::getInstance()->getRequest()->getUserParams();
	$params = $this->_cleanParams($params);
        $params['controller'] = 'ajax';
	$kmlRoute = array_merge($params,array('action' => 'kml'));
	$csvRoute = array_merge($params,array('action' => 'csv'));
	$gisRoute = array_merge($params,array('action' => 'gis'));
	$herRoute = array_merge($params,array('action' => 'her'));
        $nmsRoute = array_merge($params,array('action' => 'nms'));
	$class = 'btn btn-small';
	$html = ' <a class="'. $class . '" href="';
	$html .= $this->view->url($kmlRoute, null, false);
	$html .= '">Export all results as KML <i class="icon-download-alt"></i></a> ';
	$html .= '<a class="' . $class . '" href="';
	$html .= $this->view->url($csvRoute, null, false);
	$html .= '">Export as CSV <i class="icon-download-alt"></i></a> ';
	$html .= '<a class="' . $class . '" href="';
	$html .= $this->view->url($herRoute, null, false);
        $html .= '">Export for HER import <i class="icon-download-alt"></i></a>';
//	$html .= '<a href="#" class="' . $class . '">Export for GIS <i class="icon-download-alt"></i></a>';
        if(in_array($this->_user->institution,array('PAS','NMS'))){
        $html .= ' <a class="' . $class . '" href="';
	$html .= $this->view->url($nmsRoute, null, false);
        $html .= '">NMS report format <i class="icon-download-alt"></i></a>';

        }
	return $html;
	}

	/** Create the unauthenticated message
	 * @access protected
	 * @return string
	 */
	protected function _generateHtmlMessage(){
		$html = '<a class="btn btn-info btn-small" href="';
		$html .= $this->view->url(array('module' => 'users'),null,true);
		$html .= '">Login or register so you can export data ';
                $html .= '<i class="icon-download icon-white"></i></a>';
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

