<?php
trait Trait_JQuery {
	
	/**
	 * helper pour le rendu
	 * 
	 * @var unknown_type
	 */
	protected $_jQueryHelper;
	
	/**
	 * nom de la variable javascript
	 * 
	 * @var unknown_type
	 */
	protected $_javascriptVarName = false;
	
	/**
	 * paramètre par default
	 * 
	 * @var unknown_type
	 */
	static $_jqueryDefaultParams = [];
	
	
	/**
	 * paramètre jquery utilisé
	 * 
	 * @var unknown_type
	 */
	protected $_jqueryParams = array();
	
	/**
	 * charge les paramètre par default
	 * 
	 */
	public function loadDefaultJqueryParams() {
		$this->_jqueryParams = self::$_jqueryDefaultParams;
	}
	
	public function renderJs() {
		if (!($helper = $this->getJqueryHelper())){throw new Sea_Exception('Pas de helper defini');}
	    return $this->getView()->{$helper}("#" . $this->getId(), $this->getJqueryParams(), $this->getJavascriptVarName());
	}
	
	/**
	 * ajoute des paramètres
	 * 
	 * @param array $params
	 */
	public function addJqueryParams(array $params) {
		$this->_jqueryParams = array_merge($this->_jqueryParams, $params);
		return $this;
	}
	
	/**
	 * set un paramaètre a passer a l'objet datatable
	 * 
	 * @param String $name
	 * @param String $value
	 * @return SeaX_JQuery_Datatable
	 */
	public function setJqueryParam($name, $value) {
		$this->_jqueryParams[$name] = $value;
		return $this;
	}
	
	/**
	 * enleve tous les paramètre
	 * 
	 *  @return SeaX_JQuery_Datatable
	 */
	public function clearJqueryParams() {
		$this->_jqueryParams = array();
		return $this;
	}
	
	/**
	 * suppression d'un poaramètre
	 * 
	 * @param unknown_type $name
	 */
	public function removeJqueryParam($name) {
		if (!empty($this->_jqueryParams[$name])) {unset($this->_jqueryParams[$name]);}
		return $this;
	}
	
	/**
	 * remplace les paramètre par le tableau passé en paramètre
	 * 
	 * @param unknown_type $params
	 */
	public function setJqueryParams(array $params) {
		
		$this->_jqueryParams = $params;
		return $this;
	}
	
	/**
	 * renvoie le paramètre 
	 *
	 * @param unknown_type $name
	 */
	public function getJqueryParam($name) {
		return empty($this->_jqueryParams[$name]) ? false : $this->_jqueryParams[$name];
	}
	
	/**
	 * renvoie les paramètre du datatable
	 * 
	 */
	public function getJqueryParams() {
		return $this->_jqueryParams;
	}
	
	/**
	 * set un paramaètre a passer a l'objet datatable
	 * 
	 * @param String $name
	 * @param String $value
	 * @return SeaX_JQuery_Datatable
	 */
	static function setJqueryDefaultParam($name, $value) {
		self::$_jqueryDefaultParams[$name] = $value;
	}
	
	/**
	 * enleve tous les paramètre
	 * 
	 *  @return SeaX_JQuery_Datatable
	 */
	static function clearJqueryDefaultParams() {
		self::$_jqueryDefaultParams = array();
	}
	
	/**
	 * suppression d'un poaramètre
	 * 
	 * @param unknown_type $name
	 */
	static function removeJqueryDefaultParam($name) {
		if (!empty(self::$_jqueryDefaultParams[$name])) {unset(self::$_jqueryDefaultParams[$name]);}
	}
	
	/**
	 * remplace les paramètre par le tableau passé en paramètre
	 * 
	 * @param unknown_type $params
	 */
	static function setJqueryDefaultParams(array $params) {
		self::$_jqueryDefaultParams = $params;
	}
	
	/**
	 * renvoie le paramètre 
	 *
	 * @param unknown_type $name
	 */
	static function getJqueryDefaultParam($name) {
		return empty(self::$_jqueryDefaultParams[$name]) ? false : self::$_jqueryDefaultParams[$name];
	}
	
	/**
	 * renvoie les paramètre
	 * 
	 */
	static function getJqueryDefaultParams() {
		return self::$_jqueryDefaultParams;
	}
	
	/**
	 * @return the $_jQueryHelper
	 */
	public function getJQueryHelper() {
		return $this->_jQueryHelper;
	}

	/**
	 * @param unknown_type $_jQueryHelper
	 */
	public function setJQueryHelper($_jQueryHelper) {
		$this->_jQueryHelper = $_jQueryHelper;
		return $this;
	}

	/**
	 * @return the $_javascriptVarName
	 */
	public function getJavascriptVarName() {
		return $this->_javascriptVarName;
	}
	
	/**
	 * @param unknown_type $_javascriptVarName
	 */
	public function setJavascriptVarName($_javascriptVarName) {
		$this->_javascriptVarName = $_javascriptVarName;
		return $this;
	}
}