<?php
/**
 * traite pour la gestion de css et class
 * 
 * @author jhouvion
 *
 */
trait Trait_Html_Css {
	
	/**
	 * container des style
	 * 
	 * @var unknown_type
	 */
	protected $_css = [];
	
	/**
	 * container des class
	 * 
	 * @var unknown_type
	 */
	protected $_class = [];
	
	/**
     * renvoie les style associé a l'objet
     * 
     */
    public function renderCss() {
    	$style = '';
    	foreach ((array) $this->_css as $k => $v) {$style .= empty($v) ? '' : $k . ':' . $v . ';';}
    	return trim($style);
    }
    
   /**
    * ajoute une valeur css
    * 
    * @param unknown_type $key
    * @param unknown_type $value
    * 
    * @return Self or String
    */
    public function css($key = false, $value = false) {
    	
    	// cas du getter générale
    	if ($key === false) {return $this->_css;}
    	
    	// cas du setter générale
   		if ( is_array($key)) {$this->_css = $key;return $this;}
    	
    	// cas  du getter
    	if ($value === false) {return array_key_exists($key, $this->_css) ? $this->_css[$key] : false;}

    	// cas du setter
    	else { $this->_css[$key] = $value;return $this;}
    }
    
    /**
     * enleve un propieté css
     * 
     * @param unknown_type $key
     * @return Self
     */
    public function removeCss($key) {
    	if (array_key_exists($key, $this->_css)) {$this->_css[$key];}
    	return $this;
    }
    
    /**
     * remove all css
     * 
     * @return Self
     */
    public function clearCss() {
    	$this->_css = [];
    	return $this;
    }
    
    /**
     * renvoie les classe formatté
     * 
     */
  	public function renderClass() {
    	return trim(implode(' ',$this->_class));
    }
    
    /**
     * ajoute une classe au conteiner
     * 
     * @param unknown_type $class
     */
    public function addClass($class) {
    	
    	// si la classe n'existe pas deja, on la rajoute
    	if (!$this->hasClass($class)) {$this->_class[] = $class;}
    	
    	return $this;
    }
    
    /**
     * setter general pour les class
     * 
     * @param array $class
     * @return Trait_Html_Css
     */
    public function setClass(array $class) {
    	$this->_class = $class;
    	return $this;
    }
    
    /**
     * renvoie toute les classe sous forme de tableau
     * 
     */
    public function getClass() {
    	return $this->_class;
    }
    
    /**
     * verifie si une classe exist dejà
     * 
     * @param unknown_type $class
     * @return bool
     */
    public function hasClass($class) {
    	return (bool) array_search($class, $this->_class) !== false;
    }
    
    /**
     * supprime une classe
     * 
     */
    public function removeClass($class) {
    	
    	// si la classe existe, on la supprime
    	if (($index = array_search($class, $this->_class)) !== false) {unset($this->_class[$index]);sort($this->_class);}
    	
    	return $this;
    }
    
    /**
     * suppression de toute les class
     * 
     * @return Trait_Html_Css
     */
    public function clearClass() {
    	$this->_class = [];
    	return $this;
    }
}