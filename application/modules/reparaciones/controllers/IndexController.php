<?php

class Reparaciones_IndexController extends Zend_Controller_Action
{

	private $nm = 'Reparaciones';
	private $nm_ruta = 'reparaciones';

    public function init()
    {
		$nm_ruta = 'reparaciones';
        Zend_Session::start();
        /* Initialize action controller here */
        $session_usu = new Zend_Session_Namespace('login');
		$accs = $session_usu->accesos;
		
		//Si no esta logeado entra
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}else{

			for($i = 0; $i < count($accs);$i++)
			{
				if($accs[$i]->nombre_validar == $nm_ruta)
					$access_module  = ($accs[$i]->activo==1)?true:false;
			}	
			
			if(!$access_module)	{
				$this->_redirect("/");
			}
		
		}		
		$this->view->current = 'mod_repa';
		
      
    }

    public function indexAction(){
		
		$this->view->current2 = 'mod_'.$this->nm_ruta;

		$session_usu = new Zend_Session_Namespace('login');
		
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/index.js');
		$this->view->title = $this->nm;
		$this->view->ruta = $this->nm_ruta;
		
		$this->view->tipo = $session_usu->tipo;
		
		
		
		
    }
	
	public function nuevoAction()
	{
		$session_usu = new Zend_Session_Namespace('login');
		
		if($session_usu->tipo != 1){
			$this->_redirect("/");
		}
		
		$this->view->current2 = 'mod_'.$this->nm_ruta;
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/nuevo.js');
		
		$modelos = new Modelos_Model_Modelo();
		$tipos = new TiposReparaciones_Model_TipoReparacion();
		$reparaciones = new Reparaciones_Model_Reparacion();
		
		$this->view->modelos = $modelos->listar();
		$this->view->tipos = $tipos->listar();
		$this->view->reparaciones = $reparaciones->listar(10,'DESC');
		
		
		
		
		$this->view->title = $this->nm;
		$this->view->ruta = $this->nm_ruta;

	}	
	
	
	public function editarAction()
	{
		$session_usu = new Zend_Session_Namespace('login');
		
		if($session_usu->tipo != 1){
			$this->_redirect("/");
		}
		
		$this->view->current2 = 'mod_'.$this->nm_ruta;
		$id = $this->_getParam('id');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/editar.js');

		$modelos = new Modelos_Model_Modelo();
		$tipos = new TiposReparaciones_Model_TipoReparacion();
		$reparaciones = new Reparaciones_Model_Reparacion();
		
		
		$this->view->modelos = $modelos->listar();
		$this->view->tipos = $tipos->listar();		
		
		
		
			
		
		$this->view->reparacion = $reparaciones->obtener($id);
		
		$this->view->title = $this->nm; 
		$this->view->ruta = $this->nm_ruta;
	}	
	
	public function guardarAction(){
		
		$reparaciones = new Reparaciones_Model_Reparacion();

		$datos = array(
						'MOD_ID' => $this->_getParam("id_modelo"),
						'REP_COSTO' => $this->_getParam("costo"),
						'REP_VALOR' => $this->_getParam("valor"),
						'TIP_ID' => $this->_getParam("id_tipo"),
						'REP_ESTADO' => $this->_getParam("estado"),
						'REP_FECHA' => date('Y-m-d H:i')
					   );
		
		$tipo = $this->_getParam("tipo");
		$id = $this->_getParam("id");
		
		if($tipo!=1){
			$rs = $reparaciones->guardar($datos);
		}else{
			$where = "REP_ID = '$id'";
			$rs = $reparaciones->actualizar($datos,$where);
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
	
	
	public function listarAction(){
		$session_usu = new Zend_Session_Namespace('login');
		
		$reparaciones = new Reparaciones_Model_Reparacion();
		
		$rs = $reparaciones->listar();
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
                $html.='<tr>';
				$html.='<td>'.$aux->id.'</td>';
                $html.='<td>'.$aux->modelo.'</td>';
                $html.='<td>'.$aux->tipo.'</td>';
				if($session_usu->tipo == 1){
                $html.='<td>'.$aux->costo.'</td>';
				}
                $html.='<td>'.$aux->valor.'</td>';
				$estado_s = ($aux->estado == 1)? "<label class='label label-success estado' id='$aux->id'>Activo</label>":"<label class='label label-danger estado' id='$aux->id'>Inactivo</label>";
                //$html.='<td>'.$aux->estado.'</td>';
				$html.= "<td id='estado_".$aux->id."' class='estado-content' >$estado_s</td>";
                if($session_usu->tipo == 1){
				$html.= "<td>";
				$estado_vender = ($aux->vender == 1)? "checked":"";
				$html.= "<input type='checkbox' id='vender_".$aux->id."' id='vender_".$aux->id."'  ".$estado_vender." class='vender'  >";
				$html.= "</td>";	
				$html.='<td>';
				$html.='<div class="btn-group">
				  <button type="button" class="btn btn-default btn-sm btn-editar editar" value="/'.$this->nm_ruta.'/editar/'.$aux->id.'/" ><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#555;"></span> Modificar</button>
				  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="'.$aux->id.'" class="eliminar" ><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#555;"></span> Eliminar</a></li>
				  </ul>
				</div>';
                $html.='</td>';
				}
                $html.='</tr>';
              } 
          }else{ 
                $html.='<tr>';
                $html.='<td colspan="6" align="center">No existen '.$this->nm.'</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

	}		
	
	
	public function listarUltimasAction(){
		
		$reparaciones = new Reparaciones_Model_Reparacion();
		
		$rs = $reparaciones->listar(10,'DESC');
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
					$html.='<a href="#" class="list-group-item">';
					$html.='<h5 class="list-group-item-heading">[#'.$aux->id.'] '.$aux->modelo.'</h5>';
					$html.='<p class="list-group-item-text">'.$aux->fecha.' '.$aux->hora.' '.$aux->tipo.'</p>';
					$html.='</a>';                
              } 
			  
			

		  
          }else{ 
				
					$html.='<a href="#" class="list-group-item">';
					$html.='<h4 class="list-group-item-heading">No existen '.$this->nm.'</h4>';
					$html.='<p class="list-group-item-text">...</p>';
					$html.='</a>';
				
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

	}		

	public function eliminarAction(){

		$session_usu = new Zend_Session_Namespace('login');
		
		if($session_usu->tipo != 1){
			$this->_redirect("/");
		}	
		$id = $this->_getParam('id');
		$reparaciones = new Reparaciones_Model_Reparacion();
		$where = "REP_ID = '".$id."'";
		$rs = $reparaciones->eliminar($where);
		
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
	
	
	public function agendarReparacionesAction(){
			
			$this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
			$this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');
			$this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/agendar-reparaciones.js');
			
			$marcas = new Marcas_Model_Marca();
			$modelos = new Modelos_Model_Modelo();
			$tipos = new TiposReparaciones_Model_TipoReparacion();
			
			$this->view->marcas = $marcas->listar();
			$this->view->modelos = $modelos->listar();
			$this->view->tipos = $tipos->listar();
			
			
			

	}


	public function venderServicioAction(){
		
		
		$idReparacion = $this->_getParam("id");
		
		$reparaciones = new Reparaciones_Model_Reparacion();
		$productos = new Productos_Model_Producto();
		
		$reparacion = $reparaciones->obtener($idReparacion);
		
		
		$prod = $productos->obtenerCodigoProducto();
		
		
		
		$datos = array( 'CAT_ID' => 82,
						'PRO_SKU' => $prod->pro_sku,
						'PRO_NOMBRE' => $reparacion->nombre,
						'PRO_DESCRIPCION' => $reparacion->nombre ,
						'PRO_ESTADO' => 1,
						'PRO_STOCK' => 0,
						'PRO_SIN_SKU' => 1,
						'PRO_MANEJOSTOCK' => 0,
						'REP_ID' => (int)$idReparacion,
						'PRO_VALOR' => $reparacion->valor,
						'PRO_COSTO' => (int)$reparacion->costo
						);
									
									
		$rs = $productos->insertar($datos);

		$json = new stdclass();
		if(is_numeric($rs)){
			
			$datos2 = array ( 'REP_VENDER' => 1 );
			$where2 = "REP_ID = $idReparacion";
			
			$reparaciones->actualizar($datos2,$where2);
			
			$json->rs = true;
			$json->msg = $rs;				
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}  
		
		$this->_helper->json($json);
		exit;

	}		
	
	public function quitarVenderServicioAction(){
		
		
		$idReparacion = $this->_getParam("id");
		
		$reparaciones = new Reparaciones_Model_Reparacion();
		$productos = new Productos_Model_Producto();
		
		$reparacion = $reparaciones->obtener($idReparacion);
		

		$datos = array( 'PRO_ESTADO' => 0 );
		$where = "REP_ID = $idReparacion"; 
									
		$rs = $productos->actualizar($datos,$where);
		
		$json = new stdclass();
		if(is_numeric($rs)){
			
			$datos2 = array ( 'REP_VENDER' => 0 );
			$where2 = "REP_ID = $idReparacion";
			
			$reparaciones->actualizar($datos2,$where2);
			
			$json->rs = true;
			$json->msg = $rs;				
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}  
		
		$this->_helper->json($json);
		exit;

	}	

	public function activarVenderServicioAction(){
		
		
		$idReparacion = $this->_getParam("id");
		
		$reparaciones = new Reparaciones_Model_Reparacion();
		$productos = new Productos_Model_Producto();
		
		$reparacion = $reparaciones->obtener($idReparacion);
		

		$datos = array(	'PRO_NOMBRE' => $reparacion->nombre,
						'PRO_DESCRIPCION' => $reparacion->nombre ,
						'PRO_ESTADO' => 1,
						'PRO_VALOR' => $reparacion->valor,
						'PRO_COSTO' => (int)$reparacion->costo
						);
		
		
		
		$where = "REP_ID = $idReparacion"; 
									
		$rs = $productos->actualizar($datos,$where);
		
		$json = new stdclass();
		if(is_numeric($rs)){
			
			$datos2 = array ( 'REP_VENDER' => 1 );
			$where2 = "REP_ID = $idReparacion";
			
			$reparaciones->actualizar($datos2,$where2);
			
			$json->rs = true;
			$json->msg = $rs;				
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}  
		
		$this->_helper->json($json);
		exit;

	}	
	
	public function validaVendidoAction(){
		$idReparacion = $this->_getParam("id");
		$productos = new Productos_Model_Producto();
		$rs = $productos->validaExisteProductoServicio($idReparacion);
		$json = new stdclass();
		$json->rs = $rs;			
		$this->_helper->json($json);
		exit;

	}		
	
}