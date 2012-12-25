<?php
require_once ('Sea/Db/Table.php');
class Application_Model_DbTable_BinsearchGroup extends Sea_Db_Table {
    
    protected $_name = 'binsearch_group';
    
    protected $_dependentTables = array('BinsearchRegex' => 'Application_Model_DbTable_BinsearchRegex',
    									'BinsearchCollection' => 'Application_Model_DbTable_BinsearchCollection');
    
}
?>