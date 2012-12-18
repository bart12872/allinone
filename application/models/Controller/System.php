<?php

require_once ('Zend/Controller/Action.php');

/** 
 * @author jhouvion
 * 
 * 
 */
class Application_Model_Controller_System extends Sea_Controller_Action {

	/**
	 * 
	 * permetbde logger
	 * @var Zend_Log
	 */
	protected $_logger;
	
	/**
	 * initialisation
	 * 
	 * @see Sea_Controller_Action::init()
	 */
	public function init() {
		
		// desctivation du rendu de l'action
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	// recuperation du chemin de fichier de processus
		$this->_logger = Zend_Controller_Front::getInstance()->getPlugin('Application_Plugin_System')->getLogger();
	}
	
	/**
	 * @return the $_logger
	 */
	public function getLogger() {
		return $this->_logger;
	}
}