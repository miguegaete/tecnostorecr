<?php


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));
	
defined('APPLICATION_PATH_2')
    || define('APPLICATION_PATH_2', realpath(dirname(__FILE__) . '/var/php_sessions'));	

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/library'));

defined('JSON_PATH')
    || define('JSON_PATH', realpath(dirname(__FILE__) . '/json'));    

defined('ADMINISTRACION_IMG')
    || define('ADMINISTRACION_IMG', realpath(dirname(__FILE__) . '/imagenes/upload/'));

defined('ADMINISTRACION_IMG_SERVER')
    || define('ADMINISTRACION_IMG_SERVER', '/imagenes/upload/');
	
defined('RUTA_SERVER_LOGO')
    || define('RUTA_SERVER_LOGO', realpath(dirname(__FILE__) . '/imagenes/sitio/logo'));

defined('RUTA_HTTP_LOGO')
    || define('RUTA_HTTP_LOGO', '/imagenes/sitio/logo/');
	
	
defined('RUTA_ELIMINAR')
    || define('RUTA_ELIMINAR', realpath(dirname(__FILE__)));
	
	

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
	
	
// Define application environment
defined('ADMINISTRACION_URL')
    || define('ADMINISTRACION_URL', 'http://tecnostore.dev-mobile.cl');	

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();