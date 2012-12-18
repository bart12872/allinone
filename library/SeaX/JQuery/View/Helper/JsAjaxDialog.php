<?php

class SeaX_JQuery_View_Helper_JsAjaxDialog extends ZendX_JQuery_View_Helper_UiWidget {
	
	public function jsAjaxDialog($url, $title = '', $width = 300, $attrib = []) {
		
		// recuperation de la function jquery
		$jqh = ZendX_JQuery_View_Helper_JQuery::getJQueryHandler();
		
		/// paramètrage
		$attrib['width'] = $width;
		$attrib['title'] = $title;
		if (empty($attrib['modal'])) {$attrib['modal'] = true;}
		if (empty($attrib['resizable'])) {$attrib['resizable'] = true;}
		if (empty($attrib['minHeight'])) {$attrib['minHeight'] = '0px';}
		if (empty($attrib['close'])) {$attrib['minHeight'] = new Zend_Json_Expr(sprintf('function(){%s(this).remove().dialog(\'destroy\'); }', $jqh));}
		
		// code javascript
		$js = "%1\$s('<div />').css('display', 'none').appendTo('body').load('%2\$s', function() {%1\$s(this).initialize().dialog(%3\$s);});";

        return sprintf($js, $jqh, $url, ZendX_JQuery::encodeJson($attrib));
	}
}
