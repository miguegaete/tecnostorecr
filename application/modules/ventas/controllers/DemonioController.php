<?php 
class Ventas_DemonioController extends Zend_Controller_Action
{
    public function init()
    {
    }

	public function indexAction(){}

	public function cerrarCajasDemonioAction(){
		
		
		$cajas =  new Ventas_Model_Caja();
		$cajas_pedidos =  new Ventas_Model_Cajapedido();
		$configuraciones =  new Configuraciones_Model_Configurar();
		$config = $configuraciones->obtener(2);
		
		$tbk_debito = $config[0]->tbk_debito;
		$tbk_credito = $config[0]->tbk_credito;
		
		#Obtenemos listado de de cajas sin cerrar
		$where1 = "CAJ_ESTADO = 1"; 
		$cajas_abiertas = $cajas->listar($where1);
		
		foreach($cajas_abiertas as $caj){
			
			$pedidos = $cajas_pedidos->listar($caj->id);

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
			
		foreach($pedidos as $aux){
			
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
			$where = "CAJ_ID = ".$caj->id;
			$rs = $cajas->actualizar($datos,$where);						
			
			
		}
		exit;		
	}
	
	public function actualizarSaldosAction()
	{
		
		$productos = new Productos_Model_Producto();
		$saldos = new Productos_Model_Saldo();
		
		$producto = $productos->listar(false,false);
		
		foreach($producto as $aux)
		{
			
			$data = array( 'SAL_STOCK_INICIAL' => $aux->stock,
						   'SAL_FECHA' => date('Y-m-d'),
						   'PROD_ID' => $aux->id);
			
			$saldos->guardar($data);
			
		}
		exit;
	}
	
}