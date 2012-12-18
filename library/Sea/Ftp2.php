<?php 
class Sea_Ftp2  {
	/**
	 * Pointeur de connexion
	 * 
	 * @var unknown_type
	 */
	protected $_connect;

 	/**
 	 * construicteur
 	 * 
 	 * @param unknown_type $host
 	 * @param unknown_type $port
 	 * @param unknown_type $timeout
 	 */
    public function __construct($host, $port = 21, $timeout = 90) {
    
		// on verifie qu'lon peux charger la classe
		if(!function_exists('ftp_connect')){throw new Sea_Exception('L\'extension ftp est manquante');}
        
        if (!($this->_connect = @ftp_connect($host, $port, $timeout))) {
    		throw new Sea_Exception('Impossible de se connecter au serveur : %s', $host );        
        }
    }
    
    /**
     * constructeur avec login / password
     * 
     * @param unknown_type $host
     * @param unknown_type $login
     * @param unknown_type $password
     * @param unknown_type $port
     * @param unknown_type $timeout
     * @throws Sea_Exception
     * @return Sea_Ftp2
     */
    static function connectWithCredential($host, $login, $password, $port = 21, $timeout = 90) {
        
        $c = __CLASS__;// recuperation nom de la classe
        $instance  = new $c($host, $port, $timeout);// creation de l'objet

        // authentification
        if (!$instance->login($login, $password)) {throw new Sea_Exception('Erreur d\'authentification pour l\'utilisateur : %s', $login);}
        
        
        // on retourne l'objet
        return $instance;
    }
    
    /**
     * upload d'un arobrescence (repertoire ou fichier)
     * 
     * @param unknown_type 
     */
    public function upload($queue) {
    	
        $pwd = $this->pwd();// on recupere le repertoire de depart
        
        // on force le format de l'upload
       	$queue  = (array) $queue;

       	// on parcours tout les fichier
       	foreach($queue as $dir) {
       	    
       	    // Cas du repertoire
	        if (is_dir($dir)) {
	
	            $directory = basename($dir);// recuperation du nom de repertoire
	            
	            // si le dossier n'existe pas on tente de le créer
				if (!array_search($directory, $this->nlist())) {
					if (!$this->mkdir($directory)) {throw new Sea_Exception('Impossible de créer le repertoire %s dans %s', $directory, $pwd);}
	            }
	            
	         	// on tente de naviguer vers le repertoire
	            if (!@$this->chdir($directory)) {throw new Sea_Exception('le repertoire existe pas : %s', $directory);}
	            
	            // on recupere les fichier contenu dans le repertoire
	         	$iterator = new DirectoryIterator($dir);
	         	$child = array();
			    foreach ($iterator as $fileinfo) {if ($fileinfo->isDot()){continue;}$child[] = $fileinfo->getPathname();}
			   
			    // si le repertoire est vide, on zappe
			    if (empty($child)) {continue;}
			    
			    // on lance l'upload
			    $this->upload($child);
	            
	        // cas du fichier   
	        } else if (is_file($dir)) {
	           // on tente l'upload du fichier
	           if (!$this->put(basename($dir), $dir, FTP_BINARY)) {throw new Sea_Exception('Erreur sur l\'upload du fichier : %s', basename($dir));}

	        // en cas d'erreur
	        } else {throw new Sea_Exception('la ressource %s n\'est ni un repertoire ni un fichier');}
	        
	       	// on remonte dans le dossier initial
	        if (!@$this->chdir($pwd)) {throw new Sea_Exception('Impossible de revenir dans le repertoire initiali: %s', $pwd);}
       	}
    }
    
    /**
     * destruction de l'objet
     * 
     */
    public function __destruct() {
        @$this->close();
    }

    /**
     * surcharge de la fonction de liste d'un repertoire
     * 
     * @param unknown_type $directory
     */
    public function nlist($directory = '.') {
        return ftp_nlist($this->_connect, $directory);
    }

    /**
     * utilisation de toute les fonction _ftp 
     * 
     * @param unknown_type $function
     * @param unknown_type $arguments
     * @return mixed
     */
    public function __call($function, $arguments) {
        
        // Prepend the ftp resource to the arguments array
        array_unshift($arguments, $this->_connect);
        
        if (!function_exists($function = 'ftp_' . $function)) {throw new Sea_Exception('la fonction %s n\'existe pas', $function);}
        
        // Call the PHP function
        return call_user_func_array($function, $arguments);
    }
}