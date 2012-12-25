<?php
class Application_Model_DbTable_BinsearchRegex extends \Sea_Db_Table {
	
	protected $_name = 'binsearch_regex';
    
    protected $_referenceMap    = array(
	        'BinsearchGroup' => array(
	            'columns'           => 'binsearch_group_id',
	            'refTableClass'     => 'Application_Model_DbTable_BinsearchGroup',
	            'refColumns'        => 'binsearch_group_id'
			)
	);
}

?>