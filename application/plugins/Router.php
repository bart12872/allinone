<?php
/**
 * Plugin de chargement du routeur
 * 
 * @author Julien Houvion
 * 
 */
require_once ('Zend/Controller/Plugin/Abstract.php');


/**
 * Defini les règles de chargement
 * 
 * @author Julien Houvion
 *
 */
class Application_Plugin_Router extends Zend_Controller_Plugin_Abstract {
    
	/**
	 * Charge les règles de routage avant que le router ne soit appellé
	 * 
	 * @see Zend/Controller/Plugin/Zend_Controller_Plugin_Abstract::routeStartup()
	 */
	public function routeStartup(Zend_Controller_Request_Abstract $request) {
		
		$router = Zend_Controller_Front::getInstance()->getRouter();// recuoperation du router
		$router->removeDefaultRoutes(); // on enleve les routes par default
		$dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();// on recupere le dispatcher
		$request = $this->getRequest();     
		
		// gestion des module en sous domaine
		$host = $request->getHttpHost() ? $request->getHttpHost() : 'http://www.allinone.local';
        $hostRoute = new Zend_Controller_Router_Route_Hostname ( preg_replace('/[^\.]*(\..*)/', ':module$1', $host), ['module' => 'www']); 
    	
    	// path route par default
        $pathRoute = new Zend_Controller_Router_Route ( ':controller/:action/:id/*', [	'controller' => $dispatcher->getDefaultControllerName(), 
        																				'action' => $dispatcher->getDefaultAction(), 
        																				'id' => null]); 
		$router->addRoute ( 'default', $hostRoute->chain ( $pathRoute ) );
    	
    	// on ajoute les route trouver dans les fichier de config
    	$router->addConfig(getregistry(false, 'route'));
	}
	
	/**
	 * 
	 * Vérification si scripts direct appelé (hors MVC).
	 * 
	 * (non-PHPdoc)
	 * @see Zend/Controller/Plugin/Zend_Controller_Plugin_Abstract::routeShutdown()
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		
		$front = Zend_Controller_Front::getInstance();// recuperation du controller frontale
		$router = $front->getRouter();// On récupère le routeur
		$dispatcher = $front->getDispatcher();// on recupere le dispatcher
		
		// gestion du module par default
		if ($request->getModuleName() == 'www') {$request->setModuleName($dispatcher->getDefaultModule());}
		
	    if (is_cron()) {$this->_route();}
	}
}