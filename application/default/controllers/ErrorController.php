<?php

class ErrorController extends Sea_Controller_Action {
	
	/**
	 * par l'index on redirige sur la page d'erreur
	 * 
	 */
	public function indexAction() {$this->_forward('error');}
	
	public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
      
		$previous = $this->_request->getServer('HTTP_REFERER');
		$host = $this->_request->getScheme() . '://' . $this->_request->getServer('HTTP_HOST');
        $request = $host . $this->_request->getRequestUri();
        $url = $this->view->url();
        $urllen = mb_strlen($url);
        
        if ($url[$urllen - 1] == '/') {$url = substr($url, 0, $urllen - 1);}
        
        $path = parse_url($url, PHP_URL_PATH);
        $pathinfo = pathinfo($path);
        $extension = empty($pathinfo["extension"]) ?  null : $pathinfo["extension"];
        $rest = str_replace($host . $path, "", $request);
        $url = $host . $url . $rest;
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

            	// 404 error -- controller or action not found
                $this->view->message = 'Page not found';
                $this->__('error', '404');
                $this->getResponse()->setHttpResponseCode(404);
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                break;
            default:
                // Si l'ereeur n'est pas defini on met par default une erreur 500
                if ($this->getResponse()->getHttpResponseCode() == 200) {$this->getResponse()->setHttpResponseCode(500);}
                $this->view->message = 'Une erreur est survenue : ' . $this->getResponse()->getHttpResponseCode();
                $this->__('error', $this->getResponse()->getHttpResponseCode());
                
                break;
        }
        
        $error = array();
        foreach($errors->exception->getTrace() as $row) {
        	$row['class'] = empty($row['class']) ? '' : $row['class'];
        	$row['line'] = empty($row['line']) ? '' : $row['line'];
        	$row['function'] = empty($row['function']) ? '' : $row['function'];
        	$row['file'] = str_replace(APPLICATION_ROOT, '', realpath($row['file']));
        	if (preg_match("#library/.*#", $row['file'], $match)) {$row['file'] = $match[0];}
        	$row['file'] = empty($row['file']) ? '' : $row['file'];
        	$error[] = $row;
        }
        
        // tableau des trace
        $table = new Application_Model_Datatable($error);
        $table->setItemCountPerPage();
        $table->addText('Ligne', 'line')->align('left');
        $table->addText('Fonction', 'function')->align('left');
        $table->addText('Class', 'class')->align('left');
        $table->addText('Fichier', 'file')->align('left');
        $this->__('trace', $table);
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {$this->view->exception = $errors->exception;}
        
        // on descative le layout 
		$this->_helper->layout->disableLayout();
        $this->view->request = $errors->request;
	}
}