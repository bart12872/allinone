<?php
require_once 'Zend/Form/Decorator/Abstract.php';

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Mobile_Form_Decorator_SeaErrors extends Zend_Form_Decorator_Abstract{
	
	
	public function render($content) {
		
		$e = $this->getElement();// récupération de l'élément
		
		// si l'element a des erreur on les affiche
		if ($e->hasErrors()) {
			
			// Construction du message
			$decorator = new Zend_Form_Decorator_HtmlTag();
	        $decorator->setOptions(array('tag' => 'div','data-role' => 'content', 'class' => 'error'));			
	        $content = $decorator->render(sprintf('<span>%s</span>%s', implode('<br/>', $e->getMessages()), $content));
			
			if ($e instanceof Sea_Form_Element_Radio) {
				$e->getDecorator('container')->setOption('class', 'formErrorRadio');
			
			// on ajourte la class erreur a l'element
			} else {$e->setAttrib('class', sprintf('%s %s', $e->getAttrib('class'), 'formError'));}
		}

		return $content;
	}
}

?>