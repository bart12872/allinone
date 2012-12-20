<?php
class BinsearchController extends \Sea_Controller_Action {
	
	/**
	 * liste les collections trouvé
	 */
	public function indexAction() {
		$this->__('list',new  Default_Datagrid_BinsearchCollection);
	}
	
	/**
	 * liste les groupes binsearch
	 * 
	 */
	public function groupeAction() {
		$this->__('list',new  Default_Datagrid_BinsearchGroup);
	}
	
	/**
	 * formulaire de gestion groupe
	 * 
	 */
	public function editergroupeAction() {
		
    	// validation des paramètres
    	$validator = ['id' => [['Db_RecordExists', 'binsearch_group', 'binsearch_group_id']]];
    	$input = new Zend_Filter_Input([], $validator, $this->getRequest()->getParams());
    	if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
        
        $form = new Application_Model_FormAjax();
        $form->setAction($this->view->url());
        $form->addText('binsearch_group_label', 'Nom', true)->setAttrib('size', '50');
        $form->addText('short_title', 'Diminutif', true)->setAttrib('size', '50');
        $form->addText('rss', 'Limite Rss', true)->addValidator('LessThan', false, getregistry('rss.limit.max', 'binsearch'));
        $form->addCheckbox('active', 'Active', true);
        $form->addSubmit('save', 'Enregistrer');
	    
	    if ($this->getRequest()->isPost()) { // si un formulaire est posté ($_POST)
	        
	        // recuperation de la gestion de la table
	        $table = new Application_Model_DbTable_BinsearchGroup();
	        
	        // on regarde s'il s'agit d'un chargement du formulaire
	        if (!$this->getRequest()->getParam('save', false) && ($id = $this->getRequest()->getParam('id', false))) {
	            $default = $table->find($id);
	            if ($default->valid()){$form->populate($default->current()->toArray());}// on charge les données
	        
	        // sinon on traite le formulaire
	        } else {
		    	if($form->isValid($this->getRequest()->getPost())) {// on verifie que le formulaire est correct
		    	
		    		$data = $form->getValues();// recuperation des valeur du formulaire
		    		$db = getconnection();// récupération de la connexion
		    		$db->beginTransaction();// on demarre la transaction
		    
		    		try { // traitement
		    		    
		    		    // mise a jour de l'enregistrement
		    		    if (empty($input->id)) {$table->insert($data);}
		    		    else {$table->update($data, $table->getAdapter()->quoteInto('binsearch_group_id=?', $input->id));}
		    		    
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
	        }
	    } elseif($input->id) {
	    	$mapper = new Sea_Mapper('Application_Model_DbTable_BinsearchGroup');
	    	$form->populate($mapper->find($input->id));
	    }
		
		$this->_simpleNoLayout($form);
	}
	
	/**
	 * efface un groupe
	 * 
	 */
	public function effacergroupeAction() {
		// validation des paramètres
    	$validator = ['id' => ['presence' => 'required', ['Db_RecordExists', 'binsearch_group', 'binsearch_group_id']]];
    	$input = new Zend_Filter_Input([], $validator, $this->getRequest()->getParams());
    	if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
    	
    	$db = getconnection();// récupération de la connexion
		$db->beginTransaction();
        
        try {
        	 // recuperation de la table
        	$table = new Application_Model_DbTable_BinsearchGroup();
        
	        // mise a jour
	        $table->delete($table->getAdapter()->quoteInto('binsearch_group_id=?', $input->id));
			$db->commit();
	        $this->view->JQuery()->addOnload("refreshDataTable();");
        	$this->_simpleNoLayout($this->view->partial('success.phtml'));
		// enc as d'erreur on annules changement
		} catch (Exception $e) {
			$db->rollBack();
			$this->_simpleNoLayout($this->view->partial('error.phtml', ['m' => $e->getMessage()]));
		}
		return;
	}
	
	
	/**
	 * charge les flux
	 * 
	 */
	public function chargerAction() {
		
		$validator = ['id' => [['Db_RecordExists', 'binsearch_group', 'binsearch_group_id']]];
    	$input = new Zend_Filter_Input([], $validator, $this->getRequest()->getParams());
    	if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
		$db = getconnection();// récupération de la connexion
		
		// Chargement de la gestion des rss binsearch
    	$binsearch = new Application_Model_Binsearch();
    	$select = $db->select()->from('binsearch_group')->where('active = ?', 1);
    	if ($input->id) {$select->where('binsearch_group_id = ?', $input->id);}
    	
    	$error = '';
		foreach($db->fetchAll($select) as $row) {
			try {$binsearch->importCollection($row['binsearch_group_id']);} 
			catch (Exception $e) {$error .= $e->getMessage() . '<br/>';}
		}
		
		if (empty($error)) {
			$this->view->JQuery()->addOnload("refreshDataTable();");
			$this->_simpleNoLayout($this->view->partial('success.phtml'));
		} else {$this->_simpleNoLayout($this->view->partial('error.phtml', ['m' => $error]));}
	}
	
	/**
	 * liste les groupes binsearch
	 * 
	 */
	public function regexAction() {
		$this->__('list',new  Default_Datagrid_BinsearchRegex);
	}
}

?>