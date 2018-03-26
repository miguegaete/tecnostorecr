<?php

class Salidas_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->view->current = 4; 
        $session_usu = new Zend_Session_Namespace('login');
        if(!isset($session_usu->tipo)){
            $this->_redirect("/");
        }else{
            if($session_usu->tipo!=1){
                $this->_redirect("/ventas/");
            }
        } 


        $this::crearJsonAction();

    }

    public function indexAction()
    {

        $session_sal = new Zend_Session_Namespace('salidas');
        $salida = new Salidas_Model_Salida();
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/salidas/index.js');

        #Eliminar Validación
        if(isset($session_sal->eliminado)){
            if($session_sal->eliminado == 'si'){

                echo alerta("Eliminado Correctamente","success");
                $session_sal->unsetAll();

            }elseif($session_sal->eliminado == 'no'){

                echo alerta($session_sal->mensaje,"error");
                $session_sal->unsetAll();
            }
        }   

        #Cargar datos si este se edita

         if(isset($session_sal->salida_editar)){

            $this->view->datos_editar = $session_sal->salida_editar;
            $session_sal->unsetAll();


         }       

        #Insertar Validación
        if ($this->getRequest()->isPost()) {

            if($this->_getParam('fechasfiltro')!=1){

                    $datos = array( 'SAL_FECHA' => date("Y-m-d H:i:s"),
                                    'SAL_DESCRIPCION' => $this->_getParam('descripcion'),
                                    'SAL_VALOR' => $this->_getParam('valor'));

                                


                
                
                if($this->_getParam('id')!=""){
                    
                    $where = "SAL_ID = ".$this->_getParam('id');
                    $action_db =  $salida->actualizar($datos,$where);
                    if(is_numeric($action_db)){
                       echo alerta("Se ha actualizado correctamente","success");
                    }else{
                        echo alerta($action_db,"error");
                    }                 

                }else{
                    $action_db =  $salida->insertar($datos);   
                    if(is_numeric($action_db)){
                       echo alerta("Se ha insertado correctamente","success");
                    }else{
                        echo alerta($action_db,"error");
                    }                 
                }
            }
        }  

            
        if(($this->_getParam('fechasfiltro')!=1)){
            $this->view->salidas = $salida->listar();
        }else{

            $this->view->salidas = $salida->listar(false,$this->_getParam('fecha1'),$this->_getParam('fecha2'));
            $this->view->fechaI = $this->_getParam('fecha1');
            $this->view->fechaF = $this->_getParam('fecha2');

        }
        $this->view->title = "Salidas";
        #navigation
        $this->view->nav = $this->view->navegacion(array($this->view->translate->_("Salidas")=>"/"));

        unset($_POST);
    }

    public function eliminarAction(){
            $session_sal = new Zend_Session_Namespace('salidas');
            $id = $this->_getParam("id");
            $salida = new Salidas_Model_Salida();
			$cartola = new Cartolas_Model_Cartola();
            $where = "SAL_ID = $id";
            $accion_db  = $salida->eliminar($where);
			$where2 = "CAR_IDIE = $id AND CAR_TIPO = 2";
			$accion_db2  = $cartola->eliminar($where2);
            if($accion_db == 'si'){
                $session_sal->eliminado = $accion_db;            
            }else{
                $session_sal->eliminado = 'no';
                $session_sal->mensaje = $accion_db;
            }
            $this->_redirect("/salidas/");
            exit;
    }

    public function obtenerAction(){

		$session_sal = new Zend_Session_Namespace('salidas');
		$id = $this->_getParam("id");

		$salida = new Salidas_Model_Salida();
		$where = "SAL_ID = $id";
		$session_sal->salida_editar = $salida->obtener($where);
		$this->_redirect("/salidas/");
		exit;            
    }  

    public function guardarAjaxAction(){   

        $desc = $this->_getParam("descripcion");
        $valor = $this->_getParam("valor");

        $salida = new Salidas_Model_Salida();
		$cartola = new Cartolas_Model_Cartola();

        $datos = array( 'SAL_FECHA' => date("Y-m-d H:i:s"),
                        'SAL_DESCRIPCION' => $this->_getParam('descripcion'),
                        'SAL_VALOR' => $this->_getParam('valor'));

        if($this->_getParam('id')!=""){
                
            $where = "SAL_ID = ".$this->_getParam('id');
            $action_db =  $salida->actualizar($datos,$where);
			$datos3 = array( 'CAR_IDIE' => $this->_getParam('id'),
                        'CAR_TIPO' => 2,
                        'CAR_FECHA' => date("Y-m-d H:i:s"),
                        'CAR_VALOR' => $this->_getParam('valor'));
			$where3 = "CAR_IDIE = ".$this->_getParam('id')." AND CAR_TIPO = 2";
			$results3 = $cartola->actualizar($datos3,$where3);
            if(is_numeric($action_db)){
               echo alerta("Se ha actualizado correctamente","success");
            }else{
                echo alerta($action_db,"error");
            }                 

        }else{
            $action_db =  $salida->insertar($datos);
			$datos4 = array( 'CAR_IDIE' => $action_db,
                        'CAR_TIPO' => 2,
                        'CAR_FECHA' => date("Y-m-d H:i:s"),
                        'CAR_VALOR' => $this->_getParam('valor'));
			$results4 = $cartola->insertar($datos4);
            if(is_numeric($action_db)){
               echo alerta("Se ha insertado correctamente","success");
            }else{
                echo alerta($action_db,"error");
            }                 
        }    
        $this::crearJsonAction();    
        exit;

    }  

    public function crearJsonAction(){

        $salida = new Salidas_Model_Salida();
        $arr_salidas = $salida->listar(); 

        //Creamos Archivo json
        $json_string = json_encode($arr_salidas);
        $file = JSON_PATH.'/salidas/salidas.json';
        file_put_contents($file, $json_string);
        
    }
    public function listarAjaxAction(){

        $fechai = $this->_getParam("fechai");
        $fechaf = $this->_getParam("fechaf");

        $salida = new Salidas_Model_Salida();
        $arr_salidas = $salida->listar(false,$fechai,$fechaf); 

        //Creamos Archivo json
        echo json_encode($arr_salidas);
        
        exit;
        
    }    

}