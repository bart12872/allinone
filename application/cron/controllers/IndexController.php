<?php

class Cron_IndexController extends Application_Model_Controller_System
{
	
	/**
	 * test du moniteur
	 * 
	 */
 	public function monitorAction() {
    	
    	// recupération des argument
	    $token = Zend_Registry::getInstance()->get('token');
	    
		$logger = $this->getLogger();
		
		$logger->info( '###### Debut processus  ' . $token);
   		for ($i = 0; $i <= 5 ; $i++) {echo $i ;$logger->info( 'i wait ' . $i . ' second');sleep(1);}
   		$logger->err( 'on Test une erreur');
   		
    }
}