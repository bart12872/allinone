<?php
/**
 * gestion d'un singleton
 * 
 * 
 */

trait Trait_Singleton {
 	
	/**
	 * instance de la classe
	 * 
	 * @var unknown_type
	 */
    private static $_instance;

	/**
     * coinstrcuteur du singleton
     * 
     * @return Sea_Application_Model_ACL_Abstract
     */
    static function getInstance() {
        if (!isset(self::$_instance)) {  
        	$class = get_called_class();// récuperation de la class a créer
        	self::$_instance = new $class();
        }
        return self::$_instance;
    }
}