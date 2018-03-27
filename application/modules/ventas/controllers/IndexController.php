<?php

class Ventas_IndexController extends Zend_Controller_Action
{
    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario)) 
		{
			$this->_redirect("/");
		}
        $this->view->current = 'mod_ventas'; 
    }

    public function indexAction()
    {
        
        
		$this->view->current2 = 'mod_vender'; 
        $session_pro = new Zend_Session_Namespace('ventas');
        $session_usu = new Zend_Session_Namespace('login');
	

		
		
		$this->view->caja = $session_usu->caja;
		$this->view->usuario = $session_usu->nombre;
		$this->view->caja_inicio = $session_usu->caja_inicio;
		
		
        $categoria = new Productos_Model_Categoria();
        $producto =  new Productos_Model_Producto();
         
        
		
		
        #Archivos CSS
		$this->view->headLink()->appendStylesheet('/css/icons/flaticon.css');
		$this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
		$this->view->headLink()->appendStylesheet('/css/load.css');
		
		
		
		#Archivos JS
		$this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');
        $this->view->headScript()->appendFile('/js/sistema/ventas/index.js');
		$this->view->headScript()->appendFile('/js/jquery/jqueryrut/jqueryrut.js');
		$this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/jquery.validate.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/messages_es.js');		
		
        $this->view->headScript()->appendFile('/js/sistema/ventas/print.js');		
		

        $this->view->categoria = $categoria->listar();
        $this->view->producto = $producto->listar();


		
        $this->view->title = "Ventas";
    }
	
    public function ventasNuevoAction()
    {
		$this->view->current2 = 'mod_vender'; 
        $session_pro = new Zend_Session_Namespace('ventas');
        $session_usu = new Zend_Session_Namespace('login');
	

		
		
		$this->view->caja = $session_usu->caja;
		$this->view->usuario = $session_usu->nombre;
		$this->view->caja_inicio = $session_usu->caja_inicio;
		
		
        $categoria = new Productos_Model_Categoria();
		$producto =  new Productos_Model_Producto();
	$usuario = new Ventas_Model_Venta();   	
		
        #Archivos CSS
		$this->view->headLink()->appendStylesheet('/css/icons/flaticon.css');
		$this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
		$this->view->headLink()->appendStylesheet('/css/load.css');
		
		
		
		#Archivos JS
		$this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');
        $this->view->headScript()->appendFile('/js/sistema/ventas/ventas-nuevo.js');
		$this->view->headScript()->appendFile('/js/jquery/jqueryrut/jqueryrut.js');
		$this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/jquery.validate.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/messages_es.js');		
		
        $this->view->headScript()->appendFile('/js/sistema/ventas/print.js');		
		

        $this->view->categoria = $categoria->listar();
        $this->view->producto = $producto->listar();
        $mes = date('m');
        $anio = date('Y');
        $usuario_id = $session_usu->id;
        $total_usuario = $usuario->ventasporusuario_venta($mes, $anio,$usuario_id);
        $this->view->usuario_ventas = $total_usuario;
        
		
        $this->view->title = "Ventas";
    }	

    public function listarAction()
    {
		$this->view->current = 'mod_ventas_listado';
        $session_pro = new Zend_Session_Namespace('ventas');
        $productos = new Ventas_Model_Venta();

        $results = $productos->listar($this->_getParam('id'),true);
        $this->_helper->json($results);
    }

    public function obtenerAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $productos = new Ventas_Model_Venta();

        $results = $productos->obtener($this->_getParam('id'), $this->_getParam('tipo'),true);
        $this->_helper->json($results);
    }
	
    public function actualizarAction()
    {
		
        $session_pro = new Zend_Session_Namespace('ventas');
		$session_usu = new Zend_Session_Namespace('login');
		
		$idmesa = $this->_getParam("idmesa");
		
        $pedido = new Ventas_Model_Venta();

        $datos = array( 'USU_ID' => $session_usu->id,
                        'PED_FECHA' => date("Y-m-d H:i:s"),
                        'PED_ESTADO' => 1,
                        'PED_TOTAL' => $this->_getParam('total'),
                        'PED_DESCRIPCION' => $this->_getParam('desc'),
                        'PED_TIPO' => $this->_getParam('tipo'));
        //estado 1 = en pedido
        $where = "PED_ID = ".$this->_getParam('id');
		$results = $pedido->actualizar($datos,$where);
		
		echo $results;
		exit;
    }
   
    public function almacenarproAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $producto = new Ventas_Model_Pedidoproducto();
        $prod = new Productos_Model_Producto();
		$tipo = $this->_getParam('tipo'); //Parametro que recibe si es Venta o Nota de Crédito

        $datos = array( 'PRO_ID' => $this->_getParam('id'),
                        'PED_ID' => $this->_getParam('pedido'),
                        'PEDPRO_CANTIDAD' => $this->_getParam('cantidad'),
                        'PEDPRO_VALOR' => $this->_getParam('valor'),
                        'PEDPRO_IMPRESO' => $this->_getParam('cantidad'),
						'PEDPRO_DESCUENTO' => $this->_getParam('descuento'));

        $results = $producto->insertar($datos);
		if(is_numeric($results)){
			
			$where = "PRO_ID = ".$this->_getParam('id');
			$prods = $prod->obtener($where);
			
			$cantidad_final = 0;
			
			//Variable que calcula el stock
			if($tipo==2){//Si es igual a nota de credito
				$cantidad_final = ($prods->stock + $this->_getParam('cantidad'));
			}else{
				if($prods->manejostock == 1){
					$cantidad_final = ($prods->stock - $this->_getParam('cantidad'));
				}
			}
			
			$datos2 = array('PRO_STOCK' => $cantidad_final);
			$prod->actualizar($datos2,$where);
		}
        $this->_helper->json($results);
    }

    public function cargarAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $venta = new Ventas_Model_Venta();
        $agregado = new Ventas_Model_Pedidoagregado();
        $producto = new Ventas_Model_Pedidoproducto();

        $pedido = $venta->buscar($this->_getParam('id'));
        foreach ($pedido as $aux){
            $id_ped = $aux->id;
        }
        $results = $producto->listar($id_ped);
        $results2 = $agregado->listar($id_ped);
        $resultado = array_merge($pedido,$results,$results2);
        $this->_helper->json($resultado);
    }


	#Función cuando se finaliza un pedido inmediatamente
    public function finalizarAction()
    {
		#Model
        $session_pro = new Zend_Session_Namespace('ventas');
		$session_usu = new Zend_Session_Namespace('login');
		
        $pedido = new Ventas_Model_Venta();
        $cajas_pedidos = new Ventas_Model_Cajapedido();
		
		
        $datos = array( 'USU_ID' => $session_usu->id,
						'CLI_RUT' => $this->_getParam('cliente'),
                        'PED_FECHA' => date("Y-m-d H:i:s"),
                        'PED_ESTADO' => 2,
                        'PED_TOTAL' => $this->_getParam('total'),
                        'PED_SUBTOTAL' => $this->_getParam('subtotal'),
                        'PED_DESCUENTO' => $this->_getParam('descuento'),
                        'PED_TIPO' => $this->_getParam('tipo'),
						'FOR_ID' => $this->_getParam('forma'),
						"PED_TICKET" => $this->_getParam('ticket'));
						
        //Al guardar devuelve el id del pedido
        $rs = $pedido->guardar($datos);
		
		$json = new stdclass();
		
		$json->resultado = $rs;
		
		if(is_numeric($rs)){
			$datos2 = array("CAJ_ID" => $this->_getParam("id_caja"),
							"PED_ID"=> $rs);
			$cajas_pedidos->insertar($datos2);
		}
		
		$this->_helper->json($json);
    }

    public function finalizaraAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
		$session_usu = new Zend_Session_Namespace('login');
        $pedido = new Ventas_Model_Venta();
        $mesa = new Ventas_Model_Mesa();
        $domicilio = new Ventas_Model_Domicilio();
		$cartola = new Cartolas_Model_Cartola();

        $datos = array( 'USU_ID' => $session_usu->id,
                        'PED_FECHA' => date("Y-m-d H:i:s"),
                        'PED_ESTADO' => 2,
                        'PED_TOTAL' => $this->_getParam('total'),
                        'PED_DESCRIPCION' => $this->_getParam('desc'),
                        'PED_TIPO' => $this->_getParam('tipo'),
						'FOR_ID' => $this->_getParam('forma'));
        //estado 1 = en pedido
        $where2 = "PED_ID = ".$this->_getParam('id');
        $results = $pedido->actualizar($datos,$where2);
		
		$datos3 = array( 'CAR_IDIE' => $this->_getParam('id'),
                        'CAR_TIPO' => 1,
                        'CAR_FECHA' => date("Y-m-d H:i:s"),
                        'CAR_VALOR' => $this->_getParam('total'),
						'FOR_ID' => $this->_getParam('forma')
						);
		$results3 = $cartola->insertar($datos3);
		
        if($this->_getParam('mesa') != -1){
            $datos2 = array( 'MESA_ESTADO' => 0);
            $where = "MESA_ID = ".$this->_getParam('mesa');
            $results2 = $mesa->actualizar($datos2, $where);
        }
        else{
            $datos2 = array('PEDDOM_ESTADO' => 2);
            $where3 = "PED_ID = ".$this->_getParam('id');
            $results3 = $domicilio->actualizar($datos2,$where3);
        }
        $this->_helper->json($results);
    }

    public function actualizaragrAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
		$session_usu = new Zend_Session_Namespace('login');
        $agregado = new Ventas_Model_Pedidoagregado();

        $datos = array( 'PEDAGR_CANTIDAD' => $this->_getParam('cantidad'),
                        'PEDAGR_VALOR' => $this->_getParam('valor'));
        $where = "PEDAGR_ID = ".$this->_getParam('id');
        $results = $agregado->actualizar($datos,$where);
        $this->_helper->json($results);
    }

    public function actualizarproAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $producto = new Ventas_Model_Pedidoproducto();

        $datos = array( 'PEDPRO_CANTIDAD' => $this->_getParam('cantidad'),
                        'PEDPRO_VALOR' => $this->_getParam('valor'));
        $where = "PEDPRO_ID = ".$this->_getParam('id');
        $results = $producto->actualizar($datos,$where);
        $this->_helper->json($results);
    }

    public function eliminaragrAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $agregado = new Ventas_Model_Pedidoagregado();

        $where = "PEDAGR_ID = ".$this->_getParam('id');
        $results = $agregado->eliminar($where);
        $this->_helper->json($results);
    }

    public function eliminarproAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $producto = new Ventas_Model_Pedidoproducto();

        $where = "PEDPRO_ID = ".$this->_getParam('id');
        $results = $producto->eliminar($where);
        $this->_helper->json($results);
    }

    public function imprimirAction(){
		
		$accion = $this->_getParam("valor");
		$id = $this->_getParam("id");
		
		
        $venta = new Ventas_Model_Venta();
        
		//$agregado = new Ventas_Model_Pedidoagregado();
        
		$producto = new Ventas_Model_Pedidoproducto();
		$configuraciones = new Configuraciones_Model_Configurar();

        $pedido = $venta->buscarped2($id);
		$config = $configuraciones->obtener(2);

        foreach ($pedido as $aux){
            $id_ped = $aux->id;
        }	
		
        $results = $producto->listar($id_ped);
        //$results2 = $agregado->listar($id_ped);
		
        $resultado = array_merge($pedido,$config,$results);
		
		$this->_helper->json($resultado);


		
    }

    public function cargardomicilioAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $domicilio = new Ventas_Model_Domicilio();

        $results = $domicilio->listar();
        $this->_helper->json($results);
    }

    public function guardarDomicilioAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $domicilio = new Ventas_Model_Domicilio();
		

        $datos = array( "PED_ID" => $this->_getParam('pedid'),
                        'PEDDOM_NOMBRE' => '',
                        'PEDDOM_EMAIL' => '',
                        'PEDDOM_FONO' => '',
                        'PEDDOM_DIRECCION' => '',
                        'PEDDOM_ESTADO' => 1,
						'PEDDOM_HORA' => date("Y-m-d H:i:s"));
						
        $results = $domicilio->insertar($datos);
        $this->_helper->json($results);
    }

    public function actualizardomicilioAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
        $domicilio = new Ventas_Model_Domicilio();

        $datos = array( 'PEDDOM_NOMBRE' => $this->_getParam('nombre'),
                        'PEDDOM_EMAIL' => '',
                        'PEDDOM_FONO' => $this->_getParam('fono'),
                        'PEDDOM_DIRECCION' => $this->_getParam('dir'),
                        'PEDDOM_ESTADO' => 1);
        $where = "PED_ID = ".$this->_getParam('id');
        $results = $domicilio->actualizar($datos,$where);
        $this->_helper->json($results);
    }
	
	public function anularAction()
    {
        $session_pro = new Zend_Session_Namespace('ventas');
		$producto = new Ventas_Model_Pedidoproducto();	  
		$pedido = new Ventas_Model_Venta();
		
        $where = "PED_ID = ".$this->_getParam('id');
		
        $results = $producto->eliminar($where);
		$results2 = $pedido->eliminar($where);
		
        $this->_helper->json($results2);
    }
	
	public function listadoAction(){
		$this->view->current2 = 'mod_consultar_ventas';
		$session_usu = new Zend_Session_Namespace('login');
		#Archivos JS

        $this->view->headScript()->appendFile('/js/jquery/datepicker/bootstrap-datetimepicker.js');
		
		$this->view->headScript()->appendFile('/js/sistema/ventas/listado.js');
		$this->view->headScript()->appendFile('/js/sistema/ventas/imprimir-documento.js');
		
		$pedidos =  new Ventas_Model_Venta();
		
		
		if ($this->getRequest()->isPost()) {
			$fecha = $this->_getParam("fecha1");
			$fecha2 = $this->_getParam("fecha2");
			$fecha_i = date('Y-m-d 00:00:00',strtotime($fecha));
			$fecha_f = date('Y-m-d 23:59:59',strtotime($fecha2));			
			
			$where = "p.PED_ESTADO = 2 AND p.PED_FECHA BETWEEN '$fecha_i' AND '$fecha_f' ";
			$this->view->ventas = $pedidos->listado($where);
			
			$this->view->fecha_filtro = $this->_getParam("fecha1");
			$this->view->fecha_filtro2 = $this->_getParam("fecha2");
			
			
		}else{
			$fecha_i = date('Y-m-d 00:00:00');
			$fecha_f = date('Y-m-d 23:59:59');				
			$where = "p.PED_ESTADO = 2 AND p.PED_FECHA BETWEEN '$fecha_i' AND '$fecha_f'";
			$this->view->ventas = $pedidos->listado($where);
		}
		$this->view->tipo_usuario =  $session_usu->tipo;
		$this->view->usuario =  $session_usu->usuario;

		$this->view->title = "Ventas";
	}
	
	public function formasPagoAction()
	{
		$formas = new Ventas_Model_Formas();
		$results = $formas->listar();
		$this->_helper->json($results);
		exit;
		
		
		
	}
	public function aperturaCajaAction(){
		$session_usu = new Zend_Session_Namespace('login');
		
		$cajas =  new Ventas_Model_Caja();
		
		$datos = array( 'CAJ_FECHA_INICIO' => date('Y-m-d H:i'),
						'CAJ_ESTADO' => '1',
						'USU_ID' => $session_usu->id,
						'CON_ID' => $session_usu->empresa_id);
						
		$rs = $cajas->insertar($datos);
		
		$json = new stdclass();
		$json->resultado = $rs;
		
		
		if(is_numeric($rs)){
			
			$caj = $cajas->obtener($session_usu->id,1);
			$session_usu->caja = $caj->id; 
			$session_usu->caja_estado = $caj->estado; 
			$session_usu->caja_inicio = $caj->fecha_apertura; 
			
			$json->caja_inicio = $session_usu->caja_inicio;
			$json->usuario = $session_usu->nombre;
			
			$this->_helper->json($json);
		}else{
			$this->_helper->json($json);
		}
		exit;
		
	}
	public function actualizarCajaAction(){
		$session_usu = new Zend_Session_Namespace('login');
		
		$cajas =  new Ventas_Model_Caja();
		$cajas_pedidos =  new Ventas_Model_Cajapedido();
		$configuraciones =  new Configuraciones_Model_Configurar();
		$config = $configuraciones->obtener(2);
		
		$tbk_debito = $config[0]->tbk_debito;
		$tbk_credito = $config[0]->tbk_credito;
		
		$listado_pedidos = $cajas_pedidos->listar($this->_getParam('id_caja'));
		
			#Total Global
			$total_pedidos = 0;
			$subtotal_pedidos = 0;
			$descuento_pedidos = 0;
			
			#Total Efectivo
			$subtotal_efectivo = 0;
			$total_efectivo = 0;
			$total_descuentos_efectivo = 0;
			
			#Debito
			$subtotal_debito = 0;
			$total_debito = 0;
			$total_descuentos_debito = 0;

			#Credito
			$subtotal_credito = 0;
			$total_credito = 0;
			$total_descuentos_credito = 0;			

			#Notas de Crédito
			$subtotal_nota = 0;
			$total_nota = 0;
			$total_descuentos_nota = 0;
		
		foreach($listado_pedidos as $aux){
			switch($aux->id_forma_pago){
				#efectivo
				case 1:
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= $aux->total_ped;					
					
					$subtotal_efectivo+=$aux->subtotal_ped;
					$total_efectivo+=$aux->total_ped;
					$total_descuentos_efectivo+=$aux->descuento_ped;					
				break;
				#Debito
				case 2:
					
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= ($aux->total_ped - (($aux->total_ped * $tbk_debito)/100));
					
					$subtotal_debito+=$aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_debito+= ($aux->total_ped - (($aux->total_ped * $tbk_debito)/100));
										
				break;
				#Credito
				case 3:
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= ($aux->total_ped - (($aux->total_ped * $tbk_credito)/100));

					$subtotal_credito+=$aux->subtotal_ped;
					$total_descuentos_credito+= $aux->descuento_ped;									
					$total_credito+= ($aux->total_ped - (($aux->total_ped * $tbk_credito)/100));
					
				break;
				#Nota de Credito
				case 0:
					$total_pedidos-= $aux->total_ped;
					$subtotal_pedidos-= $aux->subtotal_ped;
					//$descuento_pedidos+= $aux->descuento_ped;
					
				    $subtotal_nota+=$aux->subtotal_ped;
					$total_descuentos_nota+=$aux->descuento_ped;
					$total_nota+=$aux->total_ped;
									
				break;
			}
			
		}			
		$datos = array( 'CAJ_TOTAL' => $total_pedidos,
						'CAJ_SUBTOTAL' => $subtotal_pedidos,
						'CAJ_DESCUENTOS' => $descuento_pedidos,
						'CAJ_SUBTOTAL_EFECTIVO' => $subtotal_efectivo,
						'CAJ_TOTAL_EFECTIVO' => $total_efectivo,
						'CAJ_DESCUENTOS_EFECTIVO' => $total_descuentos_efectivo,
						'CAJ_SUBTOTAL_DEBITO' => $subtotal_debito,
						'CAJ_TOTAL_DEBITO' => $total_debito,
						'CAJ_DESCUENTOS_DEBITO' => $total_descuentos_debito,
						'CAJ_SUBTOTAL_CREDITO' => $subtotal_credito,
						'CAJ_TOTAL_CREDITO' => $total_credito,
						'CAJ_DESCUENTOS_CREDITO' => $total_descuentos_credito,						
						'CAJ_SUBTOTAL_NOTAS_CREDITO' => $subtotal_nota,
						'CAJ_TOTAL_NOTAS_CREDITO' => $total_nota,
						'CAJ_DESCUENTOS_NOTAS_CREDITO' => $total_descuentos_nota
						
						);
						
		$where = "CAJ_ID = ".$this->_getParam('id_caja');
		$rs = $cajas->actualizar($datos,$where);
		
		$json = new stdclass();
		$json->resultado = $rs;		
		
		if(is_numeric($rs)){
			$session_usu->caja = 0; 
			$session_usu->caja_estado =''; 
			$session_usu->caja_inicio =''; 
			$this->_helper->json($json);
		}else{
			$this->_helper->json($json);
		}
		exit;
		
	}		
	
	public function cierreCajaAction(){
		$session_usu = new Zend_Session_Namespace('login');
		
		$cajas =  new Ventas_Model_Caja();
		$cajas_pedidos =  new Ventas_Model_Cajapedido();
		$configuraciones =  new Configuraciones_Model_Configurar();
		$config = $configuraciones->obtener(2);
		
		$tbk_debito = $config[0]->tbk_debito;
		$tbk_credito = $config[0]->tbk_credito;
		
		$listado_pedidos = $cajas_pedidos->listar($this->_getParam('id_caja'));
		
			#Total Global
			$total_pedidos = 0;
			$subtotal_pedidos = 0;
			$descuento_pedidos = 0;
			
			#Total Efectivo
			$subtotal_efectivo = 0;
			$total_efectivo = 0;
			$total_descuentos_efectivo = 0;
			
			#Debito
			$subtotal_debito = 0;
			$total_debito = 0;
			$total_descuentos_debito = 0;

			#Credito
			$subtotal_credito = 0;
			$total_credito = 0;
			$total_descuentos_credito = 0;			

			#Notas de Crédito
			$subtotal_nota = 0;
			$total_nota = 0;
			$total_descuentos_nota = 0;
		
		foreach($listado_pedidos as $aux){
			switch($aux->id_forma_pago){
				#efectivo
				case 1:
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= $aux->total_ped;					
					
					$subtotal_efectivo+=$aux->subtotal_ped;
					$total_efectivo+=$aux->total_ped;
					$total_descuentos_efectivo+=$aux->descuento_ped;					
				break;
				#Debito
				case 2:
					
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= ($aux->total_ped - (($aux->total_ped * $tbk_debito)/100));
					
					$subtotal_debito+=$aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_debito+= ($aux->total_ped - (($aux->total_ped * $tbk_debito)/100));
										
				break;
				#Credito
				case 3:
					$subtotal_pedidos+= $aux->subtotal_ped;
					$descuento_pedidos+= $aux->descuento_ped;
					$total_pedidos+= ($aux->total_ped - (($aux->total_ped * $tbk_credito)/100));

					$subtotal_credito+=$aux->subtotal_ped;
					$total_descuentos_credito+= $aux->descuento_ped;									
					$total_credito+= ($aux->total_ped - (($aux->total_ped * $tbk_credito)/100));
					
				break;
				#Nota de Credito
				case 0:
					$total_pedidos-= $aux->total_ped;
					$subtotal_pedidos-= $aux->subtotal_ped;
					//$descuento_pedidos+= $aux->descuento_ped;
					
				    $subtotal_nota+=$aux->subtotal_ped;
					$total_descuentos_nota+=$aux->descuento_ped;
					$total_nota+=$aux->total_ped;
									
				break;
			}
			
		}			
		$datos = array( 'CAJ_FECHA_TERMINO' => date('Y-m-d H:i'),
						'CAJ_ESTADO' => 0,
						'CAJ_TOTAL' => $total_pedidos,
						'CAJ_SUBTOTAL' => $subtotal_pedidos,
						'CAJ_DESCUENTOS' => $descuento_pedidos,
						'CAJ_SUBTOTAL_EFECTIVO' => $subtotal_efectivo,
						'CAJ_TOTAL_EFECTIVO' => $total_efectivo,
						'CAJ_DESCUENTOS_EFECTIVO' => $total_descuentos_efectivo,
						'CAJ_SUBTOTAL_DEBITO' => $subtotal_debito,
						'CAJ_TOTAL_DEBITO' => $total_debito,
						'CAJ_DESCUENTOS_DEBITO' => $total_descuentos_debito,
						'CAJ_SUBTOTAL_CREDITO' => $subtotal_credito,
						'CAJ_TOTAL_CREDITO' => $total_credito,
						'CAJ_DESCUENTOS_CREDITO' => $total_descuentos_credito,						
						'CAJ_SUBTOTAL_NOTAS_CREDITO' => $subtotal_nota,
						'CAJ_TOTAL_NOTAS_CREDITO' => $total_nota,
						'CAJ_DESCUENTOS_NOTAS_CREDITO' => $total_descuentos_nota
						
						);
						
		$where = "CAJ_ID = ".$this->_getParam('id_caja');
		$rs = $cajas->actualizar($datos,$where);
		
		$json = new stdclass();
		$json->resultado = $rs;		
		
		if(is_numeric($rs)){
			$session_usu->caja = 0; 
			$session_usu->caja_estado =''; 
			$session_usu->caja_inicio =''; 
			$this->_helper->json($json);
		}else{
			$this->_helper->json($json);
		}
		exit;
		
	}	
	
	public function cajaAction(){
		
		$conf = new Configuraciones_Model_Configurar();
		$this->view->conf = $conf->obtener(2);
		$this->view->current2 = 'mod_caja';
		#Archivos JS

        //$this->view->headScript()->appendFile('/js/jquery/datepicker/bootstrap-datetimepicker.js');
		$this->view->headScript()->appendFile('/js/sistema/ventas/listado.js');
		
		$cajas =  new Ventas_Model_Caja();
		
		
		if ($this->getRequest()->isPost()) {
			
			$fecha = $this->_getParam("fecha1");
			$fecha_hasta = $this->_getParam("fecha2");
			
			$fecha_i = date('Y-m-d 00:00:00',strtotime($fecha));
			$fecha_f = date('Y-m-d 23:59:59',strtotime($fecha_hasta));			
			
			$where = "c.CAJ_FECHA_INICIO BETWEEN '$fecha_i' AND '$fecha_f' ";
			$this->view->ventas = $cajas->listar($where);
			
			$this->view->fecha_filtro = $this->_getParam("fecha1");
			$this->view->fecha_filtro_hasta = $this->_getParam("fecha2");

		}else{
			$fecha_i = date('Y-m-d 00:00:00');
			$fecha_f = date('Y-m-d 23:59:59');				
			$where = " c.CAJ_FECHA_INICIO BETWEEN '$fecha_i' AND '$fecha_f'";
			$this->view->ventas = $cajas->listar($where);
		}
		
		$this->view->title = "Apertura / Cierre Caja";		

		
	}
	public function cajaDetalleAction(){ 
	
		$id_caja = $this->_getParam('id');
		$cajas_pedidos =  new Ventas_Model_Cajapedido();
		
		$listado_pedidos = $cajas_pedidos->listar($id_caja);	
		
		$html = "";
		$html.= "<hr width='100%'>";
		if(count($listado_pedidos)){
			foreach ($listado_pedidos as $aux){
				
				$html.= "<table class='table' cellspacing='5'>"; 
				$html.= "<tbody>";	
				$html.= "<tr>";
				$html.= "<td align='left' width='16.5%' ><strong>Cod. Venta:</strong> </td><td align='left' width='16.5%' >#".$aux->id_ped."</td>";
				$html.= "<td align='left' ><strong>Forma de pago:</strong> </td><td align='left' >".$aux->forma_pago."</td>";
				$html.= "<td align='left' ><strong>Fecha:</strong> </td><td align='left' >".$aux->fecha."</td>";
				$html.= "</tr>";				
				$html.= "</tbody>";
				$html.= "</table>";			
				
				
					$html.= "<table class='table table-bordered'>";
					$html.= "<thead><tr>";
					$html.= "<th>SKU</th>";
					$html.= "<th>Categoría</th>";
					$html.= "<th>Producto Nombre</th>";
					$html.= "<th>Cantidad</th>";
					$html.= "<th>Valor Producto</th>";
					$html.= "<th>Total Producto</th></tr></thead>";				
					$html.= "<tbody>";
					
				foreach($aux->productos as $aux2) {
					$html.= "<tr>";
					$html.= "<td>$aux2->sku</td>";
					$html.= "<td>$aux2->categoria</td>";
					$html.= "<td>$aux2->producto</td>";
					$html.= "<td>$aux2->cantidad</td>";
					$html.= "<td> $".formatearValor($aux2->valor_producto)."</td>";
					$html.= "<td> $".formatearValor($aux2->valor)."</td>";
					$html.= "</tr>";				
				}
				$html.= "<tr>";				
				$html.= "<td align='right' width='16.5%' colspan='5' ><strong>Subtotal:</strong> </td><td align='left' width='16.5%' > $".formatearValor($aux->subtotal_ped)."</td>";
				$html.= "</tr>";	
				$html.= "<tr>";	
				$html.= "<td align='right'  width='19.5%' colspan='5' ><strong>Descuento:</strong> </td><td align='left'  width='16.5%'> $".formatearValor($aux->descuento_ped)."</td>";
				$html.= "</tr>";	
				$html.= "<tr>";					
				$html.= "<td align='right' width='16.5%' colspan='5' ><strong>Total:</strong> </td><td align='left' width='16.5%' >$".formatearValor($aux->total_ped)."</td>";
				$html.= "</tr>";
				
					$html.= "</tbody>";
					$html.= "</table>";		
					$html.= "<hr width='100%'>";
			}			
		}
        $this->view->listado = $html;

		
		
	}
	
	public function trabajoCronAction(){
		
		
		
		$venta = new Ventas_Model_Venta();
		
		$v = $venta->buscarped(1821);
		dp($v);
		$datos = array('PED_FECHA' => date('Y-m-d'),
						'PED_TOTAL' => $v[0]->precio+500 );
		$where = 'PED_ID = 1821';
		$venta->actualizar($datos,$where);
		exit;
		
	}
	
	public function eliminarVentaAction(){
		
		try {
			
			$id = $this->_getParam("id");
			$tipo = $this->_getParam("tipo");
			
			$pedidos = new Ventas_Model_Pedidoproducto();
			$productos = new Productos_Model_Producto();
			$cajapedidos = new Ventas_Model_Cajapedido();
			$venta = new Ventas_Model_Venta();
			
			$caja = $cajapedidos->obtenerCajaporPedido($id);
			
			if($caja->caja_estado == 1){
				$listado_productos = $pedidos->listar($id);
				

				
				foreach($listado_productos as $aux){
					if($tipo == 2){
						$datos_prod = array( 'PRO_STOCK' => ($aux->stock - $aux->cantidad));
					}else{
						if($aux->manejostock ==  1){
							$datos_prod = array( 'PRO_STOCK' => ($aux->stock + $aux->cantidad));
						}
					}
					if($aux->manejostock ==  1){
						$where = "PRO_ID = $aux->idpro"; 
						$r = $productos->actualizar($datos_prod,$where);
					}
				}
				
				$where2 = "PED_ID = $id";
				$venta->eliminar($where2);
				
				$rs = true;
				$msg = "Operación Eliminada correctamente";
			}else{
				
				$msg = "No se puede eliminar esta operacion debido a que la Caja asociada se encuentra cerrada, favor contacte al administrador del sistema";
				$rs = false;
				
			}
			$error = false;
			
		}catch(Zend_Exception $ex){
			$error = true;
			$rs = false;
			$msg = $ex->getMessage();
		}
		
		
		$json = new stdclass();
		$json->rs = $rs;		
		$json->error = $error;		
		$json->msg = $msg;		
		$this->_helper->json($json);
			
		
		exit;
		
	}
	
	public function ingresarSaldoAction(){
		
		$monto =  $this->_getParam('monto');
		$id_caja =  $this->_getParam('id');
		
		$cajas = new Ventas_Model_Caja();
		
		$datos = array("CAJ_MONTO_INICIAL"=>$monto);
		$where = "CAJ_ID = ".$id_caja;
		
		$rs = $cajas->actualizar($datos,$where);
		
		$json = new stdclass();
		$json->resultado = $rs;		
		
		if(is_numeric($rs)){
			$json->rs = true;
			$json->monto = $monto;
			$this->_helper->json($json);
		}else{
			$json->rs = false;
			
			$this->_helper->json($json);
		}		
		
		exit;
	}
	
	public function listadoVentasAction(){
		
		try{
			$id_caja = $this->_getParam('id_caja');
			$ventas = new Ventas_Model_Cajapedido();
			$rs = $ventas->listar($id_caja);

			$json = new stdclass();
			$json->rs = $rs;		
			$json->error = false;		
			$json->msg = "";		
			$this->_helper->json($json);
		
		}catch(Zend_Exception $e){
			
			$error = true;
			$rs = false;
			$msg = $ex->getMessage();
			
			
		}
	}
	public function eliminarVentaCajaAction(){
		
			$id_venta = $this->_getParam('id_venta');
			$id_caja = $this->_getParam('id_caja');
			
			$ventas = new Ventas_Model_Cajapedido();
			
			$where = "CAJ_ID = $id_caja AND PED_ID = $id_venta";
			$rs = $ventas->eliminar($where);

			$json = new stdclass();
			if(is_numeric($rs)){
				$json->rs = $rs;		
				$json->error = false;		
				$json->msg = "";	
			}else{
				$json->rs = "";		
				$json->error = true;		
				$json->msg = $rs;		
			}			
						
			$this->_helper->json($json);
		
	}	
	public function agregarVentaCajaAction(){
		
			$id_venta = $this->_getParam('id_venta'); 
			$id_caja = $this->_getParam('id_caja');
			
			$ventas = new Ventas_Model_Cajapedido();
			
			$datos = array(
			
						"CAJ_ID" => $id_caja,
						"PED_ID" => $id_venta
			
			);
			
			$rs = $ventas->insertar($datos);

			$json = new stdclass();
			if(is_numeric($rs)){
				$json->rs = $rs;		
				$json->error = false;		
				$json->msg = "";	
			}else{
				$json->rs = "";		
				$json->error = true;		
				$json->msg = $rs;		
			}			
						
			$this->_helper->json($json);
		exit;
	}		
}