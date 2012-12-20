<?php

class Application_Model_Tvdb {
    
    /**
     * Cle api
     * @var unknown_type
     */
    protected $_key = '';
    
    /**
     * language des resultats
     * 
     * @var unknown_type
     */
    protected $_language = 'fr';

    /**
     * divers constant
     * 
     * @var unknown_type
     */
    const POSTER = 'poster';
    const FANART = 'fanart';
    
    /**
     * url de l'api
     * 
     * @var unknown_type
     */
    static public $url = 'http://www.thetvdb.com/api/';
    
    
    /**
     * constrcution
     * 
     * @param unknown_type $api
     */
    public function __construct($_apikey, $language = false) {
        
        // stocke la cle
        $this->setKey($_apikey);    
        
        // si un langauge est spécifié, on le met
        if (!empty($language)) {$this->setLanguage($language);}
    }
    
    
    /**
     * recherche de serie
     * 
     * @param unknown_type $seriesname
     * @param unknown_type $language
     * @return multitype:
     */
    public function getSeries($seriesname, $language = false) {
        
        // creation de l'url
        $url = sprintf('%sGetSeries.php?seriesname=%s&language=%s', self::$url, urlencode($seriesname), $language ? $language : $this->getLanguage());

        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        // traietemnt du resultat
        $body = $response->getBody();
// 		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		// formatage des données
		$data = empty($body['Data']['Series']) ?  array() : $body['Data']['Series'];
		if (!empty($data)  && !is_array(current($data))){$data = array($data);}
        
		return $data;
    }
    
    /**
     * charge une serie
     * 
     * @param unknown_type $id
     * @param unknown_type $language
     * @return multitype:
     */
    public function serie($id, $language = false) {
        
         // creation de l'url
        $url = sprintf('%s%s/series/%s/%s.xml', self::$url, $this->getKey(), $id, $language ? $language : $this->getLanguage());
        
        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        
        // traietemnt du resultat
        $body = $response->getRawBody();
        $body = $response->decodeChunkedBody($body);
		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		return empty($body['Data']['Series']) ?  array() : $body['Data']['Series'];
    }
    
    /**
     * Charge une serie en base de donnée
     * 
     * @param unknown_type $id
     */
    public function importSerie($id, $language = false) {
        
        // recuperation des finormation de la serie
        $data = $this->serie($id, $language);
        
        
		dd($data);
        
    	// on format les image
		if (!empty($data['banner'])) {$data['banner'] = $this->cacheBanner($data['banner']);}
		if (!empty($data['fanart'])) {$data['fanart'] = $this->cacheFanart($data['fanart']);}
		if (!empty($data['poster'])) {$data['poster'] = $this->cachePoster($data['poster']);}
        
        // insertion de la serie
        return $this;
    }
    
    public function all($serie, $language = false) {
        // creation de l'url
        $url = sprintf('%s%s/series/%s/all/%s.xml', self::$url, $this->getKey(), $serie, $language ? $language : $this->getLanguage());
        
        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        
        // traietemnt du resultat
        $body = $response->getRawBody();
        $body = $response->decodeChunkedBody($body);
		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		return empty($body['Data']) ?  array() : $body['Data'];
    }
    
    /**
     * recupere les infos
     * 
     * @param unknown_type $serie
     * @param unknown_type $season
     * @param unknown_type $episode
     * @param unknown_type $language
     * @return Ambigous <multitype:, mixed, NULL, multitype:Ambigous <mixed, NULL> >
     */
    public function info($serie, $season = false, $episode = false, $language = false) {
    	
    	  // creation de l'url
        $url = sprintf('%s%s/series/%s/%s/%s/%s.xml', self::$url, $this->getKey(), $serie,$season,  $episode, $language ? $language : $this->getLanguage());
        
        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        
        // traietemnt du resultat
        $body = $response->getRawBody();
        $body = $response->decodeChunkedBody($body);
		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		return empty($body['Data']) ?  array() : $body['Data'];
    	
    }
    
    /**
     * recupere les bannière 
     * 
     * @param unknown_type $serie
     * @return Ambigous <mixed, NULL, multitype:, multitype:Ambigous <mixed, NULL> , StdClass, multitype:Ambigous <mixed, multitype:, multitype:Ambigous <mixed, NULL> , NULL> , boolean, number, string, unknown>
     */
    public function banner($serie, $type = false) {
    	
    	 // creation de l'url
        $url = sprintf('%s%s/series/%s/banners.xml', self::$url, $this->getKey(), $serie);
        
        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        
        // traietemnt du resultat
        $body = $response->getRawBody();
        $body = $response->decodeChunkedBody($body);
		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		// recuperation du contenu 
		$banner =  empty($body['Banners']) ?  array() : $body['Banners'];
		$banner =  empty($banner['Banner']) ?  array() : $banner['Banner'];

		$result = [];
		foreach($banner as $row) {
			if ($type && $row['BannerType'] != $type) {continue;}
			$image = false;
			switch($row['BannerType']) {
				case self::FANART : $image = $this->cacheFanart($row["BannerPath"] );break;
				case self::POSTER : $image = $this->cachePoster($row["BannerPath"] );break;
			}
			if ($image) {$return [] = $image;}
		}
		
		return $return;
    }
    
    /**
     * renvoie l'id de l'episode en fonction de la serie, de la saison et de l'episode
     * 
     * @param unknown_type $serie
     * @param unknown_type $season
     * @param unknown_type $episode
     */
    public function findEpisode($serie, $season, $episode) {
     	$data = $this->all($serie);
        
        // formtage des episode
		$select = array();
        if (!empty($data['Episode']) && is_array($data['Episode'])) {
        
			foreach($data['Episode'] as $row) {
			    // filtre des saison
			    if ($season && $season != $row['SeasonNumber']){continue;}
			 	if ($episode && $episode != $row['EpisodeNumber']){continue;}
				
			 	// on renvoie l'episode
			    return $row['id'];
            }
        }
        return false;
    }
    
    /**
     * liste les episodes d'une serie
     * 
     * @param unknown_type $serie
     * @param unknown_type $season
     * @param unknown_type $language
     */
    public function listEpisode($serie, $season = false, $episode = false, $language = false) {
        
        $data = $this->all($serie, $language);
        
        // formtage des episode
		$select = array();
        if (!empty($data['Episode']) && is_array($data['Episode'])) {
        
			foreach($data['Episode'] as $row) {
			    // filtre des saison
			    if ($season && $season != $row['SeasonNumber']){continue;}
			 	if ($episode && $episode != $row['EpisodeNumber']){continue;}
				
			    // cgarhemùent d'un nouvelle saison
			    if (empty($select['Saison ' . $row['SeasonNumber']])) {$select['Saison ' . $row['SeasonNumber']] = array();}
	               
				try { // on essaie de recuperer la date
					$date = new Zend_Date($row['FirstAired']);
					$date = $date->get('dd/MM/yyyy');
				} catch (Exception $e) {$date = 'inconnue';}
	               
				$select['Saison ' . $row['SeasonNumber']][$row['id']] = sprintf('S%02dE%02d : %s - %s',$row['SeasonNumber'], $row['EpisodeNumber'], $date, $row['EpisodeName']);
            }
        }
        
        return $select;
    }
    
    /**
     * charge les information d'un épisode
     * 
     * @param unknown_type $serie
     * @param unknown_type $season
     * @param unknown_type $episode
     * @param unknown_type $language
     */
    public function episode($id, $language = false) {
        
         // creation de l'url
        $url = sprintf('%s%s/episodes/%s/%s.xml', self::$url, $this->getKey(), $id, $language ? $language : $this->getLanguage());
        
        // on lance la requete
        $client = new Zend_Http_Client($url);
        $response = $client->request();
        
        // traietemnt du resultat
        $body = $response->getBody();
// 		$body = $response->decodeGzip($body);
		$body = Zend_Json::fromXml($body, true);
		$body = Zend_Json::decode($body);
		
		return empty($body['Data']['Episode']) ?  array() : $body['Data']['Episode'];
    }
    
    /**
     * importe un episode*
     * 
     * @param unknown_type $id
     * @param unknown_type $language
     * @return Extra_Serie_Model_Tvdb2
     */
 	public function importEpisode($id, $language = false) {
        
        // recuperation des finormation de la serie
        $data = $this->episode($id, $language);
        
        dd($data);
        
    	// on format les image
 		if (!empty($data['filename'])) {$data['filename'] = $this->cacheImage($data['filename'], getregistry('episode.width', 'thetvdb'));}
        
        return $this;
    }
    
    
	/**
	 * met enacche une banière
	 * 
	 * @param unknown_type $image
	 */
	public function cacheBanner($image) {return $this->cacheImage($image, getregistry('banner.width', 'thetvdb'));}
	
	/**
	 * met enacche un fanart
	 * 
	 * @param unknown_type $image
	 */
	public function cacheFanart($image) {return $this->cacheImage($image, getregistry('fanart.width', 'thetvdb'));}
	
	/**
	 * met en cahe le poster
	 * 
	 * @param unknown_type $image
	 */
	public function cachePoster($image) {return $this->cacheImage($image, getregistry('poster.width', 'thetvdb'));}
	
    
	/**
	 * charge l'image en cache
	 * 
	 * @param unknown_type $image
	 * @throws Sea_Exception
	 * @throws Exception
	 */
	public function cacheImage($image, $width = 0, $height = 0) {
		
		$filepath = getregistry('url.banner', 'thetvdb') . $image;
		$destination = getregistry('directory.cache', 'thetvdb') . $image;

		if(!file_exists($destination) && !is_file($destination)) { // on verifie que l'url existe
		
			// on verifie que le repertoire de destination existe
			if(!is_dir(dirname($destination)) && @fopen($filepath, 'r')) {
				if (!rmkdir(dirname($destination), 0755)) {
					throw new Sea_Exception('Impossible de créer le repertoire : %s', dirname($destination));
				}
			}
			
			// on resize l'image
			$img = new Imagick($filepath);//ouverture de l'image
			if (!empty($width) || !(empty($height))) {$img->scaleimage($width, $height);}
			$img->writeimage($destination);// inscriptiond e l'image
			chmod($destination, 0755);// attributiond es droit a l'image
		}
		
		return file_exists($destination) ?  getregistry('url.cache', 'thetvdb') . $image : false;
	}
    
	/**
	 * @return the $_key
	 */
	public function getKey() {
		return $this->_key;
	}

	/**
	 * @return the $_language
	 */
	public function getLanguage() {
		return $this->_language;
	}

	/**
	 * @param string $_key
	 */
	public function setKey($_key) {
		$this->_key = $_key;
		return $this;
	}

	/**
	 * @param string $_language
	 */
	public function setLanguage($_language) {
		$this->_language = $_language;
		return $this;
	}
}
?>