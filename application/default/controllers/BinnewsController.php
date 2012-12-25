<?php
class BinnewsController extends Sea_Controller_Action {
	
	/**
	 * list les liens
	 * 
	 */
	public function indexAction() {
		$this->__('list', new Default_Datagrid_Binnews);
	}
	
	/**
	 * charger les flux rss
	 */
	public function chargerAction() {
		
		$validator = ['id' => [['Db_RecordExists', 'binnews_category', 'binnews_category_id']]];
    	$input = new Zend_Filter_Input([], $validator, $this->getRequest()->getParams());
    	if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
		$db = getconnection();// récupération de la connexion
		
		$db = getconnection();// récupération de la connexion
		
    	$select = $db->select()->from('binnews_category');
    	if ($input->id) {$select->where('binnews_category_id = ?', $input->id);}
    	else{$select->where('active = ?', 1);}
		
    	$error = '';
		foreach($db->fetchPairs($select) as $k => $v) {
			
			$flux = new Zend_Feed_Rss(sprintf(getregistry('rss.url', 'binnews'), $k));
			foreach($flux as $e) {
				try {
					$data = ['title' => $e->title(), 'link' => $e->link(), 'description' => $e->description(), 'pubDate' => new Zend_Db_Expr($db->quoteInto('STR_TO_DATE(?, "%a, %d %b %Y %T")', $e->pubDate()))];
					if (preg_match(getregistry('regex', 'binnews'), $e->description(), $match)) {
	
						// si l'enrgistrement n'existe pas, on l'ajoute
						if (!$db->fetchOne($db->select()->from('binnews', 'binnews_id')->where('binnews_id=?',  $match['id']))) {
							$info = $data + ['binnews_category_id' => $k, 'lang' => trim($match['lang']),'file' => trim($match['file']),'size' => trim($match['size']),'binnews_id' => $match['id']];
							$db->insert('binnews', $info);
						}
					}
				} catch (Exception $e) {$error .= $e->getMessage() . '<br/>';}
			}
			// mise a jour de kla date du denrie refresh
			$db->update('binnews_category', ['refreshed'=> new Zend_Db_Expr('NOW()')], $db->quoteInto('binnews_category_id=?', $k));
		}
		
		if (empty($error)) {
			$this->view->JQuery()->addOnload("refreshDataTable();");
			$this->_simpleNoLayout($this->view->partial('success.phtml'));
		} else {$this->_simpleNoLayout($this->view->partial('error.phtml', ['m' => $error]));}
	}
	
	/**
	 * gestion des categories
	 * 
	 */
	public function categorieAction() {
		$this->__('list', new Default_Datagrid_BinnewsCategory);
	}
	
	/**
	 * formulaire de gestion groupe
	 * 
	 */
	public function editercategorieAction() {
		
    	// validation des paramètres
    	$validator = ['id' => [['Db_RecordExists', 'binnews_category', 'binnews_category_id']]];
    	$input = new Zend_Filter_Input([], $validator, $this->getRequest()->getParams());
    	if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
        
        $form = new Application_Model_FormAjax();
        $form->setAction($this->view->url());
        $form->addText('binnews_category_id', 'ID', true);
        $form->addText('binnews_category_label', 'Nom', true);
        $form->addText('binnews_category_code', 'Code', true);
        $form->addCheckbox('active', 'Active');
        $form->addSubmit('save', 'Enregistrer');
	    
	    if ($this->getRequest()->isPost()) { // si un formulaire est posté ($_POST)
	        
	        // recuperation de la gestion de la table
	        $table = new Application_Model_DbTable_BinnewsCategory();
	        
	    	if($form->isValid($this->getRequest()->getPost())) {// on verifie que le formulaire est correct
	    	
	    		$data = $form->getValues();// recuperation des valeur du formulaire
	    		$db = getconnection();// récupération de la connexion
	    		$db->beginTransaction();// on demarre la transaction
	    
	    		try { // traitement
	    		    
	    		    // mise a jour de l'enregistrement
	    		    if (empty($input->id)) {$table->insert($data);}
	    		    else {$table->update($data, $table->getAdapter()->quoteInto('binnews_category_id=?', $input->id));}
	    		    
	    			$db->commit();// on valide la transaction
					$this->view->JQuery()->addOnload("refreshDataTable();");
	    			$this->_simpleNoLayout($this->view->partial('success.phtml'));
					
			    // enc as d'erreur on annules changement
				} catch (Exception $e) {
					$db->rollBack();
					$this->_simpleNoLayout($this->view->partial('error.phtml', ['m' => $e->getMessage()]));
				}
				return;
	    	}
	    } elseif($input->id) {
	    	$mapper = new Sea_Mapper('Application_Model_DbTable_BinnewsCategory');
	    	$form->populate($mapper->find($input->id));
	    }
		
		$this->_simpleNoLayout($form);
	}
}

?>