<?php
/**
 * constrcution d'un Slider
 * 
 * @author fdorchy
 *
 */
require_once 'Zend/View/Helper/FormElement.php';

class SeaX_JQuery_View_Helper_MobileFormSlider extends Zend_View_Helper_FormElement { 
	
	public function mobileFormSlider($name, $value = null, $attribs = null,$options = null, $listsep = "<br />\n")
	{
		$info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
		extract($info); // name, id, value, attribs, options, listsep, disable
		
		// now start building the XHTML.
		$disabled = '';
		if (true === $disable) {
			$disabled = ' disabled="disabled"';
		}
		
		$opt='';
		foreach(array_flip($options) as $option){
			$opt .= $option.'="'. $options[$option].'" '; 
		}
		
	
		// Build the surrounding select element first.
		$xhtml = '<input'
		. ' type="range" '
		. ' name="' . $this->view->escape($name) . '"'
		. ' id="' . $this->view->escape($id) . '"'
		. ' value="' . $this->view->escape($value) . '"'
		. $disabled
		. $this->_htmlAttribs($attribs)
		. $opt
		
		. ">\n    ";
	
		return $xhtml;
	}
	
}
