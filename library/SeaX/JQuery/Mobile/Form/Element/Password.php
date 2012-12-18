<?php

require_once ('Zend/Form/Element/Password.php');

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Mobile_Form_Element_Password extends Zend_Form_Element_Password {
 /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formPassword';
    
    /**
     * surcharge du constructeur
     * 
     * @param unknown_type $spec
     * @param unknown_type $options
     */
    public function __construct($spec, $label = '', $required = false, $hidden = false) {
    
    	// construction du parent
    	parent::__construct($spec);
    	
    	// traitement des paramètres
    	$this->setLabel($label)->setRequired($required)->setAllowEmpty(!$required);
    	
    	if($hidden){
    		$this->setAttribs(array('placeholder' => $label));
    		$this->removeDecorator('SeaLabel');
    	}

    }

     /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {

		/* on ajoute un emplacement e decorateurs à l'élément */
		$this->addPrefixPath('Sea_Form_Decorator', 'Sea/Form/Decorator', 'decorator');

		/* Remplace les decorateurs par default */
		
    	$this->setDecorators(array(	
    								array('ViewHelper', array('placement' => 'APPEND')),
								    array('SeaLabel', array('tag' => 'legend')),
    								array(array('div' => 'HtmlTag'), array('tag' => 'div')),
    								array('SeaErrors')));

	}


}

?>