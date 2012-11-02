<?php
class IndexController extends Pas_Controller_Action_Admin
{
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
	}



	public function indexAction() {

	$content = new Content();
	$this->view->contents = $content->getFrontContent('index');

	$quotes = new Quotes();
	$this->view->quotes  = $quotes->getValidQuotes();

	$news = new News();
	$this->view->news = $news->getHeadlines();
        $form = new CombinedForm();
        $form->setAttrib('class', 'navbar-search pull-right');
        $this->view->form = $form;
        $form->removeElement('thumbnail');
        $form->removeElement('submit');
        $form->q->removeDecorator('label');
        $form->q->setAttrib('class','input-large');
        if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
        unset($params['csrf']);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('index','results','search',$params);
	} else {
	$form->populate($form->getValues());
	}
	}

	}

}