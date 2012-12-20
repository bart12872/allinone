<?php
/**
 * liste les collections trouvé dans binsearch
 * 
 * @author jhouvion
 *
 */
class Default_Datagrid_BinsearchCollection extends Application_Model_Datatable {

	public function init() {
		
		$db = getconnection();// Récupérationd e la connexion
		
		// CREATION DES DATA
		$source = $db->select()	->from(['c' => 'binsearch_collection'])
								->join(['g' => 'binsearch_group'], 'g.binsearch_group_id=c.binsearch_group_id', ['binsearch_group_label', 'short_title'])
								->columns([ 'format_size' => new Zend_Db_Expr('CONCAT(ROUND(size / 1024), " Mo")')])
								->order('date DESC');
		
		// COLUMNS
		$select = $db->select()->from('binsearch_group', array('binsearch_group_id', 'short_title'))->order('short_title');
		$this->addText('Nom', 'title')->width('550px')->align('left')->sort('title');
		$this	->addText('Groupe', 'short_title')
				->setStrainerSelect(array('-- Choisissez --') + $db->fetchPairs($select))
				->sort('short_title')
				->align('center')
				->width('100px');
		$this->addText('Taille', 'format_size')->align('right')->width('80px');
		
		//FILTRE
		$search = $this->getRequestParam('sSearch');// recuperationd e la recherche
	    if (!empty($search) && strlen($search) > 3) {$source->where('title LIKE ?', '%' . $search . '%');}
		if ($v = $this->getRequestParam('short_title')) {$source->where('c.binsearch_group_id=?',$v);}
		
		//ORDER
		if ($this->getRequestParam('iSortingCols') > 0) {
			$column = $this->getColumnById($this->getRequestParam('iSortCol_0'));
			$source->reset(Zend_Db_Select::ORDER);
			if ($sort = $column->sort()) {$source->order($sort . ' ' . $this->getRequestParam('sSortDir_0'));}
		}
		
		$this->setAdapter($source);// attribution de l'adapteur a l'objet
		
		//PARAMETER
		$this->setItemCountPerPage(25);
		$this->setJqueryDefaultParam('aaSorting', '[]');
		$this->setJqueryParam('bSort', true);
		$this->setJqueryParam('aaSorting', array());
		$this->setJqueryParam('bFilter', true);
	}
}
?>