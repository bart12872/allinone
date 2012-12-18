<?php
/**
 * 
 * @author Sylvain Cahot
 * @since 09/03/2010
 */
require_once 'Zend/Config/Ini.php';
 require_once 'Sea/Revision_Exception.php';
                

class Sea_Revision extends Zend_Config_Ini
{
	/**
	 * Path du fichier ini contenant la conf à charger
	 * @var string
	 */
	protected $_iniFilePath;
	/**
	 * Environnement de l'application, passé en paramètre ou généré par 
	 * comparaison hostname / liste allowedEnvironment
	 * @var string
	 */
	protected $_environment;
	
	/**
	 * liste des environnements à comparer avec le hostname si pas 
	 * d'environement passé au constructeur	 *
	 * @var array
	 */
	protected $_allowedEnvironment = array('-dev' => 'developpement',
										'-preprod' => 'pre-production',
										'-simu' => 'simulation');										
	/**
	 * getter du path du fichier ini
	 * @return string iniFilePath 
	 */		
	public function getIniFilePath() {
		return $this->_iniFilePath;
	}
	
	/**
	 * setter du path du fichier ini
	 * @param string iniFilePath 
	 */
	public function setIniFilePath($path){
		$this->_iniFilePath = $path;
	}
			
	/**
	 * getter de l'environment 
	 * @return string iniFilePath 
	 */	
	public function getEnvironment() {
		return $this->_environment;
	}
		
	/**
	 * setter de l'environment
	 * @param string env 
	 */
	public function setEnvironment($env){
		$this->_environment = $env;
	}
	
	/**
	 * getter de la liste d'environnement permis
	 * @return array $alowedEnvironment
	 */		
	public function getAllowedEnvironment() {
		return $this->_allowedEnvironment;
	}
	
	/**
	 * setter de la liste d'environnement permis
	 * @param string iniFilePath 
	 */
	public function setAllowedEnvironment(array $newEnv){
		$this->_allowedEnvironment = $newEnv;
	}
	
		
	/**
	 * Constructeur, récupère les params de la conf ini à partir du chemin du 
	 * fichier ini et de l'environnement passés en param, si pas d'env passé,
	 * on parse l'hostname pour essayer de déterminer l'environnement dans 
	 * lequel on se trouve
	 *   
	 * @param string $iniFilePath 
	 * @param string $environment
	 */
		
	public function __construct($iniFilePath, $environment = null){		
		/**
		 * Gestion de l'environment
		 */
		// si environnement passé
		if($environment != null){
			$this->setEnvironment($environment);
		}else{
			//on parse l'hostname
			$hostname = $_SERVER['SERVER_NAME'];			
			$this->setEnvironment($this->getEnvFromHostname($hostname));
		}
		/**
		 * Gestion des parametres correspondant à l'environnement d fichier ini
		 */		
		$this->setIniFilePath($iniFilePath);
		parent::__construct($this->getIniFilePath(), $this->getEnvironment());		
	}
		
	
	/**
	 * Récupère l'environnement depuis l'url (en fonction de la liste 
	 * d'environnements dispos dans la classe
	 *
	 * @param string $hostname
	 * @return string
	 */
	private function getEnvFromHostname($hostname){
		foreach($this->_allowedEnvironment as $patternKey => $envVal) {
			// si un environment de la liste est trouvé dans le hostname on le retourne
			$pattern = '/'.$patternKey.'/';			
			if(preg_match($pattern,$hostname) > 0){
				return $envVal;
			}
		}
		// sinon on est en production
		return "production";
	}
		
	/**
	 * Affiche les informations de la section u inicorrespondant à 
	 * l'environnement
	 *
	 * @param array $options
	 */
	public function display($options = array('colorEnabled' =>false, 'displayProduction' =>false)){					
		if($options['displayProduction'] || $this->getEnvironment()!='production'){
			if($options['colorEnabled']){
				echo "<div class='revisionInfo' style='background-color:".$this->color."'>";
			}else{
				echo "<div>";
			}
			
			echo $this->getEnvironment();			
			if(!$this->revision){
				throw new Sea_Revision_Exception(sprintf('Parametre %s non trouve dans le fichier ini.','revision'));
			}else{
				echo " rev".$this->revision." ";	
			}
			if(!$this->update){
				throw new Sea_Revision_Exception(sprintf('Parametre %s non trouve dans le fichier ini.','update'));
			}else{
				echo $this->update;	
			}
			echo "</div>";
		}
		
	}
	
}