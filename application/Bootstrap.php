<?php
require_once 'Sea/Application/Bootstrap/Bootstrap.php';

class Bootstrap extends Sea_Application_Bootstrap_Bootstrap {
	
	/**
	 * chargement des fichier d'initialisatyion
	 * 
	 * @param unknown_type $path
	 * @throws Sea_Exception
	 */
	protected function _loadIniFiles($path) {
		
		// on verifie que le repertoire existe
		if (!realpath($path)) {throw new Sea_Exception('Le repertoire %s n\'existe pas', $path);}
		
		$explore = new Sea_Explorer($path);
    	
    	foreach($explore->listFiles(false, array('ini')) as $file) {
    		$basename = basename($file);// recuperation du nom du fichier
    		
    		if(!$basename) {throw new Sea_Exception('Impossible de charger le fichier : %s', $file);}
    		if (in_array($basename, array('application.ini'))) continue;// on ne retraite pas le fichier de constant
    		
    		// recuperation du namespace
    		$namespace = pathinfo($file, PATHINFO_FILENAME);
    		
    		// si le namespace existe deja on recupere les donnée qui sont dedans
    		$data = new Zend_Config_Ini($file, APPLICATION_ENV);
    		
    		// inscription des donnée
    		Zend_Registry::set($namespace, new Zend_Config(array_merge_recursive(Zend_Registry::isRegistered($namespace) ? Zend_Registry::get($namespace)->toArray() : array(), $data->toArray())));
    	}
	}

	/**
     * inscription des class sea et zend a l'autoloader
	 * 
     */
    public function _initAutoloader() {
    	$autoloader = Zend_Loader_Autoloader::getInstance()->registerNamespace(array('Sea_', 'SeaX_'));
    }
    
 	/**
     * Debug Mode en fonction du display_errors de PHP
     * 
     * ! Attention ! : Il est nécessaire d'initialiser le paramètre 'display_errors' dans le vhost et par conséquent
     * 				il est fortement déconseillé de faire un ini_set('display_errors', [...]) dans le code!
     * 
     */
    public function _initDebugMode() {define("DEBUG_MODE", ini_get('display_errors'));}
    
    /**
     * charge les plugin de zend
     * 
     */
    public function _initRegisterPlugin() {
    	
    	// recuperation du controller frontale
    	$front = Zend_Controller_Front::getInstance();
    	
		if (!is_cron()) {
        } else {
			$front->registerPlugin(new Application_Plugin_System());// chargement du module de disptach des taches system
		}
    }
    
	/**
     * Chargement des fichiers ini
     * 
     */
    public function _initIniFiles() {
    	
    	$this->_loadIniFiles(APPLICATION_PATH .'/configs');
    	
    	// récuperation du controller frontale
    	$front = Zend_Controller_Front::getInstance();
    	
    	// recuperation des ressource
    	$option = $this->getOption('resources');

    	foreach((array) $option['modules'] + array($front->getDefaultModule()) as $module) {

    		// construction de recuperation des fichier ini
			$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . $module .  DIRECTORY_SEPARATOR . 'configs';

			// si le repertoire exitse, on charge les fichiers
			if (realpath($path)) {$this->_loadIniFiles($path);}
    	}
    }
}

