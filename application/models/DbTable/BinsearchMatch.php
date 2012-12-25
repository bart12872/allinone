<?php
require_once ('Sea/Db/Table.php');
class Application_Model_DbTable_BinsearchMatch extends Sea_Db_Table {
    
    protected $_name = 'binsearch_match';
    
    protected $_referenceMap    = array(
	        'BinsearchCollection' => array(
	            'columns'           => 'binsearch_collection_id',
	            'refTableClass'     => 'Application_Model_DbTable_BinsearchCollection',
	            'refColumns'        => 'binsearch_collection_id'
			),
    		'BinsearchRegex' => array(
	            'columns'           => 'binsearch_regex_id',
	            'refTableClass'     => 'Extra_Binsearch_Model_DbTable_BinsearchRegex',
	            'refColumns'        => 'binsearch_regex_id'
			)
	);
}
?>