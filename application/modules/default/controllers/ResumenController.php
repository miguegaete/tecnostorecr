<?php

class ResumenController extends Zend_Controller_Action
{
    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}      
    }

    public function indexAction(){

		$this->view->current = 'mod_resumen';
                
        #Archivos CSS
        $this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
		$this->view->headLink()->appendStylesheet('/css/load.css');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/default/resumen/index.js');
        $this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');        
        $this->view->headScript()->appendFile('https://www.gstatic.com/charts/loader.js');        
        //$this->view->headScript()->appendFile('/js/jquery/charts/loader.js');        
       
		$this->view->title = "Resumen";

		#navigation
		$this->view->nav = $this->view->navegacion(array($this->view->translate->_("Resumen")=>"/"));

        unset($_POST);
    }
	public function productosMasVendidosAction(){	
		
		$productos = new Ventas_Model_Venta();
		$rs = $productos->productosmasvendidos(); 
		
		$this->_helper->json($rs);
		exit;
	}
}