<?php

class Movimientos_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}		
		$this->view->current = 'mod_inventario';
    }
	
	public function indexAction(){
		
		$this->view->current2 = 'mod_movimientos';
        $session_pro = new Zend_Session_Namespace('productos');
        $producto = new Productos_Model_Producto();
        $movimiento = new Movimientos_Model_Movimiento();
        
        $this->view->headScript()->appendFile('/js/sistema/movimientos/index.js');
		
		#Titulo
		$this->view->title = "Movimientos";		
		$this->view->movimientos = $movimiento->listar();
	}
	
	public function nuevoAction(){
		$this->view->current2 = 'mod_movimientos';
		$session_pro = new Zend_Session_Namespace('productos');
		$producto = new Productos_Model_Producto();
		$movimiento = new Movimientos_Model_Movimiento();
		
		
		
		$this->view->headScript()->appendFile('/js/sistema/movimientos/nuevo.js');
        #Insertar Validación
        if ($this->getRequest()->isPost()) {
			
			
			$datos = array( 'MOV_FECHA' => date("Y-m-d"),
							'PRO_ID' => $this->_getParam('id'),
							'MOV_CANTIDAD' => $this->_getParam('cantidad'),
							'MOV_NOMBRE_PRODUCTO' => $this->_getParam('nombre'),
							'MOV_TIPO' => $this->_getParam('tipo'),
							'MOV_STOCK_AL_INSERTAR' => $this->_getParam('stock')
							);
			$where = "PRO_ID = ".$this->_getParam('id');
			$prod_aux = $producto->obtener($where);
			
			if(($this->_getParam('tipo') == 'Salida') AND ($prod_aux->stock < $this->_getParam('cantidad')) ){
				echo alerta("No se puede generar un movimiento de salida, cuando la cantidad supera al stock actual","error");
			}else{	
			
			$accion_db = $movimiento->insertar($datos);
			
			if(is_numeric($accion_db)){
				
					$where2 = "PRO_ID = ".$this->_getParam('id');
					$prod = $producto->obtener($where2);				
					
					
					if($datos['MOV_TIPO']=='Entrada'){
						$cantidad_final = ($this->_getParam('cantidad') + $prod->stock);
					}else{
						$cantidad_final = ($prod->stock - $this->_getParam('cantidad'));
					}
					
					$where = "PRO_ID = ".$this->_getParam('id');
					$datos2 = array('PRO_STOCK' => $cantidad_final);
					$accion_db2 = $producto->actualizar($datos2,$where);
					
					if(is_numeric($accion_db2)){
						echo alerta("Se ha insertado correctamente","success");
					}else{
						echo alerta($action_db,"error");
					}
					
			}else{
				echo alerta($action_db,"error");
			}
			
			}


		}			
		
		
		$this->view->title = "Nuevo Movimiento";	
		
	}
	
	public function comprarAction()
	{
		$this->view->current2 = 'mod_comprar';
		#CSS
		$this->view->headLink()->appendStylesheet('/css/bootstrap-chosen.css');
		
		#JS
		$this->view->headScript()->appendFile('/js/jquery/choosen/chosen.jquery.min.js');
		$this->view->headScript()->appendFile('/js/sistema/movimientos/comprar.js');
		
		$productos =  new Productos_Model_Producto();
		$formas = new Ventas_Model_Formas();
		$documentos = new Ventas_Model_Documento();
		$configuraciones = new Configuraciones_Model_Configurar();
		$proveedores = new Movimientos_Model_Proveedor();
		
		
		$this->view->proveedores = $proveedores->listar();
		$this->view->producto = $productos->listar(false,true,true);
		$this->view->formas = $formas->listar();
		$conf = $configuraciones->obtener(2);
		
		$this->view->iva = $conf[0]->iva;
		
		$where = "DOC_ESTADO = 1";
		$this->view->documentos = $documentos->listar($where);
		$this->view->title='Comprar';
	}
	
	public function guardarCompraAction(){
		
		$session_usu = new Zend_Session_Namespace('login');
		
		$compras  = new Movimientos_Model_Comprar();
		
		$datos = array( 'COM_FECHA' => date('Y-m-d',strtotime($this->_getParam('txtFechaCompra'))),
						'FOR_ID' => $this->_getParam('ddlFormaPago'),
						'DOC_ID' => $this->_getParam('ddlDocumento'),
						'COM_SUBTOTAL' => $this->_getParam('txtsubtotal'),
						'COM_IVA' => $this->_getParam('txtiva'),
						'COM_TOTAL' => $this->_getParam('txttotal'),
						'USU_ID' => $session_usu->id,
						'PROV_ID' => $this->_getParam('ddlProveedores'),
						'COM_NUMERODOCUMENTO' => $this->_getParam('txtNumero')
		);
		
		$rs = $compras->guardar($datos);
		
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
	
	public function guardarProductosCompraAction(){
		
		$productos = $this->_getParam("productos");
		$id_compra = $this->_getParam("id");
		
		$producto = new Productos_Model_Producto();
		$compra_productos = new Movimientos_Model_ComprarProducto();
		
		if(count($productos) > 0) {
			
			for($i=0;$i < count($productos); $i++){
				
				//Obtengo el producto
				$where = "PRO_ID = ".$productos[$i]['id'];
				$prod_aux = $producto->obtener($where);	
				
				
				$data = array("COM_ID" => $id_compra,
							  "PRO_ID"=> $productos[$i]['id'],
							  "COMPRO_CANTIDAD" => $productos[$i]['cantidad'],
							  "COMPRO_COSTO" => $productos[$i]['precio_costo'],
							  "COMPRO_TOTAL" => $productos[$i]['total']
							  );	  
				$rs = $compra_productos->guardar($data);
				
				if(!is_numeric($rs)){
					$rs = $rs;
				}elseif(is_numeric($rs))
				{
					$stock_nuevo = ($productos[$i]['cantidad'] + $prod_aux->stock);	
					$datos = array('PRO_STOCK' => $stock_nuevo);
					$accion_db2 = $producto->actualizar($datos,$where);					
				}
				
				
			}
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
	
	public function consultarComprasAction()
	{
		
		$this->view->current2 = 'mod_consultar_compras';
		$this->view->headScript()->appendFile('/js/sistema/movimientos/consultar-compras.js');
		
		$this->view->title = "Consultar Compras";
	}
	
	public function listarComprasAction()
	{
		$compras = new Movimientos_Model_Comprar();
		
		$rs = $compras->listar();
		
		
		$html = "";
		$contador = 0;
		
        if (count($rs)) {
              foreach ($rs as $aux){ 
				$contador++;
                $html.='<tr>';
				$html.='<td>'.$contador.'</td>';
				$html.='<td>'.$aux->fecha.'</td>';
                $html.='<td>'.$aux->documento.'</td>';
                $html.='<td><a href="" id="'.$aux->id.'" class="num_doc">'.$aux->num_documento.'</a></td>';
                $html.='<td>'.$aux->proveedor.'</td>';
                $html.='<td>'.$aux->rut_proveedor.'</td>';
                $html.='<td>'.$aux->forma_pago.'</td>';
                $html.='<td>'.$aux->total.'</td>';

				/*$html.='<div class="btn-group">
				  <button type="button" class="btn btn-default btn-sm btn-editar editar" value="/clientes/editar/'.$aux->rut.'/" ><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color:#555;"></span> Modificar</button>
				  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="'.$aux->rut.'" class="eliminar" ><span class="glyphicon glyphicon-trash" aria-hidden="true" style="color:#555;"></span> Eliminar</a></li>
				  </ul>
				</div>';*/
                $html.='</tr>';
              } 
          }else{ 
                $html.='<tr>';
                $html.='<td colspan="8" align="center">No existen Compras</td>';
                $html.='</tr>';
          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;
	}
	
	public function listarProductosComprasAction()
	{
		$id_compra = $this->_getParam("id_compra");
		$compras = new Movimientos_Model_ComprarProducto();
			
		$rs = $compras->listar($id_compra);
		
		
		$html = "";
		$subtotal;
		$iva;
		$total;
		
        if (count($rs)) {
              foreach ($rs as $aux){ 
			  
                $html.='<tr>';
				$html.='<td align="center">'.$aux->cantidad.'</td>';
				$html.='<td class="txt-capital">'.$aux->producto.'</td>';
                $html.='<td align="center" >'.$aux->costo.'</td>';
                $html.='<td align="center" >'.$aux->total.'</td>';
                $html.='</tr>';
				
				$subtotal = $aux->subtotal;
				$iva = $aux->iva;
				$total = $aux->total_com;
				
				$proveedor = $aux->proveedor;
				$rut_proveedor = $aux->rut_proveedor;
				
              } 
			  
                $html.='<tr class="line-top">';
                $html.='<td colspan="2"></td>';
                $html.='<td align="right" class="txt-bold">SUBTOTAL</td>';
                $html.='<td align="center" class="txt-bold"  >'.$subtotal.'</td>';
                $html.='</tr>';	
				$html.='<tr>';
                $html.='<td colspan="2"></td>';
                $html.='<td align="right" class="txt-bold" >IVA</td>';
                $html.='<td align="center" class="txt-bold" >'.$iva.'</td>';
                $html.='</tr>';	
				$html.='<tr class="line-bottom">';
                $html.='<td colspan="2"></td>';
                $html.='<td align="right" class="txt-bold" >TOTAL</td>';
                $html.='<td align="center" class="txt-bold" >'.$total.'</td>';
                $html.='</tr>';	
				
				$html.='<tr>';
                $html.='<td align="left" colspan="4" class="cont-price-vender txt-bold">PROVEEDOR</td>';				
                $html.='</tr>';					
				$html.='<tr>';
                $html.='<td align="left" class="txt-bold" >Nombre o Razón Social</td>';
                $html.='<td align="left" colspan="3" >'.$proveedor.'</td>';
				$html.='</tr>';
				$html.='<tr class="line-bottom">';
                $html.='<td align="left" class="txt-bold" >RUT</td>';
                $html.='<td align="left" colspan="3" >'.$rut_proveedor.'</td>';								
                $html.='</tr>';			
				
				
          }else{ 

          }
		
		
		
		$json = new stdclass();
			$json->rs = $html;
		
		$this->_helper->json($json);
		exit;		
	}
	
	public function saldosMovimientosAction()
	{
		$anio= (int)date('Y');
		$mes = (int)date('m');
		

		
		$this->view->anios = anios(2016,$anio);
		$this->view->meses = meses();
		$this->view->current2 = 'mod_saldos_movimientos';
		$productos = new Productos_Model_Producto();
		$this->view->headScript()->appendFile('/js/sistema/movimientos/saldos.js');
		if ($this->getRequest()->isPost()) {
		$this->view->anio = $this->_getParam('anio');
		$this->view->mes = $this->_getParam('mes');			
			$this->view->saldos = $productos->listarSaldos($this->_getParam('mes'),$this->_getParam('anio'));
		}else{
			$this->view->anio = $anio;
			$this->view->mes = $mes;			
			$this->view->saldos = $productos->listarSaldos($mes,$anio);
		}
		$this->view->title = "Resumen Saldos y Movimientos";
		
	}
	
	
	
	
}