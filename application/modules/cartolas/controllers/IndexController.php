<?php

class Cartolas_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->view->current = 5; 
        $session_usu = new Zend_Session_Namespace('login');
        if(!isset($session_usu->tipo)){
            $this->_redirect("/");
        }else{
            if($session_usu->tipo!=1){
                $this->_redirect("/ventas/");
            }
        }
        //$this::crearJsonAction();

    }

    public function indexAction()
    {

        $session_sal = new Zend_Session_Namespace('cartolas');
        $cartola = new Cartolas_Model_Cartola();
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/cartola/index.js');
		
		$this->view->title = "Cartolas";
        #navigation
        $this->view->nav = $this->view->navegacion(array($this->view->translate->_("Cartolas")=>"/"));
        #Eliminar Validación
        /*if(isset($session_sal->eliminado)){
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

        unset($_POST);*/
    }
	
	public function cargaringresoAction()
    {
        $session_pro = new Zend_Session_Namespace('Cartola');
        $agregado = new Ventas_Model_Pedidoagregado();
        $producto = new Ventas_Model_Pedidoproducto();

        $results = $producto->listar($this->_getParam('id'));
        $results2 = $agregado->listar($this->_getParam('id'));
        $resultado = array_merge($results,$results2);
        $this->_helper->json($resultado);
    }
	
	public function cargaregresoAction()
    {
        $session_pro = new Zend_Session_Namespace('Cartola');
		$id = $this->_getParam("id");

		$salida = new Salidas_Model_Salida();
		$where = "SAL_ID = $id";
		$results = $salida->obtener($where);
        $this->_helper->json($results);
    }

    /*public function eliminarAction(){
            $session_sal = new Zend_Session_Namespace('salidas');
            $id = $this->_getParam("id");
            $salida = new Salidas_Model_Salida();
            $where = "SAL_ID = $id";
            $accion_db  = $salida->eliminar($where); 
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
        
    }*/
	
    public function listarAjaxAction(){

        $fecha = $this->_getParam("fecha");

        $cartola = new Cartolas_Model_Cartola();
        $arr_cartola = $cartola->listar(false,$fecha); 

        //Creamos Archivo json
        echo json_encode($arr_cartola);
        
        exit;
    }
	public function exportarpdfAction(){
		
		$fecha = $this->_getParam("fecha");
		$salida = new Salidas_Model_Salida();
		$agregado = new Ventas_Model_Pedidoagregado();
		$producto = new Ventas_Model_Pedidoproducto();
		$cartola = new Cartolas_Model_Cartola();
		$IngresoEgreso = $cartola->listar(false,$fecha);
		$html = "<div style='position: relative;min-height: 1px;padding-left: 15px;padding-right: 15px;'><div style='padding-bottom: 9px;margin: 20px 0 20px;'><h2>Cartola (Diaria)</h2></div></div>";
		$html.= "<table style='width: 100%;max-width: 100%;margin-bottom: 20px;'>";
		$html.= "<thead><tr><th style='vertical-align: bottom;background-color:gray;color:white;padding:8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>ID</th><th style='vertical-align: bottom;background-color:gray;color:white;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>Fecha</th><th style='vertical-align: bottom;background-color:gray;color:white;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>Tipo</th><th style='vertical-align: bottom;background-color:gray;color:white;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>Descripción</th><th style='vertical-align: bottom;background-color:gray;color:white;padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>Total</th></tr></thead>";
		$html.= "<tbody>";
		$total = 0;
		foreach ($IngresoEgreso as $aux){
			$descripcion = '';
			$idie = $aux->idie;
			$tipo = $aux->tipo;
			$valor = str_replace(".", "", $aux->valor);
			if($tipo == 1){
				$total = $total+$valor;
				$tipod = "Ingreso";
				$results = $producto->listar($idie);
				$results2 = $agregado->listar($idie);
				$resultado = array_merge($results,$results2);
				
				$descripcion = "<ul style='padding-left:0px;'>";
				foreach ($resultado as $aux2){
					$descripcion.= "<li>".$aux2->nombre." x ".$aux2->cantidad." $".$aux2->valor."</li>";
				}
				$descripcion.= "</ul>";
				
			}
			else{
				$total = $total-$valor;
				$tipod = "Egreso";
				$where = "SAL_ID = $idie";
				$resultado = $salida->obtener($where);
				foreach ($resultado as $aux2){
					$descripcion .= $aux2->descripcion;
				}
			}
			$html.= "<tr><td style='padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>".$aux->idie."</td><td style='padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>".$aux->fecha."</td><td style='padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>".$tipod."</td><td style='padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>".$descripcion."</td><td style='padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #dddddd;'>$".$aux->valor."</td></tr>";
        }
		$html.= "<tr><td class='text-right' colspan='4'><h3>Total</h3></td><td><h3>$".formatearValor($total)."</h3></td></tr>";
		$html.= "</tbody>";
		$html.= "</table>";
        $this->view->listado = $html;

		//dp($this->view->listado);
		
		
		
		
		
	}

}