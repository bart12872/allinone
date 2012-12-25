<?php
require_once ('Sea/Db/Table.php');
class Application_Model_DbTable_Binnews extends Sea_Db_Table {
    
    protected $_name = 'binnews';
    
    protected $_referenceMap    = array(
	        'BinnewsCategory' => array(
	            'columns'           => 'binnews_category_id',
	            'refTableClass'     => 'Application_Model_DbTable_BinnewsCategory',
	            'refColumns'        => 'binnews_category_id'
			)
	);
}
?>