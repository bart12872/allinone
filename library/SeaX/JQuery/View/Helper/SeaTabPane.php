<?php

require_once ('ZendX/JQuery/View/Helper/TabPane.php');

class SeaX_JQuery_View_Helper_SeaTabPane extends ZendX_JQuery_View_Helper_TabPane {
	
	public function seaTabPane($id = null, $content = '', array $options = array()) {
		parent::tabPane($id, $content, $options);
	}
	
 	protected function _addPane($id, $name, $content, array $options=array())
    {
        $this->view->seaTabContainer()->addPane($id, $name, $content, $options);
    }
}

?>