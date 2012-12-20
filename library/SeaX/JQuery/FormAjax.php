<?php
require_once 'Trait/JQuery.php';
class SeaX_JQuery_FormAjax extends Sea_Form {
	
	use Trait_JQuery;
	
	public function render(Zend_View_Interface $view = null) {
		
		// si pas d'id généré, on en génére un
		$id = $this->getId();
		if (empty($id)) {$this->setAttrib('id', 'ajax_form__' . rand(0, 10000));}
		
		// Definition de la cible pour raflrachir le formulaire
		$this->setJqueryParam('target', sprintf('#%s:parent', $this->getId()));
		$this->setJqueryParam('success', new Zend_Json_Expr(sprintf('function() {jQuery("#%s").initialize()}', $this->getId())));

		// rednu du javascript
		$this->renderJs();
		
		return parent::render($view);// rendu du formulaire
	}
}

?>