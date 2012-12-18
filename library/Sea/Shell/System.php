<?php
require_once 'Sea/Shell.php';

/** 
 * @author jhouvion
 * 
 * 
 */
class Sea_Shell_System extends Sea_Shell {
	
	/**
	 * initialisation
	 * 
	 * (non-PHPdoc)
	 * @see Sea_Shell::init()
	 */
	public function init($controller = false, $action = false, $module = false, $plugin = false, $token = false) {
		
		//paramètrage
		$this->addArg('-c', $controller);// controller
		$this->addArg('-a', $action);// action
		if ($module) {$this->addArg('-m', $module);}// module
		if ($plugin) {$this->addArg('-p', $plugin);}// plugin
		$this->_command = APPLICATION_PATH . '/cron_launcher';// executable
		$this->addArg('-e', APPLICATION_ENV);// environnement
		
		// gestion du token -- permet de retrouver le processus pour echange avec client
		$this->addArg('-t', $token ? $token : base64_encode($controller . $action . date('U')));
		
		// gestion des paramètres supplementaires
		if (func_num_args() > 5) {foreach((array) array_slice( func_get_args(), 5) as $key => $value) {$this->addArg($key, $value);}}
	}
}

?>