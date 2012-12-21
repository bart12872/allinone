<?php

class Default_Datagrid_BinsearchRegexTest extends \Application_Model_Datatable {
	
	protected $_javascriptVarName = 'BinsearchRegexTest';
	
	public function init() {
		
		$db = getconnection();// récupération de la connexion
		
		$select = $db->select()	->from(array('c' => 'binsearch_collection'))
								->join(array('g' => 'binsearch_group'), 'g.binsearch_group_id=c.binsearch_group_id', 'binsearch_group_label')
								->columns(array('format_size' => new Zend_Db_Expr('CONCAT(ROUND(size / 1024), " Mo")')))
								->limit(100)
								->order('date DESC');
		
		if ($size = $this->getRequestParam('min_size')) {$select->where('size >=?', $size);}
		if ($group = $this->getRequestParam('binsearch_group_id')) {$select->where('c.binsearch_group_id=?', $group);}
		$search = $this->getRequestParam('sSearch');// recuperationd e la recherche
	    if (!empty($search) && strlen($search) > 3) {$select->where('title LIKE ?', '%' . $search . '%');}
		
		$result = [];// initliasation du resultat
		if ($pattern = $this->getRequestParam('pattern')) {
			foreach($db->fetchAll($select) as $row) {
				
			    // on test a regex
			    preg_match(sprintf('#^%s$#i',$pattern), $row['title'], $matches);
			    $row['name'] = empty($matches['name']) ? '' : $matches['name'];
			    $row['season'] = empty($matches['season']) ? '' : $matches['season'];
			    $row['episode'] = empty($matches['episode']) ? '' : $matches['episode'];
			    
			    
			    foreach($matches as $k => $v) {if (!is_numeric($k)){$row[$k] = $v;}}
			    $result[] = $row;
			    if (count($result) >= 100) {break;}
			}
		}

       	$this->setAdapter($result);
       	$this->setItemCountPerPage(10);
       	$this->setJqueryParam('bFilter', true);
		$this->addText('Titre', 'title')->width('500px');
		$this->addText('Serie', 'name')->width('500px');
		$this->addText('Sai.', 'season')->width('30px');
		$this->addText('Ep.', 'episode')->width('30px');
		
		
		$tt = $this->createExtra('Tabletools');
		$tt->addButton('Tester', sprintf('glob("%s").fnDraw();', $this->getJavascriptVarName()));
		$this->addExtra($tt);
		
		// javascript pour le post du formulaire
		$js = <<<EOL
		 function ( aoData) {
			aoData.push( { "name": "sToken", "value": "$this->_token" } );
			aoData.push( { "name": "min_size", "value":\$('#min_size').val() } );
			aoData.push( { "name": "binsearch_group_id", "value":\$('#binsearch_group_id').val() } );
			aoData.push( { "name": "pattern", "value":\$('#pattern').val() } );
	    }
EOL;
		$this->setJqueryParam('fnServerParams', new Zend_Json_Expr($js));
	}
}

?>