<?php
require_once ('Sea/Db/Table.php');
class Application_Model_DbTable_BinsearchCollection extends Sea_Db_Table {
    
    protected $_name = 'binsearch_collection';
    
    protected $_referenceMap    = array(
	        'BinsearchGroup' => array(
	            'columns'           => 'binsearch_group_id',
	            'refTableClass'     => 'Extra_Binsearch_Model_DbTable_BinsearchGroup',
	            'refColumns'        => 'binsearch_group_id'
			)
	);
    
    protected $_dependentTables = array('BinsearchMatch' => 'Application_Model_DbTable_BinsearchMatch');
}
?>