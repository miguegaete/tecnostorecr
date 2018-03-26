<?php

class Ventas_Model_Cajapedido extends Zend_Db_Table_Abstract
{
    protected $_name = "CAJA_PEDIDO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($id_caja = false)
    {
		$productos =  new Productos_Model_PedidoProducto();
		
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("cp"=>"CAJA_PEDIDO"),array("id_ped"=>"PED_ID"))
					->joinLeft(array("p"=>"PEDIDO"),"p.PED_ID = cp.PED_ID",array("total"=>"PED_TOTAL",
																				"subtotal"=>"PED_SUBTOTAL",
																				"descuento"=>"PED_DESCUENTO",
																				"tipo"=>"PED_TIPO",
																				"fecha" => "PED_FECHA"))
					->joinLeft(array("f"=>"FORMA_PAGO"),"f.FOR_ID = p.FOR_ID",array("forma_pago"=>"FOR_NOMBRE",
																					"id_forma" => "FOR_ID"));
                    if($id_caja){
						$sql->where("cp.CAJ_ID =".$id_caja);
					}
					
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id_ped = $rs->id_ped;
			$obj->total_ped = $rs->total;
			$obj->subtotal_ped = $rs->subtotal;
			$obj->descuento_ped = $rs->descuento;
			$obj->tipo_ped = $rs->tipo;
			$obj->forma_pago = $rs->forma_pago;
			$obj->id_forma_pago = $rs->id_forma;
			$obj->fecha = date('d/m/Y H:i',strtotime($rs->fecha));;
			$where = "pp.PED_ID = ".$rs->id_ped;
			$obj->productos = $productos->listar_productos(false,$where);
            
            $lista[] = $obj;
        }
        return $lista;
    }
	
    public function obtener($id_usuario = false,$estado = false)
    {
            $sql = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("c"=>"CAJA"),array("id" => "CAJ_ID",
                                                            "fecha_apertura"=>"CAJ_FECHA_INICIO",
                                                            "fecha_cierre"=>"CAJ_FECHA_TERMINO",
                                                            "estado"=>"CAJ_ESTADO"));
						if($id_usuario){									
							$sql->where("USU_ID = ".$id_usuario);			
						}
						if($estado){									
							$sql->where("CAJ_ESTADO =".$estado);			
						}						
						
            $rs = $this->fetchRow($sql);
            $caja = new stdClass();
            
            if($rs)
            {
                
                $caja->id = $rs->id;
                $caja->fecha_apertura = $rs->fecha_apertura;
                $caja->fecha_cierre = $rs->fecha_cierre;
                $caja->estado = $rs->estado;
            }else{
				$caja->id = false;
			}
			
			unset($sql,$rs);
			return $caja;
        
    }	
	
    public function obtenerCajaporPedido($id_pedido = false)
    {
            $sql = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array("cp"=>"CAJA_PEDIDO"),array("id" => "CAP_ID"))
						->joinLeft(array("c"=>"CAJA"),"cp.CAJ_ID = c.CAJ_ID ",array("caja_id" => "CAJ_ID",
																					"caja_estado" => "CAJ_ESTADO"))
						
						->where("cp.PED_ID  = ".$id_pedido);						
						
            $rs = $this->fetchRow($sql);
            $caja = new stdClass();
            
            if($rs)
            {
                $caja->id = $rs->id;
                $caja->caja_id = $rs->caja_id;
                $caja->caja_estado = $rs->caja_estado;
            }else{
				$caja->id = false;
			}
			
			unset($sql,$rs);
			return $caja;
        
    }		

    public function insertar($datos){

        try {
            $id = $this->insert($datos);
            return $id;
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    }

    public function actualizar($datos,$where){
        try {
            $id = $this->update($datos,$where);
            return $id;
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    } 

    public function eliminar($where){
        try {
            $id = $this->delete($where);
            return 1;
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    }
}

