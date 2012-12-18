<?php

require_once ('application/models/Controller/System.php');
class Cron_BinsearchController extends \Application_Model_Controller_System {
	
	/**
	 * charge les rss de binsearch
	 */
	public function binsearchAction() {
		
		$logger = $this->getLogger();// récupération du logger
		
		$db = getconnection();// récupération de la connexion
		
		$logger->info('Chargement de la gestion des rss binsearch');
    	$binsearch = new Model_Binsearch();
    	
    	$select = $db->select()->from('binsearch_group')->where('active = ?', 1);
    	
		foreach($db->fetchAll($select) as $row) {
			$logger->info('Traitement de ' . $row['binsearch_group_label']);
			try {$binsearch->importCollection($row['binsearch_group_id']);} catch (Exception $e) {$logger->err($e->getMessage());}
		}
	}
}

?>