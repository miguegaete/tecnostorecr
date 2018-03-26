<?php

class Productos_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}		
		 $this->view->current = 'mod_tienda';
         	
    }

    public function indexAction()
    {
		$this->view->current2= 'mod_productos';
        $session_usu = new Zend_Session_Namespace('login');
        $session_pro = new Zend_Session_Namespace('productos');
        $producto = new Productos_Model_Producto();
        $categoria = new Productos_Model_Categoria();

        #Archivos CSS
        $this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css'); 

        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/productos/index.js');
        $this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');



        #Eliminar Validación
        if(isset($session_pro->eliminado)){
            if($session_pro->eliminado == 'si'){

                echo alerta("Eliminado Correctamente","success");
                $session_pro->unsetAll();

            }elseif($session_pro->eliminado == 'no'){

                echo alerta($session_pro->mensaje,"error");
                $session_pro->unsetAll();
            }
        }   

        #Cargar datos si este se edita

         if(isset($session_pro->producto_editar)){

            $this->view->datos_editar = $session_pro->producto_editar;
            $session_pro->unsetAll();
			//dp($this->view->datos_editar);

         }       

        #Insertar Validación
        if ($this->getRequest()->isPost()) {

            if($_FILES['adjunto']['name'] !=""){
                $imagen = $_FILES['adjunto'];
                if (!empty($imagen)) {
                    $archivo_tipo = explode('/', $_FILES['adjunto']['type']);
                    $formato = $archivo_tipo[1];
                
                    $ruta_imagen = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
                    $ruta_imagen2 = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG_SERVER . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
                
                if ($formato == "jpg" || $formato == "jpeg" || $formato == 'png' || $formato == 'gif'){
                    if(move_uploaded_file($_FILES['adjunto']['tmp_name'], $ruta_imagen)){
                        $img_original = imagecreatefromjpeg($ruta_imagen);
                        list($ancho,$alto)=getimagesize($ruta_imagen);                      
                        
						$ancho_normal = 500;
                        $alto_normal = 350;
						
                        $ancho_galeria = 121;
                        $alto_galeria = 85;
                        
						//if(($ancho >= $ancho_normal) && ($alto >= $alto_normal)){
                            $calidad=95;
                            
							$img_normal = imagecreatetruecolor($ancho_normal,$alto_normal);
							$img_galeria = imagecreatetruecolor($ancho_galeria,$alto_galeria);
														
							
                            imagecopyresampled($img_normal,$img_original,0,0,0,0,$ancho_normal, $alto_normal,$ancho,$alto);
							imagecopyresampled($img_galeria,$img_original,0,0,0,0,$ancho_galeria, $alto_galeria,$ancho,$alto);					
							
							
                            $ruta_1_n =  'uta_normal_' . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name']));
							$ruta_1_g =  'uta_galeria_' . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name']));
							
                            $ruta_2_n = ADMINISTRACION_IMG_SERVER .'r'.$ruta_1_n;
							$ruta_2_g = ADMINISTRACION_IMG_SERVER .'r'.$ruta_1_g;
							
                            $ruta_imagen_normal =  ADMINISTRACION_IMG.'/r'.$ruta_1_n;
							$ruta_imagen_galeria =  ADMINISTRACION_IMG.'/r'.$ruta_1_g;
							
						
							
                            imagejpeg($img_normal,$ruta_imagen_normal,$calidad);
							imagejpeg($img_galeria,$ruta_imagen_galeria,$calidad);
							
                            unlink($ruta_imagen);       
                        //}else{
                            //echo alerta("la imagen es muy pequeña, debe ser mayor  a Ancho : 500px Alto : 350px","error");
                            //return false;
                        //}   
                        
                    }
                }else{
                    echo alerta("formato de imagen no soportado","error");
                    return false;
                }
            }
            }

            
            if($this->_getParam('id')!=""){
				
            $datos = array( 'CAT_ID' => $this->_getParam('categoria'),
                            'PRO_SKU' => $this->_getParam('sku'),
                            'PRO_NOMBRE' => $this->_getParam('nombre'),
                            'PRO_DESCRIPCION' => $this->_getParam('descripcion'),
                            'PRO_VALOR' => $this->_getParam('valor'),
                            'PRO_ESTADO_VALOR_OFERTA' => $this->_getParam('activar_vr'),
							'PRO_VALOR_OFERTA' => $this->_getParam('valor_rebaja'),
                            'PRO_ESTADO' => $this->_getParam('estado'));				
                
                if(!empty($ruta_2_n)){
                    $datos['PRO_IMAGEN'] = (!empty($ruta_2_n))?$ruta_2_n:'';
					$datos['PRO_ICONO'] = (!empty($ruta_2_g))?$ruta_2_g:'';
                }
			
                

                $where = "PRO_ID = ".$this->_getParam('id');
                $action_db =  $producto->actualizar($datos,$where);
                if(is_numeric($action_db)){
                   echo alerta("Se ha actualizado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 

            }else{
            $datos = array( 'CAT_ID' => $this->_getParam('categoria'),
                            'PRO_SKU' => $this->_getParam('sku'),
                            'PRO_NOMBRE' => $this->_getParam('nombre'),
                            'PRO_DESCRIPCION' => $this->_getParam('descripcion'),
                            'PRO_VALOR' => $this->_getParam('valor'),
                            'PRO_ESTADO_VALOR_OFERTA' => $this->_getParam('activar_vr'),
							'PRO_VALOR_OFERTA' => $this->_getParam('valor_rebaja'),
                            'PRO_ESTADO' => $this->_getParam('estado'),
                            'PRO_STOCK' => 0);
							
                $datos['PRO_IMAGEN'] = (!empty($ruta_2_n))?$ruta_2_n:'';
				$datos['PRO_ICONO'] = (!empty($ruta_2_g))?$ruta_2_g:'';
				
                $action_db =  $producto->insertar($datos);   
                if(is_numeric($action_db)){
                   echo alerta("Se ha insertado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 
            }
        }        

        $this->view->producto = $producto->listar(false,false);
        $this->view->categoria = $categoria->listar();

        $this->view->title = "Productos";
		
        #Breadcrum
        $this->view->nav = $this->view->navegacion(array( "Productos" => "/" ));

        
	
    }
   public function eliminarAction(){
            $session_pro = new Zend_Session_Namespace('productos');
            $id = $this->_getParam("id");
            
			$producto = new Productos_Model_Producto();
			$repuestos = new Reparaciones_Model_Reparacion;
			$where =  "PRO_ID = $id";
			
			
			$prod = $producto->obtener($where);
			
			$repuesto = $repuestos->obtener($prod->idReparacion);
			
			$datos = array("REP_VENDER" => 0 );
			$where2 = "REP_ID  = $repuesto->id";
			
			$repuestos->actualizar($datos,$where2);
			
			
			
            $where = "PRO_ID = $id";
            $accion_db  = $producto->eliminar($where); 
            
			if($accion_db == 'si'){
                $session_pro->eliminado = $accion_db;            
            }else{
                $session_pro->eliminado = 'no';
                $session_pro->mensaje = $accion_db;
            }
            $this->_redirect("/productos/");
            exit;
    }

    public function obtenerAction(){
			
		$this->view->current2= 'mod_productos';	
        #Archivos CSS
        $this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css'); 

        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/productos/index.js');
        $this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');
		
            $session_pro = new Zend_Session_Namespace('productos');
            $id = $this->_getParam("id");
			$this->view->title = "Editando:";
            $producto = new Productos_Model_Producto();
			$categoria = new Productos_Model_Categoria();
            
			
			if ($this->getRequest()->isPost()) {
				
				
				if($this->_getParam('tipo-edit')=='producto-informacion'){				
					$datos = array( 'CAT_ID' => $this->_getParam('categoria'),
									'PRO_SKU' => $this->_getParam('sku'),
									'PRO_NOMBRE' => $this->_getParam('nombre'),
									'PRO_DESCRIPCION' => $this->_getParam('descripcion'),
									'PRO_ESTADO' => $this->_getParam('estado'),
									'PRO_SIN_SKU' => $this->_getParam('sin_sku'),
									'PRO_MANEJOSTOCK' => $this->_getParam('manejostock'));
				}elseif($this->_getParam('tipo-edit')=='producto-precio'){
					
					
					$datos = array('PRO_VALOR' => $this->_getParam('valor'),
									'PRO_ESTADO_VALOR_OFERTA' => $this->_getParam('activar_vr'),
									'PRO_VALOR_OFERTA' => $this->_getParam('valor_rebaja'),				
									'PRO_COSTO' => $this->_getParam('costo'));				
				}elseif($this->_getParam('tipo-edit')=='producto-imagen'){

					if($_FILES['adjunto']['name'] !=""){
						$imagen = $_FILES['adjunto'];
						if (!empty($imagen)) {
							$archivo_tipo = explode('/', $_FILES['adjunto']['type']);
							$formato = $archivo_tipo[1];
						
							$ruta_imagen = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
							$ruta_imagen2 = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG_SERVER . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
						
						if ($formato == "jpg" || $formato == "jpeg" || $formato == 'png' || $formato == 'gif'){
							if(move_uploaded_file($_FILES['adjunto']['tmp_name'], $ruta_imagen)){
								$img_original = imagecreatefromjpeg($ruta_imagen);
								list($ancho,$alto)=getimagesize($ruta_imagen);                      
								
								$ancho_normal = 500;
								$alto_normal = 350;
								
								$ancho_galeria = 121;
								$alto_galeria = 85;
								
								//if(($ancho >= $ancho_normal) && ($alto >= $alto_normal)){
									$calidad=95;
									
									$img_normal = imagecreatetruecolor($ancho_normal,$alto_normal);
									$img_galeria = imagecreatetruecolor($ancho_galeria,$alto_galeria);
																
									
									imagecopyresampled($img_normal,$img_original,0,0,0,0,$ancho_normal, $alto_normal,$ancho,$alto);
									imagecopyresampled($img_galeria,$img_original,0,0,0,0,$ancho_galeria, $alto_galeria,$ancho,$alto);					
									
									
									$ruta_1_n =  'uta_normal_' . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name']));
									$ruta_1_g =  'uta_galeria_' . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name']));
									
									$ruta_2_n = ADMINISTRACION_IMG_SERVER .'r'.$ruta_1_n;
									$ruta_2_g = ADMINISTRACION_IMG_SERVER .'r'.$ruta_1_g;
									
									$ruta_imagen_normal =  ADMINISTRACION_IMG.'/r'.$ruta_1_n;
									$ruta_imagen_galeria =  ADMINISTRACION_IMG.'/r'.$ruta_1_g;
									
								
									
									imagejpeg($img_normal,$ruta_imagen_normal,$calidad);
									imagejpeg($img_galeria,$ruta_imagen_galeria,$calidad);
									
									unlink($ruta_imagen);       
								//}else{
									//echo alerta("la imagen es muy pequeña, debe ser mayor  a Ancho : 500px Alto : 350px","error");
									//return false;
								//}   
								
							}
						}else{
							echo alerta("formato de imagen no soportado","error");
							return false;
						}
					}
					}				
					
										
					if(!empty($ruta_2_n)){
						$datos = array('PRO_IMAGEN' => (!empty($ruta_2_n))?$ruta_2_n:'',
										'PRO_ICONO' => (!empty($ruta_2_g))?$ruta_2_g:'');	
					}
					
				}

								
                
                $where = "PRO_ID = ".$this->_getParam('id');
                $action_db =  $producto->actualizar($datos,$where);
                if(is_numeric($action_db)){
                   echo alerta("Se ha actualizado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }  				
				
				
			}
			
			
			
			
			
			$where = "PRO_ID = $id";
            $this->view->datos_editar = $producto->obtener($where);
			$this->view->categoria = $categoria->listar(false,1);        
			$this->view->title = "Editando: ".$this->view->datos_editar->nombre;
			
			
    }  
	
	public function listaVentaAction(){

            $id = $this->_getParam("id");
            $venta = new Productos_Model_PedidoProducto();
			
            $where = "pp.PED_ID = $id";
            $result =  $venta->listar_productos(false,$where);
            $this->_helper->json($result);
                        
    }    
	
	public function obtenerSkuAction(){
		
		$sku = $this->_getParam("sku");
		$producto = new Productos_Model_Producto();
		$where = "PRO_SKU = $sku AND PRO_ESTADO <> 0";
		$result = $producto->obtener($where);
		$this->_helper->json($result);
		exit;
		
	}
	public function nuevoAction(){
        $session_usu = new Zend_Session_Namespace('login');
		$this->view->current2= 'mod_productos';
		
        $producto = new Productos_Model_Producto();
        $categoria = new Productos_Model_Categoria();		
        #Archivos CSS
        $this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css'); 

        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/productos/index.js');
		$this->view->headScript()->appendFile('/js/sistema/productos/nuevo.js');
        $this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');

			if ($this->getRequest()->isPost()) {
				
				
				if($this->_getParam('tipo-edit')=='producto-informacion'){				
					$datos = array( 'CAT_ID' => $this->_getParam('categoria'),
									'PRO_SKU' => $this->_getParam('sku'),
									'PRO_NOMBRE' => $this->_getParam('nombre'),
									'PRO_DESCRIPCION' => $this->_getParam('descripcion'),
									'PRO_ESTADO' => $this->_getParam('estado'),
									'PRO_STOCK' => 0,
									'PRO_SIN_SKU' => $this->_getParam('sin_sku'),
									'PRO_MANEJOSTOCK' => $this->_getParam('manejostock'));
				}
					

								
                
                $action_db =  $producto->insertar($datos);   
                if(is_numeric($action_db)){
                   echo alerta("Se ha insertado correctamente","success");
				   $this->_redirect('/productos/obtener/'.$action_db.'/');
                }else{
                    echo alerta($action_db,"error");
                }  					
			}		
			$this->view->categoria = $categoria->listar(false,1);
			//dp($this->view->categoria);
			$this->view->title = "Añadir Producto";
			
		
		
	}

	public function listadoProductosJsonAction(){
		
		$producto = new Productos_Model_Producto();
		
		$listado = $producto->listar(false,true);
		$this->_helper->json($listado);
		
		
		
	}

	public function obtenerProductoAction(){
		
		$productos = new Productos_Model_Producto();
		
		$where = "PRO_ID = ".$this->_getParam('id_producto')." AND PRO_ESTADO <> 0";
		
		$producto = $productos->obtener($where); 
		
		$this->_helper->json($producto);
		exit;
		
	}
	
	public function generateCodeAction(){
		
		$productos = new Productos_Model_Producto();
		$codigo = $productos->obtenerCodigoProducto();
		//print_r($codigo);
		$this->_helper->json($codigo);
		
		
		exit;
	}
}