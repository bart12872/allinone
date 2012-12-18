<?php
/**
 * Création d'un accordeon jquery a artir d'un conteneur existant
 * 
 *  @author Julien Houvion
 */


/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";


class SeaX_JQuery_View_Helper_Accordion extends ZendX_JQuery_View_Helper_UiWidget {
    
    public function accordion($element, array $params=array()) {
       
		// traitement des paramètre
		$params = count($params) > 0 ? ZendX_JQuery::encodeJson($params) : "{}";
		
		// Inscription
        $js = sprintf('%s("%s").accordion(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $element, 
            $params
        );

        // inscriptoin sur le onload
        $this->jquery->addOnLoad($js);
    }
}