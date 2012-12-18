<?php
/**
 * Création d'un box de mise en valeur
 * 
 *  @author Julien Houvion
 */


/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_View_Helper_Highlight extends ZendX_JQuery_View_Helper_UiWidget {
	
	public function highlight($content, array $attribs = array()) {

		// initialisation du widget
		$html = '';
		
		// gestion de la class
		$class = !empty($attribs['class']) ? $attribs['class'] : '';
		unset($attribs['class']);
		
		$html = sprintf('<div class="ui-widget" '. $this->_htmlAttribs($attribs) .'>
							<div class="ui-state-highlight ui-corner-all" style="margin-top:5px; padding: 0 .7em;"> 
								<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>%s</p>
							</div>
						</div>', $content);
		
		return $html;
    }
}

?>