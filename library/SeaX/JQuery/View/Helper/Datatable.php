<?php

/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_View_Helper_Datatable extends ZendX_JQuery_View_Helper_UiWidget{
	
	public function datatable($element, array $params=array(), $name = false) {
		
		$more = '';
		if ($params['bFilter'] == true) {$more .= '.fnFilterOnReturn().fnFilterColumns()';}
		if ($params['bServerSide'] == true) {$more .= '.fnInsertStack()';}
		
		// traitement des paramètre
		$params = count($params) > 0 ? ZendX_JQuery::encodeJson($params) : "{}";
		
		// Inscription
        $js = sprintf('%s("%s").dataTable(%s)%s;',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $element, 
            $params,
        	$more
        );
        
        // inscriptoin sur le onload
        $this->jquery->addOnLoad($js);
        
        return $js;
    }
}