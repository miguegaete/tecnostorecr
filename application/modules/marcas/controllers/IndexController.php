<?php

class Marcas_IndexController extends Zend_Controller_Action
{

	private $nm = 'Marcas';
	private $nm_ruta = 'marcas';

    public function init()
    {
		$nm_ruta = 'marcas';
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
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/index.js');
		$this->view->title = $this->nm;
		$this->view->ruta = $this->nm_ruta;
		
	

    }
	
	public function nuevoAction()
	{
		$this->view->current2 = 'mod_'.$this->nm_ruta;
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/nuevo.js');
		$this->view->title = 'Añadir '.$this->nm;
		$this->view->ruta = $this->nm_ruta;

	}	
	
	
	public function editarAction()
	{
		$this->view->current2 = 'mod_'.$this->nm_ruta;
		$id = $this->_getParam('id');
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/'.$this->nm_ruta.'/editar.js');

		
		$marcas = new Marcas_Model_Marca();
			
		
		$this->view->marca = $marcas->obtener($id);
		
		$this->view->title = $this->nm; 
		$this->view->ruta = $this->nm_ruta;
	}	
	
	public function guardarAction(){
		
		$modelos = new Marcas_Model_Marca();

		$datos = array(
						'MAR_NOMBRE' => $this->_getParam("nombre"),
						'MAR_ESTADO' => $this->_getParam("estado")
					   );
		
		$tipo = $this->_getParam("tipo");
		$id = $this->_getParam("id");
		
		if($tipo!=1){
			$rs = $modelos->guardar($datos);
		}else{
			$where = "MAR_ID = '$id'";
			$rs = $modelos->actualizar($datos,$where);
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
		
		$modelos = new Marcas_Model_Marca();
		
		$rs = $modelos->listar();
		
		$html = "";
		
        if (count($rs)) {
              foreach ($rs as $aux){    
                $html.='<tr>';
				$html.='<td>'.$aux->id.'</td>';
                $html.='<td>'.$aux->nombre.'</td>';
				
				$estado_s = ($aux->estado == 1)? "<label class='label label-success estado' id='$aux->id'>Activo</label>":"<label class='label label-danger estado' id='$aux->id'>Inactivo</label>";
                //$html.='<td>'.$aux->estado.'</td>';
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
                $html.='<td colspan="6" align="center">No existen '.$this->nm.'</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;

	}		

	public function eliminarAction(){
		
		$id = $this->_getParam('id');
		$marcas = new Marcas_Model_Marca();
		$where = "MAR_ID = '".$id."'";
		$rs = $marcas->eliminar($where);
		
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