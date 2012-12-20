<?php

class Application_Model_FormAjax extends SeaX_JQuery_FormAjax {

	public function _init() {
		
		$this->setJQueryHelper('ajaxForm');
		$this->setJqueryDefaultParams(getregistry(false, 'ajaxform')->toArray());
		$this->setAction($this->getView()->url());
	}
}

?>