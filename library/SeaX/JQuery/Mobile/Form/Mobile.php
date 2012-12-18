<?php
/**
 * Creation du formulaire pour mobile
 * @author Fdorchy
 * @since 28/02/2012
 */


class SeaX_JQuery_Mobile_Form_Mobile extends Sea_Form
{
	/**
	 * prefix pour le element a rajouter
	 * 
	 * @var String
	 */
	static public $ELEMENT_TYPE = 'SeaX_JQuery_Mobile_Form_Element_';
	
	/**
	 * Bouton d'action du fomulaire
	 * 
	 * @var unknown_type
	 */
	protected $_submit;
	
	/**
	 * formatage des donnée
	 *
	 */
	protected function _format( $values = array() ) {return $values;}

	/**
	 * surclass du constucteur
	 *
	 * @param unknown_type $option
	 */
	function __construct($option = null) {
		
		//ajout des décorateur
		$this->addPrefixPath('SeaX_JQuery_Mobile_Form_Decorator', 'SeaX/JQuery/Mobile/Form/Decorator', 'decorator');
		
		// on ajoute les decorateur
        $this->loadDefaultDecorators();
	          
	    // rfécuperere les information passé en paramètre
		$args = func_get_args();
		
		//on apelle la function init avec les paramètres passé au constructeur
		call_user_func_array(array($this, 'init'), $args);
	}
	
	/**
	 * ajoute un bouton de validation de formulaire
	 * 
	 * @param String $id
	 * @param String $value
	 * @param Array $attribs
	 */
	public function addSubmit($id, $value, $attribs = array()) {
		
		// créartion de l'element
		require_once 'SeaX/JQuery/Mobile/Form/Element/Submit.php';
		$element = new SeaX_JQuery_Mobile_Form_Element_Submit($id, $value);
		
		// ajout des attribut pour les bouton
		$element->setOptions($attribs);
		
		// attribution de l'element a la pile 
		$this->_submit[] = $element;
		
		return $this;
	}
	
	/**
	 * ajoute un element au formulaire
	 * (non-PHPdoc)
	 * @see Zend_Form::addElement()
	 */
	public function addElement($type) {
	
		if ($type instanceof Zend_Form_Element) {
			$element = $type;
		}
		else  {
			// On récupère les arguments sans le premier, et on insère l'argument id en 2e position
			$args = func_get_args();
				
			// on verifie que le type existe
			$type = static::$ELEMENT_TYPE . $type;
			$filename =  str_replace('_', '/', $type ) . '.php';
			require_once $filename;
			unset($args[0]);
				
			// recuperation des parametre
			$php = array();
			foreach ($args as $value) {
				$php[] = var_export($value, true);
			}
			
			// creation de la colum
			eval (sprintf('$element = new '. $type .'(%s);', implode(',', $php)));
		}
		parent::addElement($element);
	
		return $element;
	}
	
	/**
	 * chargement des decorateurs par default
	 *
	 * (non-PHPdoc)
	 * @see Zend_Form_Element::loadDefaultDecorators()
	 */
	public function loadDefaultDecorators() {
	
		$this->setDecorators(	array(	array('FormElements'),
								array('Submit', array('class' => 'formSubmit')),
								array('form', array('class' => 'formSea'))));
	
	}
}

