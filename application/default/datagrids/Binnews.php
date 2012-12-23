<?php
class Default_Datagrid_Binnews extends Application_Model_Datatable {
	
	public function init() {
		
		$db = getconnection();// récupération de la connexion
		$s = $db->select()	->from('binnews')
							->where('title NOT LIKE ?', '%*** MOT DE PASSE ***%')
							->group('binnews.binnews_id')
							->order('pubDate DESC');
       	
       	// creation de la liste
        $this->setJqueryParam('bFilter', true);
        
		$this->addText('Titre', 'title' )->width('400px');
		$this->addText('Langue', 'lang' );
		$this->addText('Taille', 'size' );
	
		//FILTRE
		$search = $this->getRequestParam('sSearch');// recuperationd e la recherche
		if (!empty($search) && strlen($search) > 3) {$s->where('title LIKE ?', '%' . $search . '%');}
		$this->setAdapter($s);
		
		$this->setItemCountPerPage(30);
		$this->setJqueryParam('bFilter', true);
	}
}