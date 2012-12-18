<?php
/**
 * recupere une constaznt a partir de son adresse
 * 
 */
function getregistry($s, $namespace = 'constant') {
	
	// verification de l'existance du namespace
	if (!Zend_Registry::getInstance()->isRegistered($namespace)) {throw new Sea_Exception('Impossible de charger le namespace : %s', $namespace);}
	
	// recuperation du namspace
	$return = Zend_Registry::getInstance()->get($namespace);
	
	if ($s) {
    	// on parcours les elements
    	foreach ((array) explode('.', $s) as $v) {
    		
    		// si erreur on renvoie false
    		if (!isset($return->$v)) return false;
    		
    		// sinon on avance dans l'arborescence
    		$return = $return->$v;	
    	}
	}
	

	// on retourne la valeur si il y en a une
	return ($return == Zend_Registry::getInstance()->get($namespace)) && ($s !== false) ? false : $return;
}

/**
 * renvoie une instance de 
 * 
 * 
 * @return Zend_View
 */
function getview() {
    return Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
}

/**
 * Renvoie la connexion courante
 * 
 */
function getconnection() {return Zend_Db_Table::getDefaultAdapter();}


/**
 * Liste de fonctions utiles
 * 
 * 
 */

/**
 * raccourcie vers Zend_Debug::dump()
 * 
 * @param $var
 * @param $label
 * @param $echo
 */
function d($var, $label = "") {
	
	// recuperation de la pile d'execution
	$debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	
	if (DEBUG_MODE) {
		// si label vide on affiche
		if (empty($label)) {
			foreach($debug as $d) {
				if ($d['function'] != __FUNCTION__ && $d['function'] != 'dd') { continue;}
				$label = str_replace(realpath(APPLICATION_PATH .'/..'), '', $d['file']);
				$label .= ' (' . $d['line'] . ')';
			}
		}
	
		require_once 'Zend/Debug.php';
		Zend_Debug::dump($var, $label, true);
	}
	
	return $debug;
}

/**
 * debug; die;
 * 
 * @param  $var
 * @param  $label
 * @param  $echo
 */
function dd($var, $label = "", $backtrace = false) {
	
	$debug = d($var,$label);// on affiche la valeur
	if ($backtrace) d($debug, 'BACKTRACE');// on afiiche la pile si demandé
	die;
}

function ieversion() {
  $match=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$reg);
  if($match==0)
    return -1;
  else
    return floatval($reg[1]);
}

/**
 * efface un repertoire recursivement
 * 
 * @param unknown_type $dir
 */
function rrmdir($dir) {
	 
	if (!is_dir($dir)) {return false;}
	
	$objects = scandir($dir); 	
	
	foreach ($objects as $object) { 
		if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") { if(!@rrmdir($dir."/".$object)) return false;} 
				else {if(!@unlink($dir."/".$object))return false;}
		} 
	}
	 
	reset($objects); 
	if (!@rmdir($dir)) return false; 
	 
	return true;
} 

/**
 * creer un repertoir recursivement
 * @param unknown_type $path
 * @param unknown_type $mode
 */
function rmkdir($path, $mode = 0777) {
	
    $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
    $e = explode("/", ltrim($path, "/"));
    if(substr($path, 0, 1) == "/") {$e[0] = "/".$e[0];}
    
    $c = count($e);
    $cp = $e[0];
    for($i = 1; $i < $c; $i++) {
        if(!is_dir($cp) && !mkdir($cp, $mode)) {return false;}
        $cp .= "/".$e[$i];
    }
    
    return file_exists($path) ? true : mkdir($path, $mode);
}

function chromelog($var, $key = null, $method = null) {
	if (DEBUG_MODE) {
		if ($var instanceof Exception) {
			$e = $var;
			$var = $e->getMessage();
			$key = is_null($key) ? 'Exception raised in file ' . $e->getFile() . ' on line ' . $e->getLine() . ' with code ' . $e->getCode() . ':' : $key;
			$method = is_null($method) ? 'error' : $method;
		}
		
		$method = is_null($method) ? 'log' : $method;
		$method = strtolower($method);
		
		$var = (is_object($var)) ? (array) $var : $var;
		if ($key) {
			ChromePhp::$method($key, $var);
		} else {
			ChromePhp::$method($var);
		}
	}
}

/**
 * Remplace les accent dans une chaine
 * @param String $sChaine
 */
function replace_accent($sChaine) {
	
    $sChaine = str_replace(
        array(
            'à', 'â', 'ä', 'á', 'ã', 'å',
            'î', 'ï', 'ì', 'í', 
            'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
            'ù', 'û', 'ü', 'ú', 
            'é', 'è', 'ê', 'ë', 
            'ç', 'ÿ', 'ñ', 
        ),
        array(
            'a', 'a', 'a', 'a', 'a', 'a', 
            'i', 'i', 'i', 'i', 
            'o', 'o', 'o', 'o', 'o', 'o', 
            'u', 'u', 'u', 'u', 
            'e', 'e', 'e', 'e', 
            'c', 'y', 'n', 
        ),
        $sChaine
    );
    return $sChaine;
}


/**
 * Echappe le fin de commentaire pour mettre dans un heredoc javascript
 * 
 * @param string $str
 */
function js_heredoc_escape($str) {
	return preg_replace('/\*\//', '\*\/', $str);
}

/**
 * Récupère les valeu d'un champ enum dans une base de donnée mysql
 * 
 * @param String $table
 * @param String $field
 * @return Array
 */
function get_mysql_enum($table, $field) {
	
	// initialisation du resultat
	$type = array();
	
	// recuperation de la connexion ala base de donnée
	$db = getconnection();	
	
	// recupération de la declara	tion du champ enum
	$rows = $db->fetchRow('SHOW COLUMNS FROM '.$table.' LIKE  \''.$field.'\' ');
	
	// récupration des valeur
	$matches = array();
	if (preg_match_all('/\'([^,]+)\'/', $rows['Type'], $matches)) {
		foreach($matches[1] as $match) {
			$match = str_replace('\'\'', '\'', $match);
			$type[$match] = $match;
		}
	}
	
	return $type;
}

/**
 * parse une chaine caractere au format de query string
 * et renvoie le tableau associatif de resultat
 * 
 * @param String $q
 */
function parse_query($q) {
	
	// initialisation du retour
	$return = array();
	
	// on recherche les differente variable
	foreach ((array) explode('&', $q) as $var) {
		
		// réinitilisation des variable
		unset($key, $value);
		
		// recuperation de la cle et de la valeur
		list($key, $value) = explode('=', $var);
		
		// attirbution de la valeur
		$return[$key] = $value;
	}
	
	return $return;
}

/**
 * recuperation de la vrai adresse ip
 * 
 * 
 */
function real_ip() {

	//check ip from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$ip=$_SERVER['HTTP_CLIENT_IP'];}
    //to check ip is pass from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}
 	elseif (is_cron())  {$ip = '127.0.0.1';}
    else {$ip=$_SERVER['REMOTE_ADDR'];}
    
    return $ip;
}

/**
 * revneoi la veleure associé a la clé dans un tableau
 * 
 * @param mixed $key
 * @param array $search
 * @return Mixed
 */
function array_find( $key , array $search) {
	
	// intiialisation du resultat
	$return = false;
	
	// si l'index existe recuperationd e la valeur associé
	if (array_key_exists($key, $search))  { $return = $search[$key];}
	
	return $return;
}

/**
 * remplie un tableau avec des pairs cle /valeur identique
 * 
 * @param unknown_type $array
 */
function array_fill_all($array) {
	$return = array();
	foreach ((array)$array as $val) {$return[$val] = $val;}
	return $return;
}

/**
 * renvoie les association du tableau composé des clés
 * 
 * @param unknown_type $keys
 * @param unknown_type $haystack
 */
function array_get_assoc($keys, $haystack) {
	
	$haystack = (array) $haystack;
	$return  = array();
	foreach ((array) $keys as $key) {if (array_key_exists($key, $haystack)) {$return[$key] = $haystack[$key];}}
	
	return $return;
}

/**
 * transformation d'un tableau en objet
 * 
 * @param array $array
 */
function array_to_object(array $array) {

	$object = new stdClass();
	
    foreach ($array as $name=>$value) {
       $name = trim($name);
       $object->$name = is_array($value) ? array_to_object($value) : $value;
    }
    
    return $object;
}


/**
 * transformation d'un objet en tableau
 * 
 * @param array $array
 */
function object_to_array($obj) {

	$result = array();
	
    foreach ($obj as $name=>$value) {
//       $name = strtolower(trim($name));
       $result[$name] = is_object($value) ? object_to_array($value) : $value;
    }
    
    return $result;
}

/**
 * remonte tout les element d'un tableau sur un niveau
 * 
 * @param unknown_type $array
 * @param unknown_type $ignore_numeric
 * @throws Exception
 */
function array_flatten(array $array) {
	
	$return = array();
	
	foreach ($array as $k => $v) {
		if (is_array($v)) {foreach (array_flatten($v) as $key => $val) { $return[$key] = is_array($val) ? array_flatten($val) : $val; }} 
		else {$return[$k] = is_array($v) ? array_flatten($v) : $v;}
	}
	return $return;
}

/**
 * format un  numero de telephone
 * 
 * @param unknown_type $phone
 */
function phone_number_format($phone) {
	
	$phone = str_replace(' ', '', $phone);
	$phone = implode(" ", str_split($phone,2));
	return $phone;
}

/**
 * test si l'application est lancé depouis la ligne de commande
 * 
 */
function is_cron() {
	return in_array(php_sapi_name(), array('cli', 'cgi-fcgi')) ? true : false;
}


/**
 * formatage du titre (repertoire)
 * 
 * @param unknown_type $title
 */
function alpha_num($s) {return preg_replace('/[^\w^\/^\.]/', '', replace_accent(str_replace(' ', '_', $s)));}

/**
 * renvoie la taille dans l'unité la plus lisible (mo, go etc...)
 * 
 * @param unknown_type $size
 * @param unknown_type $round
 * @throws Exception
 */
function format_size($size, $round = 1) {
	
	$unit = array('Ko', 'Mo', 'Go', 'To');
	
	// initialisation du resultat
	$result = $size . 'o';
	
	// calcul
	foreach ($unit as $u) {if (($size /= 1024) > 1) {$result = round($size, $round) . $u;}}
	
	return $result;
}


/**
 * envoie un message d'erreur au plugin Application_Plugin_Message
 * 
 * @param unknown_type $content
 */
function message_error($content) {Zend_Controller_Front::getInstance()->getPlugin('Application_Plugin_Message')->error($content);}

/**
 * envoie un message au plugin Application_Plugin_Message
 * 
 * @param unknown_type $content
 */
function message_highlight($content) {Zend_Controller_Front::getInstance()->getPlugin('Application_Plugin_Message')->highlight($content);}


/**
 * recuperation des module, controller et vue disponible
 */
function getaction($m = '', $c = '') {
	
	// recuperation du controller frontal
	$front = Zend_Controller_Front::getInstance();
	    
	//intitialisation du resultat
	$result = array();
	
	// parcours de tous les repertoire de controller
	foreach ($front->getControllerDirectory() as $module => $path) {
        	
		// si le repertoir n'existe pas, on passe
   		if (!is_dir($path) || (!empty($m) && $m != $module)) {continue;}

		foreach (scandir($path) as $file) {
				
			//on verifie qu'il s'agit bien d'un controller
			if (strstr($file, "Controller.php") !== false) {
				
				// inclusion du fichier pour reflection
				include_once $path . DIRECTORY_SEPARATOR . $file;
				
				//recuperation des classe declaré
				foreach (get_declared_classes() as $class) {
					
					// on verifie qu'ils 'agirt un d'un controller 
					if (is_subclass_of($class, 'Zend_Controller_Action')) {
						
						$controller = str_replace($module . '_', '', strtolower(substr($class, 0, strpos($class, "Controller"))));
						$actions = array();
						
						if (!empty($c) && $c != $controller) {continue;}
						
						// Récupérationd es actions
						foreach (get_class_methods($class) as $action) {
							if (strstr($action, "Action") !== false) {$actions[] = strtolower(substr($action, 0, strpos($action, "Action")));;}
						}
					}
				}
				
				// ajout des actions
				if (!empty($actions)) {$result[$module][$controller] = $actions;}
			}
		}
	}
		
 	return $result;
}

function getacl() {
	return Application_Model_Acl_Default::getInstance();
}

