<?php
require_once ('Sea/Db/Table.php');
class Application_Model_DbTable_BinnewsCategory extends Sea_Db_Table {
    
    protected $_name = 'binnews_category';
    
    protected $_dependentTables = array('Binnews' => 'Application_Model_DbTable_Binnews');
}
?>