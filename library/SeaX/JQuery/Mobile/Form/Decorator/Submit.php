<?php

require_once ('Zend/Form/Decorator/Abstract.php');

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Mobile_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract {
	
	/**
	 * rendu des bouton de soumission de formulaire(non-PHPdoc)
	 * 
	 * @see Zend_Form_Decorator_Abstract::render()
	 */
	public function render($content) {
		
		// initialisation du rendu
		$render = '';
		
		// recupration des bouton
		$submit = $this->getElement()->getSubmit();
		
		// si aucun bouton, on renvoie le rendu actuel
		if (empty($submit)) {return $content;}
		
		//construction des bouton
		foreach ((array)$this->getElement()->getSubmit() as $submit) {
		
			// on recupere la vue
			$view = $this->getElement()->getView();
			$render .= $submit->render();
		}
		
		// ajout des bouton
		if (!empty($render)) {
			// formatage des attributs
			$attrs = '';
			foreach ($this->getOptions() as $key => $value) {$attrs .= ' ' . $key .'="' . $value . '"';}
			$content  .=  '<div '.$attrs.'>' . $render .'</div>';
		}
		
		return $content;
	}
	
}

?>