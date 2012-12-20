<?php
class Default_Datagrid_BinsearchGroup extends \Application_Model_Datatable {
	
	public function init() {
	
	  	$db = getconnection();// récupération de la connexion
		$s = $db->select()	->from('binsearch_group')
							->joinLeft('binsearch_collection', 'binsearch_group.binsearch_group_id=binsearch_collection.binsearch_group_id', null)
							->columns(array('c' => new Zend_Db_Expr('count(binsearch_collection_id)')))
							->group('binsearch_group.binsearch_group_id');
		
		$search = $this->getRequestParam('sSearch');// recuperationd e la recherche
	    if (!empty($search) && strlen($search) > 3) {$s->where('binsearch_group_label LIKE ?', '%' . $search . '%');}
        
        // creation de la liste
        $this->setJqueryParam('bFilter', true);
		$this->addText('Nom', 'binsearch_group_label');
		$this->addText('Diminutif', 'short_title')->align('center');
        $this->addText('Limite Rss', 'rss')->align('center');
        $this->addText('Collection', 'c')->align('center');
		$this->addMulti('Action') ->add($this->column('IconeUi', 'Raffraichir', 'ui-icon-arrowrefresh-1-e', $this->getView()->url(['controller' => 'binsearch', 'action' => 'charger', 'id' => '%d'], null, true, false), 'binsearch_group_id', ['class' => 'dialog-ajax']))
                                  ->add($this->column('IconeUi', 'Editer', 'ui-icon-pencil', $this->getView()->url(['controller' => 'binsearch', 'action' => 'editergroupe', 'id' => '%d'], null, true, false), 'binsearch_group_id', ['class' => 'dialog-ajax', 'data-dialog-width' => 800]))
                                  ->add($this->column('IconeUi', 'Supprimer', 'ui-icon-closethick', $this->getView()->url(['controller' => 'binsearch', 'action' => 'effacergroupe', 'id' => '%d'], null, true, false), 'binsearch_group_id', ['class' => 'dialog-ajax']))
                                  ->align('center')->width('120px');
		 
        $this->setAdapter($s);
		$tt = $this->createExtra('Tabletools');
		$tt->addButton('Ajouter', sprintf('dialogurl("%s", null,"Nouvelle entrée", 600)',  $this->getView()->url(['controller' => 'binsearch', 'action' => 'editergroupe'], 'default', false, true)));
		$tt->addButton('Charger', sprintf('dialogurl("%s", null, "Chargement des flux")', $this->getView()->url(['controller' => 'binsearch', 'action' => 'charger'], 'default', false, true)));
		$this->addExtra($tt);
		$this->setItemCountPerPage(0);
	}
}

?>