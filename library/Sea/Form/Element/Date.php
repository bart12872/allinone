<?php
/**
 * generation d'un champ formulaire de date
 *
 */

class Sea_Form_Element_Date extends Zend_Form_Element_Text {

	/**
     * @var string Default view helper
     */
	var $helper = 'formDate';

	/**
	 * Option relative au parametrage du plugin date picker
	 *
	 * @var unknown_type
	 */
	protected $_attribs = array('dpk_startDate' => '01/01/1996');

	/**
	 * prefix pour les attribut du date picker
	 *
	 * @var unknown_type
	 */
	protected $_prefix = 'dpk_';


	 /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {

		/* on ajoute un emplacement e decorateurs à l'élément */
		$this->addPrefixPath('Sea_Form_Decorator', 'Sea/Form/Decorator', 'decorator');
		
		$this->setDecorators(array(	array('SeaErrors'),
									array('ViewHelper', array('placement' => 'PREPEND')),
						            array(array('input' => 'HtmlTag'), array('tag' => 'td', 'class' => 'form-input')),
								    array('SeaLabel'),
								    array(array('div' => 'HtmlTag'), array('tag' => 'tr'))));

	}

	/**
	 * Setter pour les atribut relatif a uploadify
	 *
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @return unknown
	 */
	function setPlugin($name, $value) {
		if (!empty($name)) {$this->_attribs[$this->_prefix.$name] = $value;}
		return $this;
	}


	 /**
     * Set element value (SURCHARGE)
     *
     * @param  mixed $value
     * @return Zend_Form_Element
     */
	public function setValue($value)
    {

    	/* recuperation parametre */
    	$registry = Zend_Registry::getInstance();
        $config = $registry->get("config");

        $dateValue = '';

        /* formatage de la date */
        if (!empty($value)) {

        	/* cas de l'enregistrement en base */
        	if (Zend_Date::isDate($value, $config->date->outputFormat)) {
        		$date  = new Zend_Date($value);
        		$dateValue = $date->get($config->date->inputFormat);
        	}

        	/*  cas de la lecture en base */
        	if (Zend_Date::isDate($value, $config->date->inputFormat)) {
        		$date  = new Zend_Date($value);
        		$dateValue = $date->get($config->date->outputFormat);
        	}
        }

        /* mise de la valeur dans le champ de saisie */
        $this->setPlugin('dpSetSelected' ,$dateValue );

        $this->_value = $dateValue;

        return $this;
    }

    /**
	 * Surcharge du rendu
	 *
	 * @param Zend_View_Interface $view
	 */
	public function render( 	Zend_View_Interface $view = null) {
		$this->setAttribs($this->_attribs);

		return parent::render($view);
	}
}