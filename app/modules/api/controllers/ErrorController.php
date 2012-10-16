<?php
/**
 * RESTful ErrorController
 *
 **/
class Api_ErrorController extends REST_Controller
{
	
public function init() {
		$this->_helper->acl->allow('public',null);
		$this->view->version = "Api Version 1.0";
		$this->view->host = $this->view->serverUrl();
		$this->view->author = 'Daniel Pett';
		$this->view->licence = 'CC BY-SA';
		$this->view->urlCalled = $this->view->curUrl();
		$this->view->documentation = $this->view->serverUrl() . '/api/';
		$this->view->timeStamp = Zend_Date::now()->toString('yyyy-MM-ddTHH:mm:ssZ');
		$this->_helper->layout->disableLayout();
	}
    public function errorAction()
    {
        if ($this->_request->hasError()) {
            $error = $this->_request->getError();
            $this->view->message = $error->message;
            $this->getResponse()->setHttpResponseCode($error->code);
            return;
        }

        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->view->message = 'Page not found';
                $this->getResponse()->setHttpResponseCode(404);
                break;

            default:
                // application error
                $this->view->message = 'Application error';
                $this->getResponse()->setHttpResponseCode(500);
                break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception->getMessage();
        }
    }

    /**
     * Catch-All
     * useful for custom HTTP Methods
     *
     **/
    public function __callAction()
    {
    }

    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * GET Action
     *
     * @return void
     */
    public function getAction()
    {
    }

    /**
     * POST Action
     *
     * @return void
     */
    public function postAction()
    {
    }

    /**
     * PUT Action
     *
     * @return void
     */
    public function putAction()
    {
    }

    /**
     * DELETE Action
     *
     * @return void
     */
    public function deleteAction()
    {
    }
}
