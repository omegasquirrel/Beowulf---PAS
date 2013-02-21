<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * AnalyticsLink helper
 *
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_AnalyticsLink extends Zend_View_Helper_Abstract {
	
	const SLASH = '/';
	
	public function getRole(){
		$user = new Pas_User_Details();
		return $user->getPerson()->role;
	}
	/**
	 * 
	 */
	public function analyticsLink() {
		return $this;
	}
	
	private function getCurUrl(){
		return $this->view->curUrl();
	}
	
	private function getPath(){
		$path = parse_url($this->getCurUrl(), PHP_URL_PATH); 
		return  self::SLASH . substr($path, 1);
	}
	
	private function encodePath()
	{
		$raw = base64_encode($this->getPath());
		return $raw;
	}
	
	private function url(){
		if($this->getRole()){
		$params = array(
			'module' 		=> 'analytics',
			'controller' 	=> 'content',
			'action'		=> 'page',
			'url'			=> rawurlencode($this->encodePath())
		);
		$url = $this->view->url($params, 'default', true);
		$html = '<a rel="nofollow" class="btn" href="' . $url . '">View analytics <i class="icon-signal"></i></a>';
		return $html;
		} else {
			return '';
		}
	}
	
	public function __toString()
	{
		return $this->url();
	}
}

