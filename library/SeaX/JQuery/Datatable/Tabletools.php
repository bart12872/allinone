<?php

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Datatable_Tabletools {
	
	
	/**
	 * dom a appliquer a la table pour l'affichage du plugin
	 * 
	 * 
	 * @var unknown_type
	 */
	protected $_sDom = '<"H"Tfr>t<"F"ip>';
	
	/**
	 * paramètre tu table tools
	 * 
	 * @var unknown_type
	 */
	protected $_params = array();
	
	
	/**
	 * on enleve tout les boutons
	 * 
	 * @return SeaX_JQuery_Datatable_Tabletools
	 */
	public function clearButton() {
		$this->_params['aButtons'] = array();
		return $this;
	}
	
	/**
	 * renvoie le bouton
	 * 
	 * @param unknown_type $i
	 * @throws Sea_Exeption
	 */
	public function getButtonByIndex($i) {
		
		// on veirfie que l'index existe
		if (!array_key_exists($i, $this->_params['aButtons'])) {require_once 'Sea/Exception.php';throw new Sea_Exception('Aucun bouton avec l\'index %s',$i);}
		
		// on retourne lebouton
		return $this->_params['aButtons'][$i];
	}
	
	/**
	 * genere les paraètre a jouter a la table
	 * 
	 */
	public function generateParams() {
		return array('sDom' => $this->_sDom, 'oTableTools' => $this->_params);
	}
	
	/**
	 * ajoute un bouton d'axction
	 * 
	 * 
	 * @param unknown_type $sButtonText
	 * @param unknown_type $fnClick
	 * 
	 * @return SeaX_JQuery_Datatable_Tabletools
	 */
	public function addButton($sButtonText, $fnClick, $icon = false) {
		
		// création et ajout du bouton
		$table = array(	'sExtends' => 'text', 
						'sButtonText' => $sButtonText,
						'sButtonText' => $sButtonText,
						'sButtonClass' => 'button',
						'fnClick' =>  new Zend_Json_Expr(sprintf('function(){%s}', $fnClick)));
// 		if (!empty($icon)) {$table['fnInit'] = new Zend_Json_Expr(sprintf('function(b){jQuery(b).button( "option", "icons", {primary:"%s"} ).refresh();}', $icon));}

		// on rinsett les nouveau paramètre
		$this->_params['aButtons'][] =  $table;
		
		return $this;
	}
	
	/**
	 * ajoute un bouton de type lien
	 * 
	 * @param unknown_type $sButtonText
	 * @param unknown_type $url
	 */
	public function addLink($sButtonText, $url, $icon = false) {
		return $this->addButton($sButtonText, sprintf('jQuery.location(\'%s\');', $url),$icon);
	}
}

?>