<?php

class Movimientos_ProveedoresController extends Zend_Controller_Action
{

    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}		
		$this->view->current = 'mod_proveedores';
    }
	
	public function indexAction(){
		
		$this->view->current2 = 'mod_proveedores';
		$this->view->headScript()->appendFile('/js/sistema/proveedores/index.js');
		
		
		$this->view->title = 'Proveedores';
		
	}
	public function editarAction()
	{
		$this->view->current2 = 'mod_proveedores';
		$this->view->headScript()->appendFile('/js/sistema/proveedores/editar.js');
		$this->view->title = 'Editar Proveedor';
		
		$id = $this->_getParam('id');
		$proveedores = new Movimientos_Model_Proveedor();
		$this->view->proveedor = $proveedores->obtener($id);
	}	
	
	public function nuevoAction(){
		$this->view->current2 = 'mod_proveedores';
		$this->view->headScript()->appendFile('/js/sistema/proveedores/nuevo.js');
		
		$this->view->title = 'Añadir Proveedor';
	}
	
	public function listarAction(){
		
		
		$proveedores = new Movimientos_Model_Proveedor();
		$rs = $proveedores->listar();
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
                $html.='<tr>';
				$html.='<td>'.$aux->rut.'</td>';
                $html.='<td>'.$aux->nombre.'</td>';
                $html.='<td>'.$aux->email.'</td>';
                $html.='<td>';

				$html.='<div class="btn-group">
				  <button type="button" class="btn btn-default btn-sm btn-editar editar" value="/proveedores/editar/'.$aux->id.'/" ><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#555;"></span> Modificar</button>
				  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="'.$aux->id.'" class="eliminar" ><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#555;"></span> Eliminar</a></li>
				  </ul>
				</div>';
                $html.='</td>';
                $html.='</tr>';
              } 
          }else{ 
                $html.='<tr>';
                $html.='<td colspan="6" align="center">No existen Proveedores</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;		
		
		
	}
	
	public function guardarAction(){
		
		$proveedores = new Movimientos_Model_Proveedor();

		$datos = array('PROV_NOMBRE' => $this->_getParam("txtNombre"),
						'PROV_RUT' => $this->_getParam("txtRUT"),
						'PROV_EMAIL' => $this->_getParam('txtEmail')
				);
		
		$tipo = $this->_getParam("txtTipo");
		$id = $this->_getParam("txtId");
		
		if($tipo!=1){
			$rs = $proveedores->guardar($datos);
		}else{
			$where = "PROV_ID = '$id'";
			$rs = $proveedores->actualizar($datos,$where);
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
		
		$id = $this->_getParam('id');
		$proveedores = new Movimientos_Model_Proveedor();
		$where = "PROV_ID = '".$id."'";
		$rs = $proveedores->eliminar($where);
		
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
		
		$id = $this->_getParam('txtId');
		$proveedores = new Movimientos_Model_Proveedor();
		$rs = $proveedores->obtener($id);
		$this->_helper->json($rs);
		exit;

	}	
	
	
}