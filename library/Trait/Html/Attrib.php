<?php
/**
 * trait pour la gestion des id et autre attribut
 * 
 * @author jhouvion
 *
 */
trait Trait_Html_Attrib {
	
	/**
	 * container des attribut
	 * 
	 * @var unknown_type
	 */
	protected $_attrib = [];
	
	/**
     * renvoie les style associé a l'objet
     * 
     */
    public function renderAttrib() {
    	
    	$xhtml = '';
        foreach ($this->_attrib as $key => $val) {$xhtml .= " $key=\"$val\"";}
        return trim($xhtml);
    }
    
 	/**
     * set l'id du tag
     * 
     * @param unknown_type $id
     * @return Sea_Decorator_HtmlTag
     */
    public function setId($id) {$this->attrib('id', $id);return $this;}
    
    /**
     * getter pour l'id du tag
     * 
     * @return Ambigous <mixed, NULL, multitype:>
     */
    public function getId() {return $this->attrib('id');}
    
    /**
     * remove attribute
     * 
     */
    public function removeId() {$this->removeAttrib('id');return $this;}
    
   /**
    * ajoute une valeur Attrib
    * 
    * @param unknown_type $key
    * @param unknown_type $value
    * 
    * @return Self or String
    */
    public function attrib($key = false, $value = false) {
    	// cas du getter générale
    	if ($key === false) {return $this->_attrib;}
    	
    	// cas du setter générale
   		if ( is_array($key)) {$this->_attrib = $key;return $this;}
    	
    	// cas  du getter
    	if ($value === false) {return array_key_exists($key, $this->_attrib) ? $this->_attrib[$key] : false;}

    	// cas du setter
    	else { $this->_attrib[$key] = $value;return $this;}
    }
    
    /**
     * enleve un propieté Attrib
     * 
     * @param unknown_type $key
     * @return Self
     */
    public function removeAttrib($key) {
    	if (array_key_exists($key, $this->_attrib)) {$this->_attrib[$key];}
    	return $this;
    }
    
    /**
     * remove all Attrib
     * 
     * @return Self
     */
    public function clearAttrib() {
    	$this->_attrib = [];
    	return $this;
    }
}