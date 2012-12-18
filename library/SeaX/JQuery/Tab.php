<?php
require_once 'Trait/View.php';
require_once 'Trait/JQuery.php';
/**
 * classe creation des panel
 * 
 * @author jhouvion
 *
 */
class SeaX_JQuery_Tab {
	
	use Trait_View;
	use Trait_JQuery;

	/**
	 * id pour le Panel
	 * 
	 * @var unknown_type
	 */	
	protected $_id = '';
	
	/**
	 * Helper pour chaque panel
	 * 
	 * @var unknown_type
	 */
	protected $_pane = 'seaTabPane';
	
	/**
	 * Contenu des differente tab
	 * 
	 * @var unknown_type
	 */
	protected $_tabs = array();
	
	/**
	 * Constrcteur
	 * 
	 * @param unknown_type $id
	 */
	public function __construct($id) {
		
		$this->_id = strval($id);
		$this->setJqueryHelper('seaTabContainer');
	}
	
	/**
	 * ajoute une table
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $content
	 * @return self
	 */
	public function addTab($title, $content, $ank = false) {$this->_tabs[] = array('title' => $title, 'content' =>$content, 'ank' => $ank);return $this;}
	
	/**
	 * ajoute une url (ajax)
	 * 
	 * @param unknown_type $title
	 * @param unknown_type $url
	 */
	public function addUrl($title, $url, $ank = false) {$this->_tabs[] = array('title' => $title, 'url' => $url, 'ank' => $ank);return $this;}
	
	/**
	 * renvoie les tab
	 * 
	 */
	public function getTabs(){return $this->_tabs;}
	
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_pane
	 */
	public function getPane() {
		return $this->_pane;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
		return $this;
	}

	/**
	 * @param field_type $_pane
	 */
	public function setPane($_pane) {
		$this->_pane = $_pane;
		return $this;
	}

	/**
	 * rendu
	 */
	public function render() {
		
		$id = $this->getId();// recuperation de l'id
 		$pane = $this->getPane();// objet de generation des pane
 		$container = $this->getJqueryHelper();// objet de genration des container
 		$model = false;// si on a des model en pane 
     
		// construction des panes
 		foreach ($this->getTabs() as $tab) {
 			$content = '';// initialisation du contenu
 			$options = array('title' => $tab['title']);// mise en place du titre 
 			if (!empty($tab['ank'])) {$options += array('ank' => $tab['ank']);}// mise en place due l'identifiant
 			
 			if (!empty($tab['content'])) {$content = $tab['content'];} 
 			elseif (!empty($tab['url'])) {$options['contentUrl'] = $tab['url'];}
 			
 			$content = !empty($content) ? $content : '';
 			$this->getView()->$pane($id,$content, $options);
 		}
 		
 		return $this->getView()->$container($id, $this->getJqueryParams(), array(), $this->getJavascriptVarName());
	}
	
	/**
	 * rendu alias
	 */
	public function __toString() {return $this->render();}
}
?>