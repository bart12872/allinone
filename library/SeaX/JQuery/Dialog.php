<?php
require_once 'Trait/View.php';
/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Dialog {
	
	 use Trait_View;
	
	/**
	 * View helper pour generer l'element
	 * 
	 * @var dialogContainer
	 */
	protected $_helper = 'dialogContainer';
	
	/**
	 * Titre du dialog
	 * 
	 * @var String
	 */
	protected $_title;
	
	/**
	 * Contenu du dialog
	 * 
	 * @var String
	 */
	protected $_content;
	
	/**
	 * Identifiant de l'obket (attribut "id" de la balise html)
	 * 
	 * @var unknown_type
	 */
	protected $_id;
	
	/**
	 *Option passé au dialog
	 * 
	 * @var Array
	 */
	protected $_options = array('resizable' => false, 'autoOpen' => false);
	
	/**
	 * taille du dialog
	 * 
	 * @var unknown_type
	 */
	protected $_width = '500px';
	
	/**
	 * constructeur
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $title
	 * @param unknown_type $content
	 * @param unknown_type $option
	 */
	public function __construct($id = '', $title = '', $content = '', $option = array()) {
		
		 // rfécuperere les information passé en paramètre
		$args = func_get_args();
		
		//on apelle la function init avec les paramètres passé au constructeur
		call_user_func_array(array($this, 'init'), $args);
	}
	
	/**
	 * function d'initialisation pour le surchargeage
	 * 
	 */
	public function init($id = '', $title = '', $content = '', $option = array()) {

		// construction
		$this	->setId($id)
				->setTitle($title)
				->setContent($content);
				
		if (!empty($option)){$this->setOptions($option);}
	}
	
	/**
	 * ajoute ou modifi une option
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function addOption($key, $value) {
		$this->_options[$key]= $value;
	}
	
	/**
	 * enleve une option
	 * 
	 * @param unknown_type $key
	 */
	public function removeOption($key) {
		if (array_key_exists($key, $this->getOptions())) {unset($this->_options[$key]);}
	}
	
	/**
	 * on effectue le rendu
	 * 
	 */
	public function render() {
		// recuperation du helper
		$helper = $this->getHelper();
		
		// constrcution des options
		$options = $this->getOptions();
		$options['title'] = $this->getTitle();
		$options['width'] = $this->getWidth();

		return $this->getView()->$helper($this->getId(), strval($this->getContent()), $options);
	}	
	
	/**
	 * alias pour render
	 */
	public function __toString() {return $this->render();}
    
    /**
     * ajoute le dialog au rendu final
     * 
     */
    public function append(){
    	Zend_Controller_Front::getInstance()->getResponse()->appendBody($this->render());
    }
    
	/**
	 * @return the $_helper
	 */
	public function getHelper() {
		return $this->_helper;
	}

	/**
	 * @return the $_title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * @return the $_content
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_options
	 */
	public function getOptions() {
		return $this->_options;
	}

	/**
	 * @param dialogContainer $_helper
	 */
	public function setHelper($_helper) {
		$this->_helper = $_helper;
		return $this;
	}

	/**
	 * @param String $_title
	 */
	public function setTitle($_title) {
		$this->_title = $_title;
		return $this;
	}

	/**
	 * @param String $_content
	 */
	public function setContent($_content) {
		$this->_content = $_content;
		return $this;
	}

	/**
	 * @param unknown_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
		return $this;
	}

	/**
	 * @param unknown_type $_options
	 */
	public function setOptions($_options) {
		$this->_options = $_options;
		return $this;
	}
	/**
	 * @return the $_width
	 */
	public function getWidth() {
		return $this->_width;
	}

	/**
	 * @param unknown_type $_width
	 */
	public function setWidth($_width) {
		$this->_width = $_width;
		return $this;
	}
}
?>