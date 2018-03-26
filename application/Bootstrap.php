<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        #autoloader
        $modelLoader = new Zend_Application_Module_Autoloader(array(
                                        'namespace'=>'',
                                        'basePath'=>APPLICATION_PATH.'/modules/default'
                                        ));
        
        #librería de Aeurus
        Zend_Loader::loadFile("Aeurus/funcionesGenerales.php");
        Zend_Loader::loadFile("Aeurus/pichoDateController.php");
		//Zend_Loader::loadFile("MPDF/MPDF.php");
		Zend_Loader::loadFile("MPDF/PDF_AutoPrint.php");

        #plugin multilanguage
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Plugin_LangSelector());
        
        return $modelLoader;
    }
    
    protected function _initDoctype()
    {
        $view = new Zend_View();
        $view->doctype('HTML5');
        #$view->doctype('XHTML1_STRICT');
    }
    
    protected function _initView()
    {
        $view = new Zend_View();
        
        #meta
        $view->headMeta()->appendName('author', 'Devmobile Ltda.');
                         //->appendHttpEquiv('', 'UTF-8');
                         //->appendHttpEquiv('Content-Type', 'charset=UTF-8')
                         //->appendHttpEquiv('Content-Language', 'es-ES');
                         /*->setCharset('UTF-8')*/
        #title
        $view->headTitle('Tecnostore - Punto de Venta - Devmobile')
			->setSeparator(', ');
        #stylesheet
        $view->headLink()->appendStylesheet('/css/bootstrap.css');
        $view->headLink()->appendStylesheet('/css/bootstrap-datepicker.css');
        $view->headLink()->appendStylesheet('/js/jquery/magnificpopup/magnific-popup.css');   
        $view->headLink()->appendStylesheet('/css/icons/flaticon.css');
		$view->headLink()->appendStylesheet('/css/load.css');
		
        #javascript
        #$view->headScript()->appendFile('/js/jquery/1.11.3/jquery-2.0.3.min.js');
        $view->headScript()->appendFile('/js/jquery/1.12.3/jquery-1.12.3.js');
        $view->headScript()->appendFile('/js/jquery/1.11.3/bootstrap.min.js');
        $view->headScript()->appendFile('/js/jquery/datepicker/moment.js');
        $view->headScript()->appendFile('/js/jquery/datepicker/bootstrap-datetimepicker.js');
        $view->headScript()->appendFile('/js/jquery/datepicker/es.js');
        $view->headScript()->appendFile('/js/jquery/magnificpopup/jquery.magnific-popup.min.js');
		
		#Jquery Validate
		$view->headScript()->appendFile('/js/jquery/jqueryvalidate/jquery.validate.js');
        $view->headScript()->appendFile('/js/jquery/jqueryvalidate/messages_es.js');	
        $view->headScript()->appendFile('/js/jquery/jqueryvalidate/additional-methods.js');	

		#Jquery RUT
		$view->headScript()->appendFile('/js/jquery/jqueryrut/jqueryrut.js'); 			
		
		
		#Tables Bootstrap
		$view->headScript()->appendFile('/js/jquery/tables-bootstrap/jquery.dataTables.min.js'); 
		$view->headScript()->appendFile('/js/jquery/tables-bootstrap/dataTables.bootstrap.min.js'); 
		$view->headLink()->appendStylesheet('/js/jquery/tables-bootstrap/dataTables.bootstrap.min.css'); 

		#Extension Tables (Button)
		$view->headLink()->appendStylesheet('/js/jquery/extension-button/buttons.dataTables.min.css'); 
		$view->headScript()->appendFile('/js/jquery/extension-button/dataTables.buttons.min.js'); 		
		$view->headScript()->appendFile('/js/jquery/extension-button/buttons.flash.min.js'); 		
		$view->headScript()->appendFile('/js/jquery/extension-button/buttons.html5.min.js'); 		
		$view->headScript()->appendFile('/js/jquery/extension-button/buttons.print.min.js'); 		
		$view->headScript()->appendFile('/js/jquery/extension-button/jszip.min.js'); 		
		$view->headScript()->appendFile('/js/jquery/extension-button/buttons.colVis.min.js'); 		
		#$view->headScript()->appendFile('/js/jquery/extension-button/pdfmake.min.js'); 		
		#$view->headScript()->appendFile('/js/jquery/extension-button/vfs_fonts.js'); 		

		#JS Global
		$view->headScript()->appendFile('/js/sistema/principal/index.js'); 			
		
		$view->headScript()->appendFile('https://www.gstatic.com/charts/loader.js'); 
        
		
    }
    
    protected function _initViewHelpers()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        
        $view = $layout->getView();
        $view->setHelperPath(APPLICATION_PATH.'/helpers', ''); 
    }
    
    protected function _initSiteRoutes()
    {
        $this->bootstrap("frontController");
	$front = $this->getResource("frontController");
        
	$router = new Zend_Controller_Router_Rewrite();
	$rutas = new Zend_Config_Ini(APPLICATION_PATH . "/configs/rutas_pyme.ini");
	$router->addConfig($rutas, 'routes');
        
	$front->getRouter()->addConfig($rutas, "routes");
    }
}