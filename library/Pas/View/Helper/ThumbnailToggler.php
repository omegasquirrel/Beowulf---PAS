<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * ResultsQuantityChooser helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_ThumbnailToggler extends Zend_View_Helper_Abstract{
	
	
	protected $_request; 
	
	
	public function __construct(){
		$this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	}
	/**
	 * 
	 */
	public function thumbnailToggler() {
	$html = '<div>Only results with images: ';
	$active = 'success';
	$off = 'inverse';
	$thumbnail = $this->_request['thumbnail'];
	$onRequest = $this->_request;
	$onRequest['thumbnail'] = 1; 
	$offRequest = $this->_request;
	unset($offRequest['thumbnail']);
	
	if(!is_null($thumbnail)){
	$html .= '<a class="btn btn-small btn-' . $active . '" href="' . $this->view->url($onRequest,'default',true) .'">on</a> ';
	$html .= '<a class="btn btn-small btn-' . $off . '" href="' . $this->view->url($offRequest,'default',true) .'">off</a>';
	} else {
	$html .= '<a class="btn btn-small btn-' . $off . '"  href="' . $this->view->url($onRequest,'default',true) .'">on</a> ';
	$html .= '<a class="btn btn-small btn-' . $active . '"  href="' . $this->view->url($offRequest,'default',true) .'">off</a>';
	}
	$html .= '</div>';
	return $html;
	}
	
	
	
	
}

