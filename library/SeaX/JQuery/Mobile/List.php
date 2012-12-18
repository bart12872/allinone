<?php 
class SeaX_JQuery_Mobile_List{
	
	
	/**
	 * Vue pour effectuer le rendu
	 *
	 * @var Zend_View
	 */
	protected $view;
	
	/**
	 * 
	 * contenu de la liste
	 * 
	 * @var array
	 */
	protected $data;
	
	/**
	 *
	 * contenu des attributs
	 *
	 * @var array
	 */
	protected $attribs;
	
	/**
	 *
	 * contenu des options
	 *
	 * @var array
	 */
	protected $options;
	
	/**
	 *
	 * contenu des attributs
	 *
	 * @var bool
	 */
	 protected $ordered = false;
	 
	 /**
	  *
	  * contenu des options par defaut
	  *
	  * @var array
	  */
	 protected $defaultOptions = array(	'data-role' => 'listview',
										'data-inset'=>'true',
										'data-theme' => 'c',
										'data-icon' => '',
	 									'data-divider-theme' => 'a');
	 
	/**
	 * charge le constructeur
	 * @param unknown_type $data
	 */
	public function __construct($data) {
		$this->setData($data);
		$this->_loadDefaultOptions();
	}
	/**
	 * permet de charger le theme de la liste
	 * @param unknown_type $theme
	 */
	public function theme($theme = false) {
		if ($theme){$this->setOption('data-theme', $theme);return $this;}
		else {return $this->getOption();}
	}
	
	/**
	 * charge les options par default
	 */
	protected function _loadDefaultOptions(){
		$this->options = $this->defaultOptions;
	}
	
	/**
	 * rendu dans une vue
	 */
	public function render() {
		return $this->getView()->mobileList($this->getAttribs(), $this->getOrdered(), $this->getOptions());
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
			$this->setView($viewRenderer->view);
		}
	
		return $this->view;
	}
	
	/**
	 * 
	 * rajoute un url à la liste attribut
	 *
	 */
	public function addUrl($url,$row){

		$row = (array) $row;

		$attribs = $this->getAttribs();
		foreach( $this->getData() as $k =>$d){
			foreach($row as $r){$tab[] = $d[(string)$r];}
			$attribs[$k]['link'] = str_replace(" ","",vsprintf($url, $tab));
			unset($tab);
		}
		$this->setAttribs($attribs);
		$this->getAttribs();
	}
	
	/**
	 *
	 * rajoute une description à la liste attribut
	 *
	 */
	public function addDescription($row){
		$attribs = $this->getAttribs();
		foreach( $this->getData() as $k =>$d){ 
			$attribs[$k]['desc'] = $d[(string)$row];
		}
		$this->setAttribs($attribs);
		$this->getAttribs();
	}
	
	/**
	 *
	 * rajoute un compteur à la liste attribut
	 *
	 */
	public function addCount($row){
		$attribs = $this->getAttribs();
		foreach( $this->getData() as $k =>$d){
			$attribs[$k]['count'] = $d[(string)$row];
		}
		$this->setAttribs($attribs);
		$this->getAttribs();
	}
	
	/**
	 *
	 * rajoute une image à la liste attribut
	 *
	 */
	public function addIcone($url,$row){
		
		$row = (array) $row;
		
		$attribs = $this->getAttribs();
		foreach( $this->getData() as $k =>$d){
			foreach($row as $r){$tab[] = $d[(string)$r];}
			$attribs[$k]['img'] = str_replace(" ","",vsprintf($url, $tab));
			unset($tab);
		}
		$this->setAttribs($attribs);
		$this->getAttribs();
	}
	
	/**
	 *
	 * rajoute un divider par rapport au champ selectionné
	 *
	 */
	public function addDivider($row){
		$divider = array();
		foreach( $this->getData() as $k =>$d){
			if($d[(string)$row] != $divider){
				//quand on detecte un nouveau divider on le place dans le tableau
				$divider = $d[(string)$row];
				$tab[$k]['divider'] = $d[(string)$row];	
			}
			if(isset($attribs[$k])){$tab[$k+$attribs] = $attribs[$k];}
		}
		//set le tableau dans le Attribs
		$this->setAttribs($tab);
		$this->getAttribs();
	}
	
	/**
	 *
	 * rajoute un titre à la liste attribut
	 *
	 */
	public function addTitre($row){
		$attribs = $this->getAttribs();
		foreach( $this->getData() as $k =>$d){
			$attribs[$k]['title'] = $d[(string)$row];
		}
		$this->setAttribs($attribs);
		$this->getAttribs();
	}
	
	
	/**
	 * affichage du rendu
	 */
	function __toString() {
		return $this->render();
	}
	
	/**
	 * @return the $attribs
	 */
	public function getAttribs() {
		return $this->attribs;
	}
	
	/**
	 * @param field_type $attribs
	 */
	public function setAttribs($attribs) {
		$this->attribs = $attribs;
	}
	
	/**
	 * @return the $options
	 */
	public function getOptions() {
		return $this->options;
	}
	
	/**
	 * @param field_type $option
	 */
	public function setOptions($options) {
		$this->options = $options;
	}
	
	/**
	 * @return the $options[$row]
	 */
	public function getOption($row) {
		return $this->options[$row];
	}
	
	/**
	 * @param field_type $options[$row]
	 */
	public function setOption($row,$option) {
		$this->options[$row] = $option;
	}
	
	/**
	 * @return the $data
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * @param multitype: $data
	 */
	public function setData($data) {
		$this->data = $data;
	}
	
	/**
	 * @return the $ordered
	 */
	public function getOrdered() {
		return $this->ordered;
	}
	
	/**
	 * @param bool: $ordered
	 */
	public function setOrdered($ordered) {
		$this->ordered = $ordered;
	}
	
}
?>