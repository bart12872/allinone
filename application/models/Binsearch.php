<?php

/**
 * gere l'interraction avec le site binsearch
 * @author jhouvion
 *
 */
class Application_Model_Binsearch {
    
	/**
	 * 
	 * renvoie les donnée depuis l'id d'un group deja rentré
	 * 
	 * 
	 * @param unknown_type $id
	 * @throws Sea_Exception
	 */    
    public function collectionFromId($id) {
        
        $table = new Application_Model_DbTable_BinsearchGroup();
        $rowset = $table->find($id);
        
        if (!$rowset->valid()) {throw new Sea_Exception('L\'entrée %s n\'existe pas', $id);}
        
        return $this->collection($rowset->current()->binsearch_group_label, $rowset->current()->rss);
    }
    
    /**
     * formlate la taille en Ko
     * 
     * @param unknown_type $size
     */
    protected function _formatSize($size) {
    	
    	$mutiple = ['K', 'M', 'G'];$return = false;
		if (preg_match('#^(?<value>[\d\.]+)(?<multiple>G|M)B$#i',$size, $match)) {
			$return = round($match['value'] * pow(1024, array_search($match['multiple'], $mutiple)));
		}
		
		return $return;
    }
    
    /**
     * recupere les informations d'un flux rss
     * 
     * @param unknown_type $i
     */
    public function collection($group, $max = 50) {
        
        $result = array();// initialisation du resultat
        
     	// lecture des flux des log svn
        $channel = new Zend_Feed_Rss(getview()->url(array('group' => $group, 'max' => $max), 'binsearch-rss-rss', true));
        
        foreach($channel as $c) {
            
            // formatage de la description pour l'ananlyse
            $desc = str_replace(array(' ', '&nbsp;'), '', htmlspecialchars_decode($c->description()));
            
            // recuperation des infrmation dans la description
            if (!preg_match('#<span[^>]*>[^"]+"(?<detail>[^"]+)"[^:]+:(?<size>[^,]+),[^\d]+(?<part>\d+/\d+)[^"]+("(?<nfo>[^"]+)")?.*</span>#i', $desc, $matches))
            	{continue;}// si on match pas, on zap
            	
            // si release pas complete, on passe	
            eval("\$pass = (".$matches['part'].") < 1;");
            if($pass) {continue;}
            
            $data = ['description' => $c->description()];
            //on ne veux que les variable nommé
            foreach($matches as $i => $m) {if (is_numeric($i)){continue;}$data[$i] = $m;}
            
            // on formate la taille (si Ko on ne prend pas)
			if (!($data['size'] = $this->_formatSize($data['size']))) {continue;}
            $data['title'] = $c->title();// recuperation du titre
            $data['nzb'] = $c->link();// lien vers le fichier nsb
            $date = new Zend_Date($c->pubDate(),Zend_Date::RFC_2822);
            $data['date'] = $date->getTimestamp();
            
            //recherche de l'identifiant
            if (!preg_match('#^[^&]+&(?<id>\d+)=1#i', $data['nzb'], $matches)) {continue;}
            $data['binsearch_collection_id'] = $matches['id'];
            
            $result[] = $data;// ajout de l'entrée a la pile de resultat
        }

    	return $result;
    }
    
    /**
     * 
     * importation en base de donnée des flux
     * 
     * @param unknown_type $id
     */
    public function importCollection($id) {
    	
    	$db = getconnection();// récupération de la connexion
        
        // recuperation de la table de gestion
        $table = new Application_Model_DbTable_BinsearchCollection();
        
        // enregistrement des feed trouvé
        foreach($this->collectionFromId($id) as $row) {
            // on verifie si le feed exist deja
            $rowset = $table->find($row['binsearch_collection_id']);
            if ($rowset->valid()) {continue;}// s'il existe deja on passe a l'enregistrement suivant
            
            $row['date'] = new Zend_Db_Expr(sprintf('FROM_UNIXTIME("%s")', $row['date']));
            $row['binsearch_group_id'] = $id;
            $row = $table->assoc($row);
            $table->insert($row);
        }
        
        // mise a jour de kla date du denrie refresh
		$db->update('binsearch_group', ['refreshed'=> new Zend_Db_Expr('NOW()')], $db->quoteInto('binsearch_group_id=?', $id));
        
        return $this;// on renvoie l'objet courant
    }
}
?>