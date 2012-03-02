<?php

class Api_IndexController extends Zend_Controller_Action
{
	protected $higherLevel = array('admin','flos'); 
	protected $researchLevel = array('member','heros','research');
	protected $restricted = array('public');


	public function init() {
		
 		
 		$this->_helper->_acl->allow(null);
    }

public function postDispatch ()
      { 
	$response = $this->getResponse();
    
	if ($this->_renderFooter === true) {
	$response ->insert('footer', $this->view->render('structure/footer.phtml'));
           }
	if ($this->_renderBreadcrumb === true) {
	$response ->insert('breadcrumb', $this->view->render('structure/breadcrumb.phtml'));
           }
	if ($this->_renderSidebar === true) {
	$response ->insert('sidebar', $this->view->render('structure/researchSidebar.phtml'));
           }   
	if ($this->_renderNavigation === true) {
	$response ->insert('navigation', $this->view->render('structure/navigation.phtml'));
           }  
	if ($this->_renderMeta === true) {
	$response ->insert('meta', $this->view->render('structure/meta.phtml'));
           }   
	if ($this->_renderHeader === true) {
	$response ->insert('header', $this->view->render('structure/header.phtml'));
           }   
	if ($this->_renderUserData === true) {
	$response ->insert('userdata', $this->view->render('structure/userdata.phtml'));
           }  
	if ($this->_renderMessages === true) {
	$response ->insert('messages', $this->view->render('structure/messages.phtml'));
           }   
	if ($this->_renderAnalytics === true) {
	$response ->insert('analytics', $this->view->render('structure/analytics.phtml'));
           }   
      } 
		
protected function _noBreadcrumb() {
            $this->_renderBreadcrumb = false;
            return;
       } 

protected function _noFooter () {
            $this->_renderFooter = false;
            return;
       } 
protected function _noSidebar () {
            $this->_renderSidebar = false;
            return;
       } 
protected function _noNavigation () {
            $this->_renderNavigation = false;
            return;
       } 
protected function _noMeta () {
            $this->_renderMeta = false;
            return;
       } 
protected function _noHeader () {
            $this->_renderHeader = false;
            return;
       } 

protected function _noUserData () {
            $this->_renderUserData = false;
            return;
       } 
protected function _noMessages () {
            $this->_renderMessages = false;
            return;
       } 
	   
protected function _noAnalytics () {
            $this->_renderAnalytics = false;
            return;
       } 


protected function getIdentityForForms()
	{
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity())
	{
	$user = $auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	else 
	{
	$id = '3';
	return $id;
	}
	}
	
public function getTimeForForms()
	{
	$dateTime = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
	return $dateTime;
	}
	
	
public function indexAction()
    {
	
 	$content = new Content();
	$this->view->content = $content->getFrontContent('api');
    }
}