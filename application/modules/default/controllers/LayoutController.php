<?php

class LayoutController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        Zend_Session::start(); 
        
    }

    public function indexAction()
    {
        // action body
    }

    public function topAction()
    {

    }

    public function mainAction()
    {
		// $servicios = new Servicios_Model_Area();
		// $this->view->areas = $servicios->listar(false,1); // limite y codigo de Tipo de servicio

        $this->view->session_usu = new Zend_Session_Namespace('login');
		$configurar = new Configuraciones_Model_Configurar();
		$this->view->empresa = $configurar->obtener(2);
		
		$accs = $this->view->session_usu->accesos;
		
		for($i = 0; $i < count($accs);$i++)
		{
			$acceso[$accs[$i]->nombre_validar] =  ($accs[$i]->activo==1)?true:false;
		}		
		$this->view->acceso = $acceso;
		
		//dp($this->view->a['Vender']);
    }

    public function footerAction()
    {

    }
}

