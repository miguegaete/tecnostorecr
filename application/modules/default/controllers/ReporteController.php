<?php

class ReporteController extends Zend_Controller_Action
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

		$this->view->current = 'mod_reportes';
                
        #Archivos CSS
        $this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
		$this->view->headLink()->appendStylesheet('/css/load.css');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/reporte/index.js');
        $this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');        
        $this->view->headScript()->appendFile('https://www.gstatic.com/charts/loader.js');        
       
		$this->view->title = "Reportes";

		#navigation
		$this->view->nav = $this->view->navegacion(array($this->view->translate->_("Reportes")=>"/"));

        unset($_POST);
    }
	public function productosMasVendidosAction(){	
		
		$productos = new Ventas_Model_Venta();
		$rs = $productos->productosmasvendidos(); 
		
		$this->_helper->json($rs);
		exit;
	}
	public function categoriasMasVendidasAction(){	
		
		$productos = new Ventas_Model_Venta();
		$rs = $productos->categoriasmasvendidas(); 
		
		$this->_helper->json($rs);
		exit;
	}	
	
	public function cantidadProductosPorCategoriaAction(){	
		
		$productos = new Ventas_Model_Venta();
		$rs = $productos->cantidadproductosporcategoria(); 
		
		$this->_helper->json($rs);
		exit;
	}		
	
	
	public function ventasPorUsuarioAction(){
			
		$mes = $this->_getParam('mes');
		$anio = $this->_getParam('anio');
		
		$productos = new Ventas_Model_Venta();
		$rs = $productos->ventasporusuario($mes,$anio); 
		
		$this->_helper->json($rs);
		exit;
	}
	public function ventasPorMesAction(){	
		$anio = $this->_getParam("anio");
		$productos = new Ventas_Model_Venta();
		$rs = $productos->ventaspormesnueva($anio); 
		
		$this->_helper->json($rs);
		exit;
	}	
}