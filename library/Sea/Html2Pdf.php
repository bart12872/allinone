<?php

require_once 'html2pdf/html2pdf.class.php';
require_once 'Sea/View/Aggregate.php';

class Sea_Html2Pdf extends HTML2PDF {

	// Iniitalisation du constructeur
	protected $sens = 'P';
	protected $format = 'A4';
	protected $langue='fr';
	protected $unicode=true; 
	protected $encoding='UTF-8';
	protected $marges = array(5, 5, 5, 8);
	
	/**
	 * Chemin du fichier phtml pour effectuer le rendu
	 * 
	 * @var unknown_type
	 */
	protected $script;
	
	/**
	 * Vue afin de gerer le rendu
	 * 
	 * @var Zend_View
	 */
	protected $view;
	
	/**
	 * fichier 
	 * 
	 * @var Sea_File
	 */
	protected $_file;
	
	/**
	 * chemin du fichier physique
	 */
	protected $_filename;
	
	/**
	 * argument a passer a la method init
	 */
	protected $_args;

	/**
	 * methode a surcharger 
	 * 
	 */
	public function init() {;}
	
	/**
	 * Surchazrge du constrcuteur pour inclure l'initialisation
	 * 
	 * @param unknown_type $sens
	 * @param unknown_type $format
	 * @param unknown_type $langue
	 * @param unknown_type $unicode
	 * @param unknown_type $encoding
	 * @param unknown_type $marges
	 */
	public function __construct() {
	
		// paramètrage de la vue création de la vue
		$this->getView()->addBasePath(APPLICATION_PATH . '/views');
		
		parent::__construct();
		
		// on recupere les arguments
		$this->setArgs(func_get_args());
			
		//on apelle la function init avec les paramètres passé au constructeur
		call_user_func_array(array($this, 'init'), $this->getArgs());
	}
	
	public function resetPdf() {	
		// création de l' objet PDF
		$this->pdf = new HTML2PDF_myPdf($this->sens, 'mm', $this->format, $this->unicode, $this->encoding);
	}
	
	
	//GESTION DES VUE
	/**
	 * Calcul du rendu (contenu)
	 * 
	 */
	protected function render() {
		// on retourne le dernier rendu
		return $this->view->render($this->getScript());
	}
	
   /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Form
     */
    public function setView(Zend_View_Interface $view = null) {
        $this->view = $view;
        return $this;
    }

    /**
     * Retrieve view object
     *
     * If none registered, attempts to pull from ViewRenderer.
     *
     * @return Zend_View_Interface|null
     */
    public function getView() {
        if (null === $this->view) {
            require_once 'Zend/Controller/Action/HelperBroker.php';
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $this->setView(clone($viewRenderer->view));
        }

        return $this->view;
    }
   
	/**
	 * @return the $_script
	 */
	public function getScript() {
		return $this->script;
	}

	/**
	 * @param unknown_type $_script
	 */
	public function setScript($_script) {
		$this->script = $_script;
		return $this;
	}
	
	

	/**
	 * on supprime le fichier, permet a la regenration de celui ci
	 * 
	 * 
	 * @throws Zend_Exception
	 */
	public function clean() {

		// si le fichier n'dxiste pas, on ne fait pas traitement
		if (file_exists($this->getFilename())) { 
			
			// supression du fichier
			if (!unlink($this->getFilename())) {
				require_once 'Zend/Exception.php';
				throw new Zend_Exception('Impossible de supprimer le fichier');
			}
			
			// onreset le contenue du pdf
			$this->resetPdf();
		}
		
		return $this;
	}
	
	/**
	 * efface le fichier s'il existe et regenere la facture
	 * 
	 */
	public function update() {
		if (file_exists($this->getFilename())) {$this->clean();}
		$this->init($this->getArgs());
		return $this;
	}
	
	/**
	 * on affiche le fichier dans le navigateur si celui ci a les plugin 
	 * 
	 */
	public function show() {
		//lecture et affichage du fichier
		$this->getFile()->read($this->getFilename());
		$this->getFile()->show(basename($this->getFilename()));
	}
	
	/**
	 *  on force le telechargement du fichier
	 * 
	 */
	public function download() {
		//lecture et affichage du fichier
		$this->getFile()->read($this->getFilename());
		$this->getFile()->download(basename($this->getFilename()));
	}
	/**
	 * @return the $_file
	 */
	public function getFile() {
		return $this->_file;
	}

	/**
	 * @return the $_filename
	 */
	public function getFilename() {
		return $this->_filename;
	}

	/**
	 * @return the $_args
	 */
	public function getArgs() {
		return $this->_args;
	}

	/**
	 * @param Sea_File $_file
	 */
	public function setFile($_file) {
		$this->_file = $_file;
		return $this;
	}

	/**
	 * @param field_type $_filename
	 */
	public function setFilename($_filename) {
		$this->_filename = $_filename;
		return $this;
	}

	/**
	 * @param field_type $_args
	 */
	public function setArgs($_args) {
		$this->_args = $_args;
		return $this;
	}

}

?>