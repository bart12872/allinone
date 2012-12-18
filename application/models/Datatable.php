<?php
require_once ('SeaX/JQuery/Datatable.php');

class Application_Model_Datatable extends SeaX_JQuery_Datatable {
	
	/**
	 * preconfiguration de l'objet
	 * 
	 * (non-PHPdoc)
	 * @see Sea_Datagrid_Html::_init()
	 */
	public function _init() {
		$this->setJQueryHelper('datatable');
		$this->setJqueryDefaultParams(getregistry(false, 'datatable')->toArray());
		parent::_init();
	}
   
}