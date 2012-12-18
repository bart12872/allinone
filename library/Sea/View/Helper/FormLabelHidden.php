<?php

require_once ('Sea/View/Helper/FormLabel.php');

/** 
 * @author jhouvion
 * 
 * 
 */
class Sea_View_Helper_FormLabelHidden extends Sea_View_Helper_FormLabel {

	public function formLabelHidden($name, $value = null, $attribs = null) {
		
		$xhtml = parent::formLabel($name, $value, $attribs);
		$xhtml .= $this->view->formHidden($name, $value );
		
		return $xhtml;
	}
}

?>