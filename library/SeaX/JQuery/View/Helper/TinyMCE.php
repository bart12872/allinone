<?php
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

class SeaX_JQuery_View_Helper_TinyMCE extends ZendX_JQuery_View_Helper_UiWidget {
    
    public function tinyMCE($id, $value = null, array $params = array(), array $attribs = array()) {
       
		// traitement des paramètre
		$params = count($params) > 0 ? ZendX_JQuery::encodeJson($params) : "{}";
		
		// Inscription
        $js = sprintf('%s("#%s").tinymce(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $attribs['id'], 
            $params
        );

        // inscriptoin sur le onload
        $this->jquery->addOnLoad($js);
        
         return $this->view->formTextarea($id, $value, $attribs);
    }
}

?>