<?php
/**
 * Fichier principale de l'application
 * Booteur MVC
 * appeller pour toutes les pages php et html
 */
// Define path to application directory
defined('APPLICATION_PATH') 
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
defined('APPLICATION_ROOT') 
	|| define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/..'));
    
// variable caché
defined('DEFINITION_ENV')
    || define('DEFINITION_ENV', (getenv('DEFINITION_ENV') ? getenv('DEFINITION_ENV') : false));
    
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

// ajout de definition externe -- file_exist etc ne marchais pas avce include path
if (@fopen('definition.php', 'r', true)) {include_once 'definition.php';}

// configuration de la timezone
date_default_timezone_set("Europe/Paris");

/** Zend_Application */
require_once 'Zend/Application.php';

// chargeemnt des fonction général
require_once 'function.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();
