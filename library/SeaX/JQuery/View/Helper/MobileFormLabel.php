<?php
/**
 * constrcution d'un label
 * 
 * @author jhouvion
 *
 */
class SeaX_JQuery_View_Helper_MobileFormLabel extends Zend_View_Helper_FormElement {
	
	public function mobileFormLabel($name, $value = null, $attribs = null) {
		
		$info = $this->_getInfo ( $name, $value, $attribs );
		extract ( $info ); // name, value, attribs, options, listsep, disable

		$xhtml  = '<label for ='. $info['id'] .' >' . $info['value'] . '</label>';
		
		return $xhtml;
	}
}
