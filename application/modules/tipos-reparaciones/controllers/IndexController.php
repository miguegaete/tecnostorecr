<?php

class TiposReparaciones_IndexController extends Zend_Controller_Action
{

	protected $nm = "Tipos Reparaciones";
	protected $nm_ruta = "tipos-reparaciones";
	
    public function init()
    {
		$nm_ruta = 'tipos_reparaciones';
		
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
		$this->view->current2 = 'mod_'.$this->nm_ruta;
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/nuevo.js');
		
		$marcas = new TiposReparaciones_Model_TipoReparacion();
		
		
		$this->view->title = $this->nm; 
		$this->view->ruta = $this->nm_ruta; 
		
		$this->view->marca = $marcas->listar();

	}	
	
	
	public function editarAction()
	{
		$this->view->current2 = 'mod_'.$this->nm_ruta;
		$id = $this->_getParam('id');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/editar.js');

		
		$tipos = new TiposReparaciones_Model_TipoReparacion();
			
		
		$this->view->tipo = $tipos->obtener($id);
		
		$this->view->title = $this->nm; 
		$this->view->ruta = $this->nm_ruta; 
	}	
	
	public function guardarAction(){
		
		$tipos = new TiposReparaciones_Model_TipoReparacion();

		$datos = array(
						'TIP_NOMBRE' => $this->_getParam("nombre"),
						'TIP_ESTADO' => $this->_getParam("estado")
					   );
		
		$tipo = $this->_getParam("tipo");
		$id = $this->_getParam("id");
		
		if($tipo!=1){
			$rs = $tipos->guardar($datos);
		}else{
			$where = "TIP_ID = '$id'";
			$rs = $tipos->actualizar($datos,$where);
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
		
		$tipos = new TiposReparaciones_Model_TipoReparacion();
		
		$rs = $tipos->listar();
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
                $html.='<tr>';
				$html.='<td>'.$aux->id.'</td>';
                $html.='<td>'.$aux->nombre.'</td>';
				
				$estado_s = ($aux->estado == 1)? "<label class='label label-success estado' id='$aux->id'>Activo</label>":"<label class='label label-danger estado' id='$aux->id'>Inactivo</label>";
				$html.= "<td id='estado_".$aux->id."' class='estado-content' >$estado_s</td>";
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
                $html.='</tr>';
              } 
          }else{ 
                $html.='<tr>';
                $html.='<td colspan="6" align="center">No existen Tipos de Reparaciones</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

	}		

	public function eliminarAction(){
		
		$id = $this->_getParam('id');
		$tipos = new TiposReparaciones_Model_TipoReparacion();
		$where = "TIP_ID = '".$id."'";
		$rs = $tipos->eliminar($where);
		
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


}