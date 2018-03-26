<?php

class Servicios_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->view->current = 0; 
        $session_usu = new Zend_Session_Namespace('login');
        if(!isset($session_usu->tipo)){
            $this->_redirect("/");
        }else{
            if($session_usu->tipo!=1){
                $this->_redirect("/ventas/");
            }
        } 

    }

    public function indexAction()
    {
        $session_sal = new Zend_Session_Namespace('servicios');
        $servicios = new Servicios_Model_Servicio();
        
		#CSS
		$this->view->headLink()->appendStylesheet('/css/load.css');
		
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/servicio/index.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryrut/jqueryrut.js');
        
		$this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/jquery.validate.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/messages_es.js');		
        $this->view->title = "Servicios";
        #navigation
        $this->view->nav = $this->view->navegacion(array($this->view->translate->_("Servicios")=>"/"));
    } 
	
	public function guardarAction(){
		
		$servicio = new Servicios_Model_Servicio();
		$cliente = new Clientes_Model_Cliente();
		$equipo = new Default_Model_Equipo();
		
		$json = new stdclass();
		
		$datos_cliente = array('CLI_NOMBRE' => $this->_getParam("nombre"),
						'CLI_RUT' => $this->_getParam("rut"),
						'CLI_TELEFONO' => $this->_getParam('fono'),
						'CLI_EMAIL' => $this->_getParam('correo'),
						'CLI_ESTADO' => 1
		);	
		
		$rscliente = $cliente->guardar($datos_cliente);
		
		if(is_numeric($rscliente)){
		
			$datos_equipo = array('EQU_IMEI' => $this->_getParam("imei"),
							'CEQ_ID' => $this->_getParam("categoria"),
							'EQU_MODELO' => $this->_getParam('modelo')
			);
			
			$rsequipo = $equipo->guardar($datos_equipo);
			
			if(is_numeric($rsequipo)){
				
				$datos_servicio = array('CLI_ID' => $rscliente,
								'EQU_ID' => $rsequipo,
								'SER_FECHA' => date('Y-m-d H:i'),
								'SER_DESCRIPCION' => $this->_getParam('descripcion')
				);	
				$rsservicio = $servicio->guardar($datos_servicio);
				
				if(is_numeric($rsservicio)){
					$json->rs = true;
					$json->msg = $rsservicio;
				}else{
					$json->rs = false;
					$json->msg = $rsservicio;					
				}
				
			}else{
				//Se elimina el Cliente
				//$where = 'CLI_ID = $rscliente';
				//$cliente->eliminar($where);
				
				$json->rs = false;
				$json->msg = $rsequipo;
				
			}
			
			
		}else{
			$json->rs = false;
			$json->msg = 'Este Cliente ya existe en nuestros Registros';
		}
		
		$this->_helper->json($json);
		exit;		
	}
	public function listarCategoriasEquipoAction(){
		
		$categorias_equipos = new Default_Model_CategoriaEquipo();
		
		$rs = $categorias_equipos->listar();
		$html = "";
		
		
        if (count($rs)){
              foreach ($rs as $aux){    
				$html.='<option value="'.$aux->id.'">'.$aux->nombre.'</option>';
              } 
        }else{ 
                $html.='<option value="">No existen categorías</option>';
        }
		
			$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

		}		
	

}