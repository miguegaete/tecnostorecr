<?php

class Productos_CategoriaController extends Zend_Controller_Action
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
		$this->view->current2= 'mod_categorias';
        $session_cat = new Zend_Session_Namespace('categorias');
        $producto = new Productos_Model_Producto();
        $categoria = new Productos_Model_Categoria();
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/categoria/index.js');

        #Eliminar Validación
        if(isset($session_cat->eliminado)){
            if($session_cat->eliminado == 'si'){

                echo alerta("Eliminado Correctamente","success");
                $session_cat->unsetAll();

            }elseif($session_cat->eliminado == 'no'){

                echo alerta($session_cat->mensaje,"error");
                $session_cat->unsetAll();
            }
        }   

        #Cargar datos si este se edita

         if(isset($session_cat->producto_editar)){

            $this->view->datos_editar = $session_cat->producto_editar;
            $session_cat->unsetAll();


         }       

        #Insertar Validación
        if ($this->getRequest()->isPost()) {
            $datos = array( 'CAT_NOMBRE' => $this->_getParam('nombre'),
                            'CAT_NOMBRE_CORTO' => $this->_getParam('nombre_corto'),
                            'CAT_ESTADO' => $this->_getParam('estado'));
            
            if($this->_getParam('id')!=""){
                $where = "CAT_ID = ".$this->_getParam('id');
                $action_db =  $categoria->actualizar($datos,$where);
                if(is_numeric($action_db)){
                   echo alerta("Se ha actualizado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 

            }else{
                $action_db =  $categoria->insertar($datos);   
                if(is_numeric($action_db)){
                   echo alerta("Se ha insertado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 
            }
        }        

        $this->view->categoria = $categoria->listar();
        
        $this->view->title = "Categorías";
        #navigation
        $this->view->nav = $this->view->navegacion(array($this->view->translate->_("Categorias")=>"/"));

        
	
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
	
    public function obtenerAction(){
		$this->view->current2= 'mod_categorias';
		$id = $this->_getParam("id");
		$categoria = new Productos_Model_Categoria();	

        #Archivos JS
        //$this->view->headScript()->appendFile('/js/sistema/productos/index.js');
		$this->view->headScript()->appendFile('/js/sistema/categoria/obtener.js');
		
		if ($this->getRequest()->isPost()) {		
			$datos = array( 'CAT_NOMBRE' => $this->_getParam('nombre'),
							'CAT_NOMBRE_CORTO' => $this->_getParam('nombre_corto'),
							'CAT_ESTADO' => $this->_getParam('estado'));
			$where = "CAT_ID = ".$this->_getParam('id');
			$action_db =  $categoria->actualizar($datos,$where);
			
			if(is_numeric($action_db)){
			   echo alerta("Se ha actualizado correctamente","success");
			}else{
				echo alerta($action_db,"error");
			}							
		
		}

		$where = "CAT_ID = $id";
		$this->view->datos_editar = $categoria->obtener($where);   
		$this->view->title = "Editando: ".$this->view->datos_editar->nombre;
    }
    public function nuevoAction(){
		$this->view->current2= 'mod_categorias';
        $categoria = new Productos_Model_Categoria();		

        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/categoria/nuevo.js');

        if ($this->getRequest()->isPost()) {
            $datos = array( 'CAT_NOMBRE' => $this->_getParam('nombre'),
                            'CAT_NOMBRE_CORTO' => $this->_getParam('nombre_corto'),
                            'CAT_ESTADO' => $this->_getParam('estado'));
            

                $action_db =  $categoria->insertar($datos);   
                if(is_numeric($action_db)){
                   echo alerta("Se ha insertado correctamente","success");
				   $this->_redirect('/productos/categoria/obtener/'.$action_db.'/');
                }else{
                    echo alerta($action_db,"error");
                }                
        }  		
			$this->view->categoria = $categoria->listar();
			$this->view->title = "Añadir Categoría";
			
		
		
	}	
}