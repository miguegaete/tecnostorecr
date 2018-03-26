<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        
        

    }

    public function indexAction()
    {
		$this->view->headLink()->appendStylesheet('/css/signin.css');
		
		$this->view->headScript()->appendFile('/js/sistema/default/index.js');
		
        $session_usu = new Zend_Session_Namespace('login');

        if(isset($session_usu->tipo)){
            $this->_redirect("/ventas/ventas-nuevo/");
        }
		
		$configurar = new Configuraciones_Model_Configurar();
		$this->view->empresa = $configurar->obtener(2);

        if ($this->getRequest()->isPost()) {

            $usuario =  new Default_Model_Usuario();
			$empresa = new Configuraciones_Model_Configurar();
			$cajas =  new Ventas_Model_Caja();
			$accesos = new Default_Model_Permiso();
			



            $user = $this->_getParam('usuario');
            $pass = $this->_getParam('password');            

            if($usuario->obtener_login($user,$pass)){
                
                $where = "USU_LOGIN = '$user'";
                $user = $usuario->obtener($where);
                $empr = $empresa->obtener(2);
				
				$caj = $cajas->obtener($user->id,1);
				
				
                $session_usu->id = $user->id;
                $session_usu->usuario = $user->usuario;
                $session_usu->nombre = $user->nombre;
                $session_usu->tipo = $user->tipo;
                $session_usu->email = $user->email;
				$session_usu->dispositivo = detect_disp();
				$session_usu->empresa_id = $empr[0]->id;
				
				
				
				$session_usu->accesos = $accesos->listar(false,$user->id);
				
				
				
				
				if($caj){
					$session_usu->caja = $caj->id; 
					$session_usu->caja_estado = $caj->estado; 
					$session_usu->caja_inicio = $caj->fecha_apertura; 
				}else{
					$session_usu->caja = 0;
					$session_usu->caja_estado = ''; 
					$session_usu->caja_inicio = ''; 
				}

                $this->_redirect("/ventas/ventas-nuevo/");
            }else{
                echo alerta("Usuario y/o Password inválidas","error");
            }






        }



    }
    
    public function mapaDelSitioAction()
    {
		#pagina padre

		
		
		
    }
    
    public function accesibilidadAction()
    {
      
    }
	
	public function subirImagenAction(){
		
		$this->view->headLink()->appendStylesheet('/css/estilo_imagen.css');
		$this->view->headScript()->appendFile('/js/sistema/default/subir-imagen.js');


		//comprobamos que sea una petición ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			//obtenemos el archivo a subir
			$file = $_FILES['archivo']['name'];
			//comprobamos si existe un directorio para subir el archivo
			//si no es así, lo creamos
			if(!is_dir("files/")) 
				mkdir("files/", 0777);
			//comprobamos si el archivo ha subido
			if ($file && move_uploaded_file($_FILES['archivo']['tmp_name'],"files/".$file))
			{
			   sleep(3);//retrasamos la petición 3 segundos
			   echo $file;//devolvemos el nombre del archivo para pintar la imagen
			}
		}
		
		
	}
}