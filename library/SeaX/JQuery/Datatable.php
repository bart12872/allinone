<?php
require_once 'Trait/JQuery.php';
/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_Datatable extends Sea_Datagrid_Html {
	
	use Trait_JQuery;
	
	/**
	 * l'url d'update ajax de la table
	 * 
	 */
	protected $_sAjaxSource = '/tool/datatable';
	
	/**
	 * paramètre de la la requete pour le charegemtn de donnée
	 * 
	 * @var unknown_type
	 */
	static $_requestParams = array();
	
	
	/**
	 * token de chargement des données
	 * 
	 * @var unknown_type
	 */
	protected  $_token = false;
	
	/**
	 * si on affiche la pagination
	 * 
	 * @var boolean
	 */
	protected $_pagination = true;
	
	/**
	 * si on affiche les informations
	 * 
	 * @var unknown_type
	 */
	protected $_information = true;
	
	/**
	 * preconfiguration de l'objet
	 * 
	 * (non-PHPdoc)
	 * @see Sea_Datagrid_Html::_init()
	 */
	public function _init() {
		parent::_init();
		$this->loadDefaultJqueryParams();// chargement des paramètres par default
		
		// gestion de la pagination ajax
		// ne fonctionne que si la class est étendu
		if (($class = get_called_class()) != __CLASS__ ) {
			$token = md5($class . serialize($this->getArgs()));// génération du token
			$data = $this->getArgs();// génération des donnée
			array_unshift($data, $class);
			
			require_once 'Zend/Session/Namespace.php';
			
			$session = new Zend_Session_Namespace(__CLASS__);// stockage en session
			$session->$token = serialize($data);
			$this->_token = $token;
		}
	}
	
	/**
	 * 
	 * set si l'on veux que la pagination soit active
	 * 
	 * @param unknown_type $bool
	 * @return SeaX_JQuery_Datatable
	 */
	public function showPagination($bool) {$this->_pagination = (bool) $bool;return $this;}
	
	/**
	 * si l'on montere les informations
	 * 
	 * 
	 * @param unknown_type $bool
	 * @return SeaX_JQuery_Datatable
	 */
	public function showInformation($bool) {$this->_information = (bool) $bool;return $this;}
	
	
	/**
	 * Definition du nombre d'element par page
	 * (non-PHPdoc)
	 * @see Zend_Paginator::setItemCountPerPage()
	 */
	public function setItemCountPerPage($n = -1) {
		$this->setJqueryParam('iDisplayLength', $n);// paramètre jquery
		return parent::setItemCountPerPage($n);
	}
	
	/**
	 * constrcution d'un extra
	 * 
	 * @param unknown_type $name
	 */
	public function createExtra($name) {
		
		// on tente de recuperer la class de l'extra
		try {$class = sprintf('SeaX_JQuery_Datatable_%s', ucfirst($name));require_once $file = sprintf('%s.php', str_replace('_', '/', $class));}
		catch (Exception $e) {throw new Sea_Exception('L\extra %s n\'existe pas.', $name);}
		
		return new $class();// on retourn l'objet
	}
	
	/**
	 * ajoute un extra 
	 * 
	 * @param unknown_type $extra
	 * 
	 * @return Sea_Datagrid_Html
	 */
	public function addExtra($extra) {return $this->addJqueryParams($extra->generateParams());}
	
	
	/**
	 * Suracharge
	 * ajoute un rendu de couleur pour une liogne en fonction d'une valeur
	 * (non-PHPdoc)
	 * @see Sea_Datagrid_Html::addRowColor()
	 */
	public function addRowColor($field, $map) {
		$js = '';
		foreach($map as $v => $c) {$js .= sprintf('case "%s" : jQuery(r).css("background", "%s");break;',$v, $c);}
		$js = sprintf('function(r,d,i) {switch(d[%d]) {%s}}', $this->getColumnIdByLabel($field), $js);
		$this->setJqueryParam('fnRowCallback',  new Zend_Json_Expr($js));
	}
	
	/**
	 *  ajouter des classe
	 * ajoute un rendu de couleur pour une liogne en fonction d'une valeur
	 * (non-PHPdoc)
	 * @see Sea_Datagrid_Html::addRowColor()
	 */
	public function addRowClass($field, $map) {
		$js = '';
		foreach($map as $v => $c) {$js .= sprintf('case "%s" : jQuery(r).addClass("%s");break;',$v, $c);}
		$js = sprintf('function(r,d,i) {switch(d[%d]) {%s}}', $this->getColumnIdByLabel($field), $js);
		$this->setJqueryParam('fnRowCallback',  new Zend_Json_Expr($js));
	}
	
	/**
	 * effectue le rendu de l'objet
	 * 
	 * (non-PHPdoc)
	 * @see Sea_Datagrid_Abstract::render()
	 */
	public function render(Zend_View_Interface $view = NULL) {
		
		// si aucune colonne est defini, on les definie automatiquement
		if (count($this->getColumns()) == 0) {$this->autogenerate();}
		
		// si ajax
		$total  = $this->getTotalItemCount();
	    $this->setJqueryParam('iDeferLoading', $total);
	    
	    // gestion si la premiere page est la dernière
	    if (!$this->_token || (($total / $this->getItemCountPerPage()) <= 1)) {
// 	        $this->setJqueryParam('iDisplayLength', $this->getTotalItemCount());
//	        $this->setJqueryParam('bPaginate', false);
	    }
		
		//recuperation l'id de la table et ajout de la classe par default
		$this->getTable()->setId($this->getId())->addClass('display');
		
		if ($this->_token) {// chargement ajax
			
			$this->setJqueryParam('sAjaxSource' , $this->_sAjaxSource);
			// message de tableau vide
			$language = $this->getJqueryParam('oLanguage');
			$language['sEmptyTable'] = $this->getNoResults();
			$this->setJqueryParam('oLanguage',  $language);
			
			if (!$this->getJqueryParam('fnServerParams')) {
				$this->setJqueryParam('fnServerParams' , new Zend_Json_Expr(sprintf('function ( aoData ) {aoData.push( { "name": "sToken", "value": "%s" } );}', $this->_token)));
			}
		}
		
		// formatage spécifique du tableau
		if (!$this->_pagination) {$this->setJqueryParam('sDom', str_replace('p', '', $this->getJqueryParam('sDom')));}// pagination
		if (!$this->_information) {$this->setJqueryParam('sDom', str_replace('i', '', $this->getJqueryParam('sDom')));}// information

		// generation du contenu
		$content = $this->_generateHeader() . $this->_generateBody();// .$this->_generateFooter();
		
		// recuperation du helper
		$this->renderJs();
		
		return $this->_table->render($content);
	}
	
	/**
	 * charge un classe depuis un token
	 * @param unknown_type $token
	 */
	static function load($token, $params = []) {
		
		// on charge la session
		$session = new Zend_Session_Namespace(__CLASS__);
		if (!($data = $session->$token)) {throw new Sea_Exception('Aucune configuration de stocké pour cet objet');}
		if (!($data = unserialize($data))) {throw new Sea_Exception('Les paramètres ne sont pas correct, erreur de desérialisation');}
		if (!class_exists($class = array_shift($data))) {
			if(!Zend_Loader_Autoloader::autoload($class)){throw new Sea_Exception('Impossible de charge l\'objet %s', $class);}
		}
		
		if (method_exists($class, 'setRequestParams')) {$class::setRequestParams($params);}
		$reflection = new ReflectionClass($class);
		return $reflection->newInstanceArgs($data);
	}
	
	
	/**
	 * Génération du header
	 * 
	 */
	protected function _generateHeader() {
		
		// initialisation du contenu
		$content = '';
		
		// Génération des header
		if ($this->_hasHeader) {

			// definfition des colonnes pour datatable
			$hJqueryDef = array();
			
			// initilisation du contenu du header
			$headerContent = "";
			
			// traitemznt poûr chacune des colonnes
			$filter = '';// filtre
			$renderFilter = false;

			foreach($this->_columns as $column) {
				
				// construction de l'element
				$header = Sea_Datagrid_Html_Element::factory($this->_header, $column);
				$header	->attrib($column->getHeader()->attrib())
						->setClass($column->getHeader()->getClass())
						->css($column->getHeader()->css());
				
				// on lance tout les callback
				$this->_header->runCallbacks($this, $header);
				$column->getHeader()->runCallbacks($this, $header);
				
				$cJqueryDef = array();
				if ($column->getHeader()->css('width')) {$cJqueryDef['sWidth'] = $column->getHeader()->css('width');$column->getCell()->removeCss('width');}
				if ($column->getCell()->getClass()) {$cJqueryDef['sClass'] = $column->getCell()->renderClass();$column->getCell()->clearClass();}
				$cJqueryDef['bSortable'] = $column->sort();
				$cJqueryDef['bVisible'] = $column->isVisible();
				$cJqueryDef = empty($cJqueryDef) ? null : $cJqueryDef;
				$hJqueryDef[] =$cJqueryDef;

				// on renvoie le rendu
				$content .= ($f = $column->getStrainer()) ? $header->render($f) : $header->render($column->getLabel());
			}
			
			// on ajoute a l'objet les definition des colonne
			$this->setJqueryParam('aoColumns', new Zend_Json_Expr(Zend_Json::encode($hJqueryDef)));
			
			//traiteemnt de la ligne complete
			$headerRow = Sea_Datagrid_Html_Element::factory($this->_headerRow);
			$this->_headerRow->runCallbacks($this, $headerRow);//on lance les callback
			
			$content = $headerRow->render($content);// on effectue le rendu
			if ($renderFilter) {$content = $headerRow->render($filter) . $content;}// si il y a un filtre, on effectue le rendu

			// traitement du header complet
			$head = Sea_Datagrid_Html_Element::factory($this->_head);
			$this->_head->runCallbacks($this, $head);//on lance les callback
			$content = $head->render($content);// on effectue le rendu
			
			// on libere de l'espace memoire
			unset($headerContent);
		}
		return $content;
	}
	
	/**
	 * ajoute des paramètres
	 * 
	 * @param array $params
	 */
	static function addRequestParams(array $params) {
		self::$_requestParams = array_merge($params, self::$_requestParams);
	}
	
	/**
	 * set un paramaètre a passer a l'objet datatable
	 * 
	 * @param String $name
	 * @param String $value
	 * @return SeaX_JQuery_Datatable
	 */
	static function setRequestParam($name, $value) {
		self::$_requestParams[$name] = $value;
	}
	
	/**
	 * enleve tous les paramètre
	 * 
	 *  @return SeaX_JQuery_Datatable
	 */
	static function clearRequestParams() {
		self::$_requestParams = array();
	}
	
	/**
	 * suppression d'un poaramètre
	 * 
	 * @param unknown_type $name
	 */
	static function removeRequestParam($name) {
		if (!empty(self::$_requestParams[$name])) {unset(self::$_requestParams[$name]);}
	}
	
	/**
	 * remplace les paramètre par le tableau passé en paramètre
	 * 
	 * @param unknown_type $params
	 */
	static function setRequestParams(array $params) {
		self::$_requestParams = $params;
	}
	
	/**
	 * renvoie le paramètre 
	 *
	 * @param unknown_type $name
	 */
	static function getRequestParam($name) {
		return array_key_exists($name, self::$_requestParams) ? self::$_requestParams[$name] : false;
	}
	
	/**
	 * renvoie les paramètre du datatable
	 * 
	 */
	static function getRequestParams() {
		return self::$_requestParams;
	}
}