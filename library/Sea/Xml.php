<?php 

class Sea_Xml {
	
	/**
	 * contenue du xml parser
	 * 
	 * @var unknown_type
	 */
	protected $_data = array();
	
	/**
	 * constrcuteur
	 * 
	 * @param unknown_type $xml
	 */
	public function __construct($xml) {
		
		//construction du reader
		$reader = new XMLReader();
		$reader->XML($xml);
		
		// recupération des donnée
		$this->_data = $this->_parse($reader);
	}
	
	/**
	 * factpry depuis chaine xml
	 * 
	 * @param unknown_type $xml
	 */
	static function xml($xml) {return new Sea_XML($xml);}
	
	/**
	 * factory depuis un fichier
	 * 
	 * @param unknown_type $path
	 */
	static function file($path) {
		
		if (file_exists($path) && $xml = file_get_contents($path)) {return new Sea_XML($xml);} 
		else {throw new Exception('Impossible d\'acceder au fichier : ' .$path );}
		
	}
	
	/**
	 * on parse le xml et le place dans un tableau afin d'en facilité l'exploitation
	 * 
	 * @param unknown_type $p
	 */
	protected function _parse($p){
		// initialisation
		$depth = 0;
		$return = array();
		
		// incrementation de la lecture
		while($p->read()) {
			
			//si profonceur inferieur, on renvoie les donnée
			if ($depth > $p->depth) {$return = (array) $data;break;}
			
			// Si elzment
			if ($p->nodeType == XMLREADER::ELEMENT){
				
				// initialisation 
				$data = array();
				$attributes = array();
				$name = $p->name;
				$depth = $p->depth;
	
				// recuperation du noeud
				$node = $p->expand();
				
				// recuperation des attributs
				if ($node->hasAttributes()) {while($p->moveToNextAttribute()) {$attributes[':'.$p->name] = $p->value;}}
				
				// si balise autofermé
				if (!$node->hasChildNodes()) {$data[$name][] = $attributes;continue;}
				else {
					// on verifie si l'element est un noeud d'autre elements
					// evite le traietemtn des saut de ligne et espace blanc
					$isNode = false;
					$child = $node->childNodes;
					for($i = 0; $i < $child->length; $i++) {if ($child->item($i)->nodeType == XMLREADER::ELEMENT) {$isNode = true;break;}}
					
					// si on a bine un neoud, on recupere recursivement les donnée
					if ($isNode) {$data = $this->_parse($p);if (!empty($attributes)) {foreach ( $attributes as $k => $v) {$data[$k] = $v;}}}
				}
			}
			
			// si contenu text
	      	if ($p->nodeType == XMLREADER::TEXT){$data['#'] = $p->value;$p->read();}
	      	
	      	// si fermetur element
	      	if ($p->nodeType == XMLREADER::END_ELEMENT){$return[$p->name][] = $data;$data = $return;}	
		}
	
		// retour des donnée
		return $return;
	}
	
	/**
	 * getter pour les donnée
	 * 
	 */
	public function getData() {return $this->_data;}
	
}