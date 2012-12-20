<?php
require_once ('ZendX/JQuery/View/Helper/UiWidget.php');

class SeaX_JQuery_View_Helper_AjaxForm extends ZendX_JQuery_View_Helper_UiWidget {
	
	function ajaxForm($element, array $params=array()) {
       
		// traitement des paramètre
		$params = count($params) > 0 ? ZendX_JQuery::encodeJson($params) : "{}";
		
		// Inscription
        $js = sprintf('%s("%s").ajaxForm(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $element, 
            $params
        );

        // inscriptoin sur le onload
        $this->jquery->addOnLoad($js);
    }
}

?>