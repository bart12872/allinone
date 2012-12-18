<?php
/**
 * redirection vers les bonnes action pour les traitement specifique
 * @author jhouvion
 *
 */
class Application_Plugin_Tool  extends Zend_Controller_Plugin_Abstract {
	
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {

		if (!($request->getControllerName() == 'tool')) {return;}
		switch ($request->getActionName()) {
			
			case 'datatable' : $request->setModuleName('www');
			case 'minify' : $request->setModuleName('www');
			case 'explorer' : $request->setModuleName('www');
		} 
	}
}
?>