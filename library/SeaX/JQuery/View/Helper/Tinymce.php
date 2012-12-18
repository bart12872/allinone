<?php

/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/**
 * 
 * Editeur WYSIWYG TinyMCE pour Jquery
 * 
 * @author tibor
 *
 */
class SeaX_JQuery_View_Helper_Tinymce extends ZendX_JQuery_View_Helper_UiWidget
{
    static public $params = array();
	
    public function tinymce($element, array $params=array())
    {
		// traitement des paramètre
		$params = array_merge($params, self::$params);
		$params = count($params) > 0 ? ZendX_JQuery::encodeJson($params) : "{}";
		
		// Inscription
        $js = sprintf('%s("%s").tinymce(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $element, 
            $params
        );

        // inscriptoin sur le onload
        $this->jquery->addOnLoad($js);
    }
}
