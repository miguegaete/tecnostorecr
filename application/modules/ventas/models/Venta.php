<?php

class Ventas_Model_Venta  extends Zend_Db_Table_Abstract
{
    protected $_name = "PEDIDO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

    public function listar($where,$venta = false)
    { 
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PRODUCTO"),array("id" => "PRO_ID",
                                                        "id_categoria" => "CAT_ID",
                                                        "nombre"=>"PRO_NOMBRE",
                                                        "descripcion"=>"PRO_DESCRIPCION",
                                                        "valor"=>"PRO_VALOR",
                                                        "imagen"=>"PRO_IMAGEN",
														"icono"=>"PRO_ICONO",
														"sku" => "PRO_SKU"))
                    ->where("p.CAT_ID = ".$where)
                    ->order(array("p.PRO_NOMBRE ASC","p.PRO_ID ASC"));
					if($venta){
						$sql->where("p.PRO_ESTADO <> 0");
					}					

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $producto = new stdClass();
            $producto->id = $rs->id;
            $producto->id_categoria = $rs->id_categoria;
            $producto->nombre = $rs->nombre;
            $producto->nombre_corto = cortar_frase($rs->nombre,2);
            $producto->descripcion = $rs->descripcion;
            $producto->valor = $rs->valor;
            $producto->imagen = (!empty($rs->imagen)) ? $rs->imagen:"/imagenes/sitio/default_gallery_2.jpg";
			$producto->icono = (!empty($rs->icono)) ? $rs->icono:"/imagenes/sitio/default_gallery_3.jpg";
			$producto->sku = $rs->sku;
            
            $lista[] = $producto;
        }
        return $lista;
    }

    public function obtener($id, $tipo,$stock = false)
    {
            $sql = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("p"=>"PRODUCTO"),array("id" => "PRO_ID",
                                                            "nombre"=>"PRO_NOMBRE",
                                                            "descripcion"=>"PRO_DESCRIPCION",
                                                            "valor"=>"PRO_VALOR",
															"estado_oferta"=>"PRO_ESTADO_VALOR_OFERTA",
															"valor_oferta"=>"PRO_VALOR_OFERTA",
                                                            "imagen"=>"PRO_IMAGEN",
                                                            "stock"=>"PRO_STOCK",
															"sku"=>"PRO_SKU"))
                        ->where("p.PRO_ID = ".$id)
                        ->order(array("p.PRO_NOMBRE ASC","p.PRO_ID ASC"));
						if($stock){
							$sql->where("p.PRO_STOCK <> 0");
						}						
            
            $resultado = $this->fetchAll($sql);
            $lista = array();
            
            foreach($resultado as $rs)
            {
                $producto = new stdClass();
                $producto->id = $rs->id;
                $producto->nombre = $rs->nombre;
                $producto->descripcion = $rs->descripcion;
				if($rs->estado_oferta == 1){
					$producto->valor = $rs->valor_oferta;
				}else{
					$producto->valor = $rs->valor;
				}
                $producto->imagen = (!empty($rs->imagen)) ? $rs->imagen:"/imagenes/sitio/default_gallery_2.jpg";
                $producto->stock = $rs->stock;
                $producto->sku = $rs->sku;
                $producto->estado_oferta = $rs->estado_oferta;

                $lista[] = $producto;
            }
            return $lista;
        
    }

    public function guardar($datos)
	{

        try {
            $id = $this->insert($datos);
            return $id;
        }catch(Zend_Exception $e){
            return false;
        }
    }

    public function actualizar($datos,$where)
	{

        try {
            $id = $this->update($datos,$where);
            return $id;
        }catch(Zend_Exception $e){
            return $e->getMessage();
        }
    }
	
	public function eliminar($where)
	{
        try {
            $id = $this->delete($where);
            return 'si';
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    }

    public function buscar($id)
	{
        $where = "p.MESA_ID = ".$id." AND p.PED_ESTADO = 1";
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array("id" => "PED_ID",
                                                    "usuario"=>"USU_ID",
                                                    "fecha"=>"PED_FECHA",
                                                    "estado"=>"PED_ESTADO",
                                                    "total"=>"PED_TOTAL",
                                                    "descripcion"=>"PED_DESCRIPCION"))
                    ->where($where)
                    ->order(array("p.PED_ID DESC"));

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $pedido = new stdClass();
            $pedido->id = $rs->id;
            $pedido->idpro = $rs->usuario;
            $pedido->cantidad = $rs->fecha;
            $pedido->valor = $rs->estado;
            $pedido->precio = $rs->total;
            $pedido->descripcion = $rs->descripcion;
            $pedido->impreso = '';
            $pedido->nombre = '';
            $pedido->tipo = '';
            
            $lista[] = $pedido;
        }
        return $lista;
    }

    public function buscarped($id)
	{
        $where = "p.PED_ID = ".$id;
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array("id" => "PED_ID",
                                                    "usuario"=>"USU_ID",
                                                    "fecha"=>"PED_FECHA",
                                                    "estado"=>"PED_ESTADO",
                                                    "total"=>"PED_TOTAL"))
                    ->where($where)
                    ->order(array("p.PED_ID DESC"));

        
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $pedido = new stdClass();
            $pedido->id = $rs->id;
            $pedido->idpro = $rs->usuario;
            $pedido->cantidad = $rs->fecha;
            $pedido->valor = $rs->estado;
            $pedido->precio = $rs->total;
            $pedido->nombre = '';
            $pedido->tipo = '';
            
            $lista[] = $pedido;
        }
        return $lista;
    }
    
	public function buscarped2($id)
	{
        $where = "p.PED_ID = ".$id;
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array("id" => "PED_ID",
                                                    "usuario"=>"USU_ID",
                                                    "fecha"=>"PED_FECHA",
                                                    "estado"=>"PED_ESTADO",
                                                    "total"=>"PED_TOTAL",
													"subtotal" => "PED_SUBTOTAL",
													"descuento"=> "PED_DESCUENTO"))
					->joinLeft(array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array("usuario"=>"USU_NOMBRE"))						
                    ->where($where)
                    ->order(array("p.PED_ID DESC"));

        
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $pedido = new stdClass();
            $pedido->id = $rs->id;
            $pedido->fecha = date('d/m/Y',strtotime($rs->fecha));
            $pedido->hora = date('H:i',strtotime($rs->fecha));
            $pedido->estado = $rs->estado;
            $pedido->total = $rs->total;
            $pedido->subtotal = $rs->subtotal;
            $pedido->descuento = $rs->descuento;
			$pedido->usuario = $rs->usuario;
			
            
            $lista[] = $pedido;
        }
        return $lista;
    }	

	public function listado($where = false)
	{
		
		$conf = new Configuraciones_Model_Configurar();
		
		$configurar = $conf->obtener(2);
		
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array("id" => "PED_ID",
                                                        "fecha" => "PED_FECHA",
                                                        "estado"=>"PED_ESTADO",
                                                        "total"=>"PED_TOTAL",
                                                        "subtotal"=>"PED_SUBTOTAL",
                                                        "descuento"=>"PED_DESCUENTO",
                                                        "tipo"=>"PED_TIPO",
														"id_forma"=>"FOR_ID",
														"ticket_transbank"=>"PED_TICKET"))										
					->joinLeft(array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array("usuario"=>"USU_NOMBRE",
                                                                                                     "id_usuario" => "USU_ID",
                                                                                                     "nombre_usuario" => "USU_NOMBRE"
                                                                                                    ))
					->joinLeft(array("f"=>"FORMA_PAGO"),"p.FOR_ID = f.FOR_ID",array("forma"=>"FOR_NOMBRE"));
                    if($where){
						$sql->where($where);
                    }
					$sql->order(array("p.PED_FECHA DESC"));
        $resultado = $this->fetchAll($sql);
        $lista = array();
		
        $total_venta = 0;
		$total_subtotal = 0;
		
		$total_efectivo = 0;
		$total_otros = 0;
		$total_debito = 0;
		$total_credito = 0;
		
		$total_descuento = 0;
		$total_nota = 0;
		
        foreach($resultado as $rs)
        {
			if($rs->tipo==0){ //Si no es nota de credito
				$total_venta+=$rs->total;
				$total_subtotal+=$rs->subtotal;
				
			}else{ // de lo contrario resta
				$total_venta = $total_venta - $rs->total;
				$total_subtotal = $total_subtotal - $rs->subtotal;
			}
			
			$total_descuento+=$rs->descuento;
			
			if($rs->tipo == 0) //Si la venta no es nota de credito
			{
				if($rs->id_forma == 1){//Si la forma es Efectivo
					$total_efectivo+=$rs->total;
				}
				if($rs->id_forma == 2){//Si la forma es Debito
					$total_debito+=$rs->total;
				}
				if($rs->id_forma == 3){//Si la forma es Credito
					$total_credito+=$rs->total;
				}				
			}else if($rs->tipo == 2) {
				$total_nota+=$rs->total;
			}
			
			
            $pedido = new stdClass();
            $pedido->id = $rs->id;
            $pedido->fecha = date('d/m/Y',strtotime($rs->fecha));
            $pedido->hora = date('H:i',strtotime($rs->fecha));
            $pedido->estado = $rs->estado;
            
            $pedido->tipo = ($rs->tipo==0)?"Venta":"Nota de Crédito";
            $pedido->id_tipo = $rs->tipo;
            $pedido->usuario = $rs->usuario;
            $pedido->id_usuario = $rs->id_usuario;
            $pedido->nombre_usuario = $rs->nombre_usuario;
            $pedido->forma = $rs->forma;
            $pedido->id_forma = $rs->id_forma;
            $pedido->ticket_transbank = $rs->ticket_transbank;
            
			$pedido->total_venta = "$".formatearValor($total_venta);
            $pedido->total_subtotal = "$".formatearValor($total_subtotal);
            $pedido->total_descuento = "$".formatearValor($total_descuento);
            
			
			$pedido->total_debito = "$".formatearValor($total_debito);
            $pedido->total_credito = "$".formatearValor($total_credito);


			
			$total_debito_desc = (((int)$total_debito * (float)$configurar[0]->tbk_debito) / 100);
			$total_credito_desc = (((int)$total_credito * (float)$configurar[0]->tbk_credito) / 100);			
			
			$total_debito_real = ((int)$total_debito - (int)$total_debito_desc);
			$total_credito_real = ((int)$total_credito - (float)$total_credito_desc);			
			
			$pedido->total_debito_real = "$".formatearValor($total_debito_real);
            $pedido->total_credito_real = "$".formatearValor($total_credito_real);
			
			$pedido->total_debito_desc = "$".formatearValor($total_debito_desc);
            $pedido->total_credito_desc = "$".formatearValor($total_credito_desc);			
			
            $pedido->total_efectivo = "$".formatearValor($total_efectivo);
            $pedido->total_nota = "$".formatearValor($total_nota);
			$pedido->total_efectivo_caja = "$".formatearValor($total_efectivo - $total_nota);
			
			if($rs->tipo==0){ //Si es tipo venta -> Suma
            $pedido->subtotal = "$".formatearValor($rs->subtotal);
            $pedido->total = "$".formatearValor($rs->total);
			}else{ // Si es tipo Nota de Credito -> Resta
				$pedido->subtotal = " - $".formatearValor($rs->subtotal);
				$pedido->total = " - $".formatearValor($rs->total);
			}
			$pedido->descuento = "$".formatearValor($rs->descuento);
			$pedido->total_global = "$".formatearValor((($total_efectivo + $total_credito_real + $total_debito_real) - $total_nota));
            
            $lista[] = $pedido;
        }
        return $lista;		
		
	}
	
	public function productosmasvendidos(){
		
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO_PRODUCTO"),array("cantidad" => "SUM(PEDPRO_CANTIDAD)"))										
					->joinLeft(array("pr"=>"PRODUCTO"),"p.PRO_ID = pr.PRO_ID",array("sku" => "PRO_SKU"
																					,"producto" => "PRO_NOMBRE"
																					,"descripcion" => "PRO_DESCRIPCION"
																					))
					->joinLeft(array("c"=>"CATEGORIA"),"pr.CAT_ID = c.CAT_ID",array("categoria"=>"CAT_NOMBRE"))																
					->joinLeft(array("pe"=>"PEDIDO"),"p.PED_ID = pe.PED_ID",array("forma_pago"=>"FOR_ID"))																
					->where("pe.FOR_ID <> 0")
					->group("pr.PRO_ID")
					->order("SUM(PEDPRO_CANTIDAD) DESC");
		 			
		$resultado = $this->fetchAll($sql);
        foreach($resultado as $rs)
        {
            $producto = new stdClass();
            $producto->sku = $rs->sku;
            $producto->producto = $rs->producto;
            $producto->cantidad = $rs->cantidad;
            $producto->descripcion = $rs->descripcion;
            $producto->categoria = $rs->categoria;
            $lista[] = $producto;
        }
        return $lista;			
		
	}
	public function categoriasmasvendidas(){
		
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO_PRODUCTO"),array("cantidad" => "SUM(PEDPRO_CANTIDAD)"))										
					->joinLeft(array("pr"=>"PRODUCTO"),"p.PRO_ID = pr.PRO_ID")
					->joinLeft(array("c"=>"CATEGORIA"),"pr.CAT_ID = c.CAT_ID",array("categoria"=>"CAT_NOMBRE"
																					,"codigo"=> "CAT_ID"))	
					->joinLeft(array("pe"=>"PEDIDO"),"p.PED_ID = pe.PED_ID",array("forma_pago"=>"FOR_ID"))
					->where("pe.FOR_ID <> 0")					
					->group("c.CAT_ID")
					->order("SUM(PEDPRO_CANTIDAD) DESC");
					
		$resultado = $this->fetchAll($sql);
        foreach($resultado as $rs)
        {
            $producto = new stdClass();
            $producto->codigo = $rs->codigo;
            $producto->cantidad = $rs->cantidad;
            $producto->categoria = $rs->categoria;
            $lista[] = $producto;
        }
        return $lista;			
		
	}
	
	public function cantidadproductosporcategoria(){
		
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("pr"=>"PRODUCTO"),array("cantidad" => "SUM(PRO_STOCK)"))										
					->joinLeft(array("c"=>"CATEGORIA"),"pr.CAT_ID = c.CAT_ID",array("categoria"=>"CAT_NOMBRE"
																					,"codigo"=> "CAT_ID"))					
					->group("c.CAT_ID")
					->order("SUM(pr.PRO_STOCK) DESC")
					->limit(5,0); 
					
					
					
		$resultado = $this->fetchAll($sql);
        foreach($resultado as $rs)
        {
            $producto = new stdClass();
            $producto->codigo = $rs->codigo;
            $producto->cantidad = $rs->cantidad;
            $producto->categoria = ucfirst($rs->categoria);
            $lista[] = $producto;
        }
        return $lista;			
		
	}
	
	public function ventasporusuario_venta($mes,$anio,$usuario = false){

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array(
														"cantidad" => "COUNT(PED_ID)",
														"total_ventas" => "SUM(PED_TOTAL)"
														))										
					->joinLeft(array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array(   "id_codigo"=>"USU_ID",
                                                                                                        "usuario"=>"USU_NOMBRE",
                                                                                                        "comision"=> "USU_COMISION"
                                                                                                    ))
					->where("p.FOR_ID <> 0")
					->where("MONTH(p.PED_FECHA) = $mes")
					->where("YEAR(p.PED_FECHA) = $anio")
					->group("u.USU_ID")
					->order("COUNT(PED_ID) DESC");
        
                if($usuario){
                
                       $sql->where("u.USU_ID = $usuario");
                
                }					
                
		$resultado = $this->fetchAll($sql);	
            
                //dp($resultado);exit;    
        if($usuario){
            ($ventas_efectivo = $this->ventasPorTipo(1, $mes, $anio, $usuario)); 
            ($ventas_debito = $this->ventasPorTipo(2, $mes, $anio, $usuario)); 
            ($ventas_credito = $this->ventasPorTipo(3, $mes, $anio, $usuario)); 
            ($ventas_nota = $this->ventasPorTipo(0, $mes, $anio, $usuario));             
        }

		
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            
                //dp($resultado);exit;    
            if(!$usuario){
                ($ventas_efectivo = $this->ventasPorTipo(1, $mes, $anio, $rs->id_codigo)); 
                ($ventas_debito = $this->ventasPorTipo(2, $mes, $anio, $rs->id_codigo)); 
                ($ventas_credito = $this->ventasPorTipo(3, $mes, $anio, $rs->id_codigo)); 
                ($ventas_nota = $this->ventasPorTipo(0, $mes, $anio, $rs->id_codigo));             
            }            
            
            $obj->cantidad_efectivo = $ventas_efectivo->cantidad;
            (int)$obj->total_efectivo = $ventas_efectivo->ped_total;
 
            $obj->cantidad_debito = $ventas_debito->cantidad;
            (int)$obj->total_debito = $ventas_debito->ped_total;

            $obj->cantidad_credito = $ventas_credito->cantidad;
            (int)$obj->total_credito = $ventas_credito->ped_total;

            $obj->cantidad_nota = $ventas_nota->cantidad;
            (int)$obj->total_nota = $ventas_nota->ped_total;            
            
            
            $obj->cantidad = ($obj->cantidad_efectivo + $obj->cantidad_debito + $obj->cantidad_credito - $obj->cantidad_nota);
            $obj->total_ventas = "$".formatearValor($obj->total_efectivo + $obj->total_debito + $obj->total_credito - $obj->total_nota);
            $obj->total_ventas_b = ($obj->total_efectivo + $obj->total_debito + $obj->total_credito - $obj->total_nota);
            
            $obj->usuario = $rs->usuario;
            $obj->id_usuario = $rs->id_codigo;
            $obj->comision = $rs->comision;

            $obj->total_ventas_comision = "$". formatearValor(($obj->comision * $obj->total_ventas_b) /100);
            $obj->total_ventas_comision_b = ($obj->comision * $obj->total_ventas_b) /100;
            
            $lista[] = $obj;
        }
        return $lista;			
		
	}	
	public function ventaspormes(){
		//$sql = new Zend_Db_Statement_Mysqli($this, "SET lc_time_names = 'es_VE");
		

		
        $sql = 	$this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array("cantidad" => "COUNT(*)"
													,"mes_numero"=> "CASE MONTH(PED_FECHA) 
																		WHEN 1 THEN 'Enero' 
																		WHEN 2 THEN 'Febrero' 
																		WHEN 3 THEN 'Marzo' 
																		WHEN 4 THEN 'Abril' 
																		WHEN 5 THEN 'Mayo' 
																		WHEN 6 THEN 'Junio' 
																		WHEN 7 THEN 'Julio' 
																		WHEN 8 THEN 'Agosto' 
																		WHEN 9 THEN 'Septiembre' 
																		WHEN 10 THEN 'Octubre' 
																		WHEN 11 THEN 'Noviembre' 
																		WHEN 12 THEN 'Diciembre' 
																	END"
													,"mes_nombre"=> "DATE_FORMAT(PED_FECHA,'%M')"
													,"ano"=> "YEAR(PED_FECHA)"
													,"total_dinero"=>"SUM(PED_TOTAL)"
													,"total_descuento" => "SUM(PED_DESCUENTO)"))		
					//->joinLeft(array("pp"=>"PEDIDO_PRODUCTO"),"p.PED_ID = pp.PED_ID",array("productos_cantidad"=>"SUM(PEDPRO_CANTIDAD)"))
					
					->group("MONTH(PED_FECHA)");
		
					
		$resultado = $this->fetchAll($sql);
		$total = 0;
		$total_descuento = 0;
        foreach($resultado as $rs)
        {
			$total= $total + $rs->total_dinero;
			$total_descuento= $total_descuento + $rs->total_descuento;
			
            $mes = new stdClass();
            $mes->ano = $rs->ano;
            $mes->cantidad = $rs->cantidad;
            $mes->productos_cantidad = '-';//$rs->productos_cantidad;
            $mes->mes_nombre = $rs->mes_nombre;
            $mes->mes_numero = $rs->mes_numero;
            $mes->total_dinero = "$".formatearValor($rs->total_dinero);
            $mes->total_dinero_int = "$".formatearValor($total);
            $mes->total_descuento = "$".formatearValor($rs->total_descuento);
            $mes->total_descuento_int = "$".formatearValor($total_descuento);
            $lista[] = $mes;
        }
        return $lista;			
		
	}

	public function ventaspormesnueva($anio = false){
		//$sql = new Zend_Db_Statement_Mysqli($this, "SET lc_time_names = 'es_VE");
		

		
        $sql = 	$this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"CAJA"),array("cantidad" => "COUNT(*)"
													,"mes_numero"=> "CASE MONTH(CAJ_FECHA_INICIO) 
																		WHEN 1 THEN 'Enero' 
																		WHEN 2 THEN 'Febrero' 
																		WHEN 3 THEN 'Marzo' 
																		WHEN 4 THEN 'Abril' 
																		WHEN 5 THEN 'Mayo' 
																		WHEN 6 THEN 'Junio' 
																		WHEN 7 THEN 'Julio' 
																		WHEN 8 THEN 'Agosto' 
																		WHEN 9 THEN 'Septiembre' 
																		WHEN 10 THEN 'Octubre' 
																		WHEN 11 THEN 'Noviembre' 
																		WHEN 12 THEN 'Diciembre' 
																	END"
													,"mes_nombre"=> "DATE_FORMAT(CAJ_FECHA_INICIO,'%M')"
													,"ano"=> "YEAR(CAJ_FECHA_INICIO)"
													,"total_debito"=>"SUM(CAJ_TOTAL_DEBITO)"
													,"total_credito"=>"SUM(CAJ_TOTAL_CREDITO)"
													,"total_efectivo"=>"SUM(CAJ_TOTAL_EFECTIVO)"
													,"total_nota_credito"=>"SUM(CAJ_TOTAL_NOTAS_CREDITO)"
													,"ventas_total"=>"( SELECT SUM( pedpro.PEDPRO_CANTIDAD )
																		FROM PEDIDO AS ped
																		INNER JOIN PEDIDO_PRODUCTO AS pedpro ON ped.PED_ID = pedpro.PED_ID
																		WHERE YEAR( ped.PED_FECHA ) = YEAR(c.CAJ_FECHA_INICIO)
																		AND MONTH( ped.PED_FECHA ) = MONTH(c.CAJ_FECHA_INICIO)
																		AND ped.PED_TIPO != 2)"
													//,"ventas_total"=>"fn_cantidadVentasMensuales(MONTH(CAJ_FECHA_INICIO),YEAR(CAJ_FECHA_INICIO))"
													))		
					//->joinLeft(array("pp"=>"PEDIDO_PRODUCTO"),"p.PED_ID = pp.PED_ID",array("productos_cantidad"=>"SUM(PEDPRO_CANTIDAD)"))
					
					->group("MONTH(CAJ_FECHA_INICIO)");
		
					if($anio)
						$sql->where("YEAR(CAJ_FECHA_INICIO) = $anio");
				

		$resultado = $this->fetchAll($sql);
		$total = 0;
		$total_total = 0;
        foreach($resultado as $rs)
        {
			$total = 0;
			//$total= $total + $rs->total_dinero;
			//$total_descuento= $total_descuento + $rs->total_descuento;
			
            $mes = new stdClass();
            $mes->ano = $rs->ano;
            $mes->cantidad = $rs->ventas_total;
            $mes->productos_cantidad = '-';//$rs->productos_cantidad;
            $mes->mes_nombre = $rs->mes_nombre;
            $mes->mes_numero = $rs->mes_numero;
            
			#Debito
			$mes->total_debito = "$".formatearValor($rs->total_debito);
			#Credito
			$mes->total_credito = "$".formatearValor($rs->total_credito);
			
			#Efectivo
			$mes->total_efectivo = "$".formatearValor($rs->total_efectivo);
			
			#Notas Credito
			$mes->total_nota_credito = "$".formatearValor($rs->total_nota_credito);
			
			$total = $total + ($rs->total_debito);
			$total = $total + ($rs->total_credito);
			$total = $total + ($rs->total_efectivo);
			$total = $total - ($rs->total_nota_credito);	

			$total_total = $total_total + ($rs->total_debito);
			$total_total = $total_total + ($rs->total_credito);
			$total_total = $total_total + ($rs->total_efectivo);
			$total_total = $total_total - ($rs->total_nota_credito);
			
			$mes->total_final = "$".formatearValor($total);
			$mes->total_total = "$".formatearValor($total_total);
			
			
			/*$mes->total_dinero = "$".formatearValor($rs->total_dinero);
            $mes->total_dinero_int = "$".formatearValor($total);
			
            $mes->total_descuento = "$".formatearValor($rs->total_descuento);
            $mes->total_descuento_int = "$".formatearValor($total_descuento);*/
            $lista[] = $mes;
        }
        return $lista;			
		
	}	
        
        private function ventasPorTipo($tipo,$mes,$anio,$id_usuario)
        {
            $configuracion = new Configuraciones_Model_Configurar();
            
            $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from
                        (
                            array("p"=>"PEDIDO"),array
                                                    (
                                                    "cantidad" => "COUNT(PED_ID)",
                                                    "ped_total" => "SUM(PED_TOTAL)"
                                                    )
                        )										
                    ->joinLeft
                            (
                                array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array
                                                                                (
                                                                                "id_codigo"=>"USU_ID",
                                                                                "usuario"=>"USU_NOMBRE",
                                                                                "comision"=> "USU_COMISION"
                                                                                )
                            )
                    ->where("p.FOR_ID = $tipo")
                    ->where("MONTH(p.PED_FECHA) = $mes")
                    ->where("YEAR(p.PED_FECHA) = $anio")
                    ->where("u.USU_ID = $id_usuario")
                    ->group("u.USU_ID")
                    ->order("COUNT(PED_ID) DESC");
        
            $resultado = $this->fetchRow($sql);
            $obj = new stdClass();
            
            $config = $configuracion->obtener(2);
            $obj->cantidad = (!empty($resultado->cantidad))?$resultado->cantidad:0;

            if($tipo == 2){
                $tbk_debito = (($resultado->ped_total * $config[0]->tbk_debito) / 100);
                $obj->ped_total = (!empty($resultado->ped_total))?($resultado->ped_total - $tbk_debito):0;
            }else if($tipo == 3){
                $tbk_credito = (($resultado->ped_total * $config[0]->tbk_credito) / 100);
                $obj->ped_total = (!empty($resultado->ped_total))?($resultado->ped_total - $tbk_credito):0;
            }else{
                $obj->ped_total = (!empty($resultado->ped_total))?$resultado->ped_total:0;
            }
                   
            unset($sql,$resultado);
            return $obj;
        
        }
        
	public function ventasporusuario($mes,$anio,$usuario = false){

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array(
														"cantidad" => "COUNT(PED_ID)",
														"total_ventas" => "SUM(PED_TOTAL)"
														))										
					->joinLeft(array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array(   "id_codigo"=>"USU_ID",
                                                                                                        "usuario"=>"USU_NOMBRE",
                                                                                                        "comision"=> "USU_COMISION"
                                                                                                    ))
					->where("p.FOR_ID <> 0")
					->where("MONTH(p.PED_FECHA) = $mes")
					->where("YEAR(p.PED_FECHA) = $anio")
					->group("u.USU_ID")
					->order("COUNT(PED_ID) DESC");
                if($usuario){
                
                       $sql->where("u.USU_ID = $usuario");
                
                }					
		$resultado = $this->fetchAll($sql);

        $sql2 = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PEDIDO"),array(
														"cantidad" => "COUNT(PED_ID)",
														"total_ventas" => "SUM(PED_TOTAL)"
														))										
					->joinLeft(array("u"=>"USUARIO"),"p.USU_ID = u.USU_ID",array("id_codigo"=>"USU_ID",
																				"usuario"=>"USU_NOMBRE"))
					->where("p.FOR_ID = 0")
					->where("MONTH(p.PED_FECHA) = $mes")
					->where("YEAR(p.PED_FECHA) = $anio")
					->group("u.USU_ID")
					->order("COUNT(PED_ID) DESC");
                if($usuario){
                
                       $sql2->where("u.USU_ID = $usuario");
                
                }    					
		$resultado2 = $this->fetchAll($sql2);	
            

                
		
        foreach($resultado as $rs)
        {
            $usuario = new stdClass();
            $usuario->cantidad = $rs->cantidad;
            $usuario->id_codigo = $rs->id_codigo;
            $usuario->usuario = $rs->usuario;
            (int)$usuario->comision = $rs->comision;
			foreach($resultado2 as $rs2)
			{
				if($rs->id_codigo == $rs2->id_codigo){
					$total_nota = $rs2->total_ventas;
				}

			}
			
            $usuario->total_ventas = "$".formatearValor($rs->total_ventas-$total_nota);
            $usuario->total_ventas_b = ($rs->total_ventas-$total_nota);
            $usuario->total_ventas_comision = "$". formatearValor(($usuario->comision * $usuario->total_ventas_b) /100);
            (int)$usuario->total_ventas_comision_b = ($usuario->comision * $usuario->total_ventas_b) /100;
            $lista[] = $usuario;
        }
        return $lista;			
		
	}        

}