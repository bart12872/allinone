<?php

/**
 * 
 * Permet d'avoir un layout par module
 * 
 *
 */
class Application_Plugin_Layout extends Zend_Controller_Plugin_Abstract {
	
	/**
	 * set le path des script layout
	 *
	 * @param unknown_type $module
	 */
	protected function _setPath($module = '') {
		Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . $module . '/views/layouts/');
	}
	
	/**
	 * passe en mode layout general
	 * 
	 */
	public function setGeneralPath() {$this->_setPath();return $this;}

	/**
	 * set le module
	 * 
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::dispatchLoopStartup()
	 */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
    	$this->_setPath($this->getRequest()->getModuleName());
    }
}
