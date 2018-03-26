<?php

class Clientes_IndexController extends Zend_Controller_Action
{
    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}		
		$this->view->current = 'mod_ventas';

    }

    public function indexAction()
    {
		$this->view->current2 = 'mod_clientes';
		
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/clientes/index.js');
		
		$clientes = new Clientes_Model_Cliente();
		$this->view->clientes = $clientes->listar();
		
		#Titulo
		$this->view->title = "Clientes";
    }
	
	public function guardarAction(){
		
		$clientes = new Clientes_Model_Cliente();

		$datos = array('CLI_NOMBRE' => $this->_getParam("nombre"),
						'CLI_RUT' => $this->_getParam("rut"),
						'CLI_TELEFONO' => $this->_getParam('telefono'),
						'CLI_EMAIL' => $this->_getParam('email'),
						'CLI_DIRECCION' => $this->_getParam('direccion'),
						'CLI_ESTADO' => 1
				);
		
		$tipo = $this->_getParam("tipo");
		$rut = $this->_getParam("rut");
		
		if($tipo!=1){
			$rs = $clientes->guardar($datos);
		}else{
			$where = "CLI_RUT = '$rut'";
			$rs = $clientes->actualizar($datos,$where);
		}
		
		
		$json = new stdclass();
		if(is_numeric($rs)){
			$json->rs = true;
			$json->msg = $rs;
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}
		
		$this->_helper->json($json);
		exit;

	}
	public function eliminarAction(){
		
		$rut = $this->_getParam('rut');
		$clientes = new Clientes_Model_Cliente();
		$where = "CLI_RUT = '".$rut."'";
		$rs = $clientes->eliminar($where);
		
		$json = new stdclass();
		if(is_numeric($rs)){
			$json->rs = true;
			$json->msg = $rs;
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}
		
		$this->_helper->json($json);
		exit;

	}	
	public function obtenerAction(){
		
		$rut = $this->_getParam('rut');
		$clientes = new Clientes_Model_Cliente();
		$rs = $clientes->obtener($rut);
		$this->_helper->json($rs);
		exit;

	}		
		
	public function listarAction(){
		
		$clientes = new Clientes_Model_Cliente();
		
		$rs = $clientes->listar();
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
                $html.='<tr>';
				$html.='<td>'.$aux->rut.'</td>';
                $html.='<td>'.$aux->nombre.'</td>';
                $html.='<td>'.$aux->telefono.'</td>';
                $html.='<td>'.$aux->email.'</td>';
                $html.='<td>'.$aux->direccion.'</td>';
                $html.='<td>';

				$html.='<div class="btn-group">
				  <button type="button" class="btn btn-default btn-sm btn-editar editar" value="/clientes/editar/'.$aux->rut.'/" ><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#555;"></span> Modificar</button>
				  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="'.$aux->rut.'" class="eliminar" ><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#555;"></span> Eliminar</a></li>
				  </ul>
				</div>';
                $html.='</td>';
                $html.='</tr>';
              } 
          }else{ 
                $html.='<tr>';
                $html.='<td colspan="6" align="center">No existen clientes</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

	}	

	public function nuevoAction()
	{
		$this->view->current2 = 'mod_clientes';
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/clientes/nuevo.js');
		$this->view->title = 'Añadir Cliente';

	}

	public function editarAction()
	{
		$this->view->current2 = 'mod_clientes';
		$rut = $this->_getParam('id');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/clientes/editar.js');

		
		$clientes = new Clientes_Model_Cliente();
			
		
		$this->view->cliente = $clientes->obtener($rut);
		
		$this->view->title = 'Editando: '.$rut; 
	}
		
		
		
	}