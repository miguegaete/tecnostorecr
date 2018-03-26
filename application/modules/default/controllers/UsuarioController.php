<?php

class UsuarioController extends Zend_Controller_Action
{
	private $_menu;
	
    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}	
    }

    public function indexAction(){
		$this->view->current = 'mod_usuarios';
	    $session_usu = new Zend_Session_Namespace('login'); 
		$this->view->session_usu = $session_usu;
        $session_usu = new Zend_Session_Namespace('usuarios');
        $usuario = new Default_Model_Usuario();

        $this->view->pass = $usuario->RandomString(10);
        
        //Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/usuarios/index.js');  
		$this->view->headScript()->appendFile('/js/sistema/default/usuario/pass.js');

        //Eliminar registro
        if(isset($session_usu->eliminado)){
            if($session_usu->eliminado == 'si'){
                echo alerta("Eliminado Correctamente","success");
                $session_usu->unsetAll();
            }elseif($session_usu->eliminado == 'no'){
                echo alerta($session_usu->mensaje,"error");
                $session_usu->unsetAll();
            }
        }   
     
        $this->view->usuarios = $usuario->listar();
		$this->view->title = "Usuarios";
		
    }
	
	
	public function nuevoAction(){
		$this->view->current = 'mod_usuarios';
		$usuario = new Default_Model_Usuario();
		
		$session_usu = new Zend_Session_Namespace('usuarios'); 
		
		$this->view->headScript()->appendFile('/js/sistema/default/usuario/obtener.js');
        #Insertar Validación
        if ($this->getRequest()->isPost()){        
			
            $datos = array( 'USU_LOGIN' => $this->_getParam('usuario'),
                            'USU_NOMBRE' => $this->_getParam('nombre'),
                            'USU_TIPO' => $this->_getParam('tipo'),
                            'USU_EMAIL' => $this->_getParam('email'),
							'USU_ESTADO' => $this->_getParam('estado')
                            );
            
               
			$action_db =  $usuario->insertar($datos);
			
			if(is_numeric($action_db)){
			   //echo alerta("Se ha insertado correctamente","success");
				$session_usu->nuevo = 1;
				$this->_redirect('/usuarios/obtener/'.$action_db.'/');
				
				
			}else{
				echo alerta($action_db,"error");
			}                 
            
        } 
		
		$this->view->title = "A&ntilde;adir Usuario";
		
		
	}
	

    public function eliminarAction(){
		
            $session_usu = new Zend_Session_Namespace('usuarios');
            $id = $this->_getParam("id");
            $usuario = new Default_Model_Usuario();
            $where = "USU_ID = $id";
            $accion_db  = $usuario->eliminar($where); 
            if($accion_db == 'si'){
                $session_usu->eliminado = $accion_db;            
            }else{
                $session_usu->eliminado = 'no';
                $session_usu->mensaje = $accion_db;
            }
            $this->_redirect("/usuarios/");
            exit;
    }

    public function obtenerAction(){
		$this->view->current = 'mod_usuarios';
		$session_usu_action = new Zend_Session_Namespace('usuarios');
		
	    $session_usu = new Zend_Session_Namespace('login'); 
		$this->view->session_usu = $session_usu;
		
		$id = $this->_getParam("id");
		
		$this->view->headScript()->appendFile('/js/sistema/default/usuario/obtener.js');

		$usuario = new Default_Model_Usuario();
		$where = "USU_ID = $id";

        //Insertar Validación
        if ($this->getRequest()->isPost()) {   
		
            $datos = array( 'USU_LOGIN' => $this->_getParam('usuario'),
                            'USU_NOMBRE' => $this->_getParam('nombre'),
                            'USU_TIPO' => $this->_getParam('tipo'),
                            'USU_EMAIL' => $this->_getParam('email'),
							'USU_ESTADO' => $this->_getParam('estado')
                            );
            
                

			$where = "USU_ID = ".$this->_getParam('id');
			$action_db =  $usuario->actualizar($datos,$where);
			
			if(is_numeric($action_db)){
			   echo alerta("Se ha actualizado correctamente","success");
			}else{
				echo alerta($action_db,"error");
			}

        }  
		
		if(isset($session_usu_action->nuevo)){
		   echo alerta("Usuario insertado correctamente","success");
		   $session_usu_action->unsetAll();
		}		

		$this->view->datos_editar = $usuario->obtener($where);
		
		$this->view->title = "Modificar Usuario: ".$this->view->datos_editar->usuario;
		          
    } 

    public function salirAction(){

    	$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
                
        Zend_Session::namespaceUnset('login');
        $this->_redirect('/');
    } 

    public function passAction(){

    	$id = $this->_getParam("id_usuario_password");
		
		$usuario = new Default_Model_Usuario();

		$datos = array('USU_PASS' => $usuario->crypt_blowfish_bycarluys($this->_getParam('clave')));

		$where = "USU_ID = $id";
		$rs = $usuario->actualizar($datos,$where);
		
		
		//Valido y devuelvo resultado
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
    
    public function actualizarDatosAction(){

		$this->view->current2 = 'mod_actualizar';
		$this->view->headScript()->appendFile('/js/sistema/default/usuario/actualizar-datos.js');
		
		$session_usu = new Zend_Session_Namespace('login');
		
        $usuario = new Default_Model_Usuario();
        $where = "USU_ID = $session_usu->id";
        $this->view->datos_usuario = $usuario->obtener($where);    
        $this->view->title = "Actualizar Datos";
    }
	
	public function actualizarDatosAjaxAction(){
		
		$usuario = new Default_Model_Usuario();
		
		$session_usu = new Zend_Session_Namespace('login');
		$user = $session_usu->usuario;
		
		$datos = array( 'USU_NOMBRE' => $this->_getParam("nombre_user"),
						'USU_EMAIL' => $this->_getParam('email'));
						
		$where = "USU_ID = ".$session_usu->id;
		$rs = $usuario->actualizar($datos,$where);
   		
		//Valido y devuelvo resultado
		$json = new stdclass();
		if(is_numeric($rs)){
			$json->rs = true;
			$json->msg = 'Se ha actualizado correctamente';
		}else{
			$json->rs = false;
			$json->msg = $rs;
		}
		$this->_helper->json($json);			
		exit;
		
	}
	
	public function actualizarPassAjaxAction(){
		
		$usuario = new Default_Model_Usuario();
		
		$session_usu = new Zend_Session_Namespace('login');
		$user = $session_usu->usuario;
		$pass = $this->_getParam('password_actual');

		if($usuario->obtener_login($user,$pass)){

			$datos = array( 'USU_PASS' => $usuario->crypt_blowfish_bycarluys($this->_getParam('password2')));

			$where = "USU_ID = ".$session_usu->id;
			$rs = $usuario->actualizar($datos,$where);
		}else{

			$rs = "Su 'Contraseña Actual' no coincide";
		}     		
		

		//Valido y devuelvo resultado
		$json = new stdclass();
		if(is_numeric($rs)){
			$json->rs = true;
			$json->msg = 'Se ha Actualizado Correctamente';
			
		}else{
			$json->rs = false;
			$json->msg = $rs;//"Su 'Contraseña Actual' es incorrecta";
		}
		$this->_helper->json($json);			
		exit;
		
	}	
	
	public function cambiarEstadoAction(){
		
		$id = $this->_getParam('id');
		
		$usuarios = new Default_Model_Usuario();
		
		//Obtengo al Usuario
		$where =  "USU_ID = $id";
		$rs = $usuarios->obtener($where);		
		
		//Valido si existe id de usuario
		if($rs->id){
			//Valido estado si es activo lo cambio a inactivo y viceversa
			if($rs->estado == 1){
				$dato = array("USU_ESTADO"=> 2 );
				$estado = 2;
			}elseif($rs->estado == 2){
				
				$dato = array("USU_ESTADO"=> 1 );			
				$estado = 1;
			
			}
			
			//Actualizo estado
			$where2 = "USU_ID = $id";
			$res = $usuarios->actualizar($dato,$where2);		
		}else{
			$res = false;
		}
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
	
	public function accesosAction(){
		
		$permisos = new Default_Model_Permiso();
		$id = $this->_getParam('id');
		
		$this->view->headScript()->appendFile('/js/sistema/default/usuario/accesos.js');
		
		$this->view->permisos = $permisos->listar(false,$id);
		$this->view->id = $id;
		//dp($this->view->permisos);
		$this->view->title = "Accesos";
		
		
		
	}
	
	public function insertarAccesosAction(){
		$id = $this->_getParam('id');
		$accesos = $this->_getParam('acceso');
		
		$pusuario = new Default_Model_PermisoUsuario();
		$where = "USU_ID = $id";
		
		$pusuario->eliminar($where);
		
		if(count($accesos) > 0){
			for($i=0;$i<count($accesos);$i++) {
				
				$datos = array(
								'USU_ID' => $id,
								'PER_ID' => $accesos[$i]
								);
				$res = $pusuario->insertar($datos);
			}
		}else{
			$res = 1;
		}
		
		$json = new stdclass();
		
		if(is_numeric($res)){
			$json->rs = true;
			$json->msg = "Accesos actualizados";

		}else{
			$json->rs = false;
			$json->msg = $rs;
		}
		$this->_helper->json($json);			
		
		
		
		exit;
	}
	
	public function validarUsuarioAction(){
		
		try 
		{
			$usuario =  new Default_Model_Usuario();
			$user = $this->_getParam('usuario');
			$pass = $this->_getParam('pass');
			
			$rs  = $usuario->obtener_login($user,$pass);
			$error = false;
			$msg = "";
		}catch(Zend_Exception $ex){
			$rs = false;
			$error = true;
			$msg = $ex->getMessage();
		}
		
		$json = new stdclass();
		$json->rs = $rs;		
		$json->error = $error;		
		$json->msg = $msg;		
		$this->_helper->json($json);
		exit;
	}
}