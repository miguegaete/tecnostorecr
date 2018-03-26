<?php

class Productos_DescuentoController extends Zend_Controller_Action
{

    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}		
		 $this->view->current = 'mod_tienda';
         
        $session_usu = new Zend_Session_Namespace('login');
         
    }

    public function indexAction()
    {
		$this->view->current2= 'mod_descuentos';
		#Javascript
		$this->view->headScript()->appendFile('/js/sistema/descuento/index.js');
		
		$descuento = new Productos_Model_Descuento();
        $this->view->title = "Descuentos";
        #navigation
        $this->view->nav = $this->view->navegacion(array($this->view->translate->_("Descuentos")=>"/"));		

    }
	
	public function guardarAction(){
		
		$descuento = new Productos_Model_Descuento();
		$datos = array(
						"DES_BARCODE" => $this->_getParam('barcode'),
						"DES_DESCRIPCION" => $this->_getParam('nombre'),
						"DES_FECHAINICIO" => date('Y-m-d',strtotime($this->_getParam('fecha_inicio'))),
						"DES_FECHAFIN" => date('Y-m-d',strtotime($this->_getParam('fecha_final'))),
						"DES_TIPO" => $this->_getParam('tipo'),
						"DES_VALOR" => $this->_getParam('valor'),
						"DES_ESTADO" => 1
		);
		
		
		$rs = $descuento->insertar($datos);
		$this->_helper->json($rs);
		
		exit;
		
		
		
	}
	public function listarAction(){

		$descuento = new Productos_Model_Descuento();
		
		$rs = $descuento->listar(false,false);
		$html = "";
		if (count($rs)) {
			foreach ($rs as $aux) { 
				$html.= "<tr>"; 
				$html.= '<td>'.$aux->barcode.'</td>'; 
				$html.= '<td>'.$aux->descripcion.'</td>'; 
				$html.= '<td>'.$aux->fecha_inicio.'</td>'; 
				$html.= '<td>'.$aux->fecha_fin.'</td>'; 
				$html.= '<td>'.$aux->tipo.' '.$aux->valor.'</td>'; 
				$html.= '<td id="estado_'.$aux->id.'">';
				$html.= ($aux->estado==1)?'<label class="label label-success cursor-pointer" id="'.$aux->id.'">Activo</label>':'<label class="label label-danger cursor-pointer" id="'.$aux->id.'">Inactivo</label>';
				$html.= '</td>'; 
				//$html.= '<td><a href="" title="Eliminar" class="eliminar" id="'.$aux->id.'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>'; 
				$html.= '</tr>'; 
			}
		}else{
				$html.='<tr><td colspan="7" align="center">No existen descuentos</td></tr>';
		}
		
		
		
		echo $html;
		exit;
	}
	
	public function listarSelectAction(){
		$descuento = new Productos_Model_Descuento();
		$rs = $descuento->listar(false,true,true);
		
		$this->_helper->json($rs);
		exit;
	}	
	public function cambiarEstadoAction(){
		
		$id = $this->_getParam("id");
		$descuento = new Productos_Model_Descuento();
		
		$where =  "DES_ID = $id";
		$rs = $descuento->obtener($where);
		
		if($rs->estado == 1){
			$dato = array("DES_ESTADO"=> 0 );
			$estado = 0;
		}else{
			$dato = array("DES_ESTADO"=> 1 );
			$estado = 1;
		}
		
		$where2 = "DES_ID = $id";
		$res = $descuento->actualizar($dato,$where2);
		
		//Valido y devuelvo resultado
		$json = new stdclass();
		if(is_numeric($res)){
			$json->rs = true;
			$json->msg = $rs;
			$json->estado = $estado;

		}else{
			$json->rs = false;
			$json->msg = $rs;
		}
		$this->_helper->json($json);		
		exit;
		
		
	}
	
	public function generarBarcodeAction(){
		$descuento = new Productos_Model_Descuento();
		$lastid = $descuento->ultimoid();
		$dato = array("id"=> str_pad($lastid->id, 9, "0", STR_PAD_LEFT));
		$this->_helper->json($dato);
		exit;
		
		
	}
	
   public function eliminarAction(){
	   
            $session_cat = new Zend_Session_Namespace('categorias');
            $id = $this->_getParam("id");
            $categoria = new Productos_Model_Categoria();
            $where = "CAT_ID = $id";
            $accion_db  = $categoria->eliminar($where); 
            if($accion_db == 'si'){
                $session_cat->eliminado = $accion_db;            
            }else{
                $session_cat->eliminado = 'no';
                $session_cat->mensaje = $accion_db;
            }
            $this->_redirect("/productos/categoria/");
            exit;
    }
    public function nuevoAction(){
		
		$this->view->current2= 'mod_descuentos';
		$this->view->headScript()->appendFile('/js/sistema/descuento/nuevo.js');
		$this->view->title = "Añadir Descuento";
		
		
		
    }    
	
}