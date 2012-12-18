<?php

require_once ('Zend/Console/Getopt.php');

class CronLaucher {
	
	/**
	 * 
	 * @var Zend_Console_Getopt
	 */
	protected $_console;
	
	/**
	 * 
	 * contient l'instance de l'objet (singleton)
	 * 
	 * @var CronLaucher
	 */
	protected static  $_instance;
	
	
  	/**
	 * @return the Zend_Console_Getopt $_console
	 */
	public function getConsole() {
		return $this->_console;
	}

	/**
	 * @param Zend_Console_Getopt $_console
	 */
	public function setConsole($_console) {
		$this->_console = $_console;
		return $this;
	}

	/**
     * constructeur du singleton
     * 
     * @return CronLaucher
     */
    static function getInstance() {
        
        if (!isset(self::$_instance)) {
        	$class = get_called_class();// récuperation de la class a créer
        	self::$_instance = new $class();
        }
        return self::$_instance;
    }
    
    /**
     * constructeur
     */
    protected function __construct() {
    	
    	$console = new Zend_Console_Getopt('c:a:t:e:');// intitialisation
		$console->setHelp(array('c' => 'Controller','a' => 'Action', 't' => 'Token', 'e' => 'Environement'));// deifinition des l'aide
		$console->parse();// on annalyse la ligne
		    
		//mise en place de l'environnement si passé en paramettre
		if ($e = $console->getOption('e')) {putenv('APPLICATION_ENV=' . $e);}
		
		$this->setConsole($console);
    }
}