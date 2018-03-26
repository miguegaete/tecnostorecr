<?php

class Portada_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
    }

    public function quienesSomosAction()
    {
        $pagina = new Editables_Model_Pagina('quienes-somos');
        //$this->view->texto = $pagina->texto;
    }

    public function actividadesAction()
    {
        #models
        $actividades = new Actividades_Model_Actividad();
        
        #views
	$this->view->listado_actividades = $actividades->listarIndex(3);
	$this->view->fecha = Date('Y-m-d');
    }

    public function noticiasAction()
    {
      
		#noticias
     	$noticias = new Noticias_Model_Noticia();
       	$this->view->listado_noticias = $noticias->listar(4);
        
        unset($noticias);
    }
	public function tiposCarroceriasAction(){
	
		$servicios = new Servicios_Model_Servicio();
		$this->view->tipos = $servicios->listar(8); // limite
		// dp($this->view->servicios,true);
		// dp($this->view->servicios,true);
	
	}
	public function ofertasTrabajoAction(){
		$ofertas = new Ofertas_Model_Oferta();
		$this->view->ofertas = $ofertas->listar(3);
		// dp($this->view->ofertas,true);
	}
}