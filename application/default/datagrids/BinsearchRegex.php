<?php

class Default_Datagrid_BinsearchRegex extends \Application_Model_Datatable {
	
public function init() {

		$db = getconnection();// récupération de la connexion
		$s = $db->select()	
				->from(array('br' => 'binsearch_regex'))
				->join(array('bg' => 'binsearch_group'), 'br.binsearch_group_id=bg.binsearch_group_id', 'short_title')->order(['short_title', 'rank']);
       	
       	// creation de la liste
        $this->setJqueryParam('bFilter', true);
		$this->addText('Regex', 'pattern')->width('400px');
		
		$select = $db->select()->from('binsearch_group', array('binsearch_group_id', 'short_title'))->order('short_title');	
        $this->addText('Groupe', 'short_title')->width('100px')->align('center')->setStrainerSelect(array('-- Choisissez --') + $db->fetchPairs($select));
        $this->addText('Rang', 'rank')->width('15px')->align('center');
// 		$this->addMulti('Action') ->add($this->column('IconeUi', 'Gèrer les correspondances', 'ui-icon-arrowthick-2-e-w', $this->getView()->url($default + array('extra_action' => 'correspondance'), 'extra', true, false), 'binsearch_regex_id'))
//                                   ->add($this->column('IconeUi', 'Editer', 'ui-icon-pencil', $this->getView()->url($default + array('extra_action' => 'editer'), 'extra', true, false), 'binsearch_regex_id'))
//                                   ->add($this->column('IconeUi', 'Supprimer', 'ui-icon-closethick', $this->getView()->url($default + array('extra_action' => 'effacer'), 'extra', true, false), 'binsearch_regex_id'))
//                                   ->align('center')->width('130px');
		
// 		$tt = $this->createExtra('Tabletools');
// 		$tt->addLink('Ajouter', $this->getView()->url(array('extra_action' => 'editer')));
// 		$this->addExtra($tt);
		
		//FILTRE
		$v = $this->getRequestParam('short_title');if (!empty($v)) {$s->where('bg.binsearch_group_id=?', $v);}
		
		$this->setAdapter($s);
		
		$this->setJqueryDefaultParam('aaSorting', '[]');
		$this->setJqueryParam('bSort', true);
	}
}

?>