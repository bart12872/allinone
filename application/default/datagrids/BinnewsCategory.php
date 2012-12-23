<?php
class Default_Datagrid_BinnewsCategory extends Application_Model_Datatable {
	
	public function init() {
		
		$db = getconnection();// récupération de la connexion
		$s = $db->select()	->from('binnews_category')->order('binnews_category_label');
       	
       	// creation de la liste
        $this->setJqueryParam('bFilter', true);
        
		$this->addText('ID', 'binnews_category_id' );
		$this->addText('Nom', 'binnews_category_label' )->width('400px');
		$this->addBoolean('?', 'active' )->width('30px');
		$this->addText('Raffraichi', 'refreshed' )->width('100px');
		$this->addMulti('Action') ->add($this->column('IconeUi', 'Raffraichir', 'ui-icon-refresh', $this->getView()->url(['module' => 'www', 'controller' => 'binnews', 'action' => 'charger', 'id' => '%d'], 'default', true, false), 'binnews_category_id',  ['class' => 'dialog-ajax']))
                                  ->add($this->column('IconeUi', 'Editer', 'ui-icon-pencil', $this->getView()->url(['module' => 'www', 'controller' => 'binnews', 'action' => 'editercategorie', 'id' => '%d'], 'default', true, false), 'binnews_category_id',  ['class' => 'dialog-ajax', 'data-dialog-width' => 500]))
                                  ->align('center')->width('130px');
		//FILTRE
		$search = $this->getRequestParam('sSearch');// recuperationd e la recherche
		if (!empty($search) && strlen($search) > 3) {$s->where('binnews_category_label LIKE ?', '%' . $search . '%');}
		$this->setAdapter($s);
		
		$this->setItemCountPerPage(30);
		$this->setJqueryParam('bFilter', true);
		
		$tt = $this->createExtra('Tabletools');
		$tt->addButton('Ajouter', sprintf('dialogurl("%s", null, "Ajouter", 500)' ,$this->getView()->url(['controller' => 'binnews', 'action' => 'editercategorie'], 'default', true, false)));
		$tt->addButton('Charger', sprintf('dialogurl("%s", null, "Raffraichir")' ,$this->getView()->url(['controller' => 'binnews', 'action' => 'charger'], 'default', true, false)));
		$this->addExtra($tt);
	}
}