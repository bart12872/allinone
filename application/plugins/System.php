<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

/** 
 * @author jhouvion
 * 
 * initilise le routage des crons
 * 
 */

class Application_Plugin_System extends Zend_Controller_Plugin_Abstract {
	
	/**
	 * logger de la tache planifié
	 * 
	 * @var unknown_type
	 */
	protected $_logger;
	
	/**
	 * chemin de fichier de log
	 * 
	 * @var unknown_type
	 */
	protected $_filename;
	
	/**
	 * inititlisation du fichier pid (non-PHPdoc)
	 * 
	 * @see Zend_Controller_Plugin_Abstract::routeStartup()
	 */
	public function routeStartup(Zend_Controller_Request_Abstract $request) {
		
		// recuperation des element
		$controller = Zend_Registry::getInstance()->get('controller');
		$action = Zend_Registry::getInstance()->get('action');
		$token = Zend_Registry::getInstance()->get('token');
		$module = Zend_Registry::getInstance()->get('module');
		
		// si aucun token, on en crée un
		$token = empty($token) ? 'system_' . base64_encode(date('U') . $module . $controller . $action . date('U')) : $token;
	}
	
	/**
	 * routage de la cron
	 * 
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::routeShutdown()
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		
		// recuperation des element
		$controller = Zend_Registry::getInstance()->get('controller');
		$action = Zend_Registry::getInstance()->get('action');
		$module = Zend_Registry::getInstance()->get('module');
		
  
		// gestion des plugin
		$directory = '';
		if ($plugin = Zend_Registry::getInstance()->get('plugin')) {
		    
		    $request->setParam('extra_controller', $controller);
		    $request->setParam('extra_module', $plugin);
		    $request->setParam('extra_action', $action);
		    
		    // chemin specifique pour les cron du plugin
		    $directory .= DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action;
		    
		    $module = getregistry('extra.module');
		    $controller = getregistry('extra.controller');
		    $action = getregistry('extra.action');
		}
		
		// on set les element du routage
		$request->setParam('module', $module)->setModuleName($module);
		$request->setParam('controller', $controller)->setControllerName($controller);
		$request->setParam('action', $action)->setActionName($action);
		
		// definition du chemin du fichier de log
    	$file = getregistry('directory.log') . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . $directory . '.log';
    	
    	// si le repertoir n'existe pas, on essaie de le créée
    	if (!is_dir($dir = dirname($file))) {if(!rmkdir($dir)) {throw new Exception('Impossible de créer le repertoire du fichier de log: ' . $dir);};}
    	
    	//suppression du fichier de log s'il exist
    	if (is_file($file)) {@unlink($file);}
    	
    	// création du redacteur
    	// inscription des droits pour les fichier
    	$redacteur = new Zend_Log_Writer_Stream($file);
		$this->_logger = new Zend_Log($redacteur);
		
		// on inscrit le demmarrage de la cron
		$this->_logger->info('################################');
		$this->_logger->info('#### ' . $controller);
		$this->_logger->info('#### ' . $action);
		$this->_logger->info('################################');
		$this->_logger->info('#### Démarrage du processsus ');
		$this->_logger->info('#### fichier de log =>  ' . str_replace(getregistry('directory.log'), '', $file));
		$this->_logger->info('################################');
		
		//on specifie le fichier comme inscriptible
		@chmod($file, 0775);
		
		// on charge le chemin du fichier de log
		$this->_filename = $file;
	}
	
	/**
	 * une fois le traiteemnt terminé, on efface le fichier de lock(non-PHPdoc)
	 * 
	 * @see Zend_Controller_Plugin_Abstract::postDispatch()
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request) {
		
		// inscription du log de fin de proccesus
		$this->_logger->info('################################');
		$this->_logger->info('#### Fin de processus');
		$this->_logger->info('################################');
	}
	
	/**
	 * @return the $_logger
	 */
	public function getLogger() {return $this->_logger;}
}