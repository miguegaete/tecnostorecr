<?php

class Ventas_Model_Caja extends Zend_Db_Table_Abstract
{
    protected $_name = "CAJA";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($where = false)
    {
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"CAJA"),array(		"id" => "CAJ_ID",
															"fecha_inicio"=>"CAJ_FECHA_INICIO",
															"fecha_termino"=>"CAJ_FECHA_TERMINO",
													   		"subtotal" =>"CAJ_SUBTOTAL",
															"descuentos" =>"CAJ_DESCUENTOS",
															"total" =>"CAJ_TOTAL",
															"estado" =>"CAJ_ESTADO",
															"subtotal_efectivo" =>"CAJ_SUBTOTAL_EFECTIVO",
															"descuentos_efectivo" =>"CAJ_DESCUENTOS_EFECTIVO",
															"total_efectivo" =>"CAJ_TOTAL_EFECTIVO",
															"subtotal_debito" =>"CAJ_SUBTOTAL_DEBITO",
															"descuentos_debito" =>"CAJ_DESCUENTOS_DEBITO",
															"total_debito" =>"CAJ_TOTAL_DEBITO",	
															"subtotal_credito" =>"CAJ_SUBTOTAL_CREDITO",
															"descuentos_credito" =>"CAJ_DESCUENTOS_CREDITO",
															"total_credito" =>"CAJ_TOTAL_CREDITO",
															"subtotal_notas" =>"CAJ_SUBTOTAL_NOTAS_CREDITO",
															"total_descuentos_notas" =>"CAJ_DESCUENTOS_NOTAS_CREDITO",
															"total_notas" =>"CAJ_TOTAL_NOTAS_CREDITO",
															"monto_inicial" =>"CAJ_MONTO_INICIAL"
															))
					->joinLeft(array("u"=>"USUARIO"),"u.USU_ID = c.USU_ID",array("nombre"=>"USU_NOMBRE"));		
					
                    if($where){
						$sql->where($where);
                    }                
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;
            $obj->fecha_inicio = date('d/m/Y H:i',strtotime($rs->fecha_inicio));
			$obj->fecha_termino = ($rs->fecha_termino < $rs->fecha_inicio)?'-':date('d/m/Y H:i',strtotime($rs->fecha_termino));

			$dteStart = new DateTime($rs->fecha_inicio);
			
			
			if($rs->fecha_termino < $rs->fecha_inicio){
				$dteEnd   = new DateTime(); 	
			}else{
				$dteEnd   = new DateTime($rs->fecha_termino);
			}
			
			$dteDiff  = $dteStart->diff($dteEnd);
			
			$obj->tiempo_efectivo = $dteDiff->format("%H hrs. %I min.");
			
			#Global
			$obj->subtotal = '$'.formatearValor($rs->subtotal);
			$obj->subtotal_int = $rs->subtotal;
            $obj->descuentos = '$'.formatearValor($rs->descuentos);
            $obj->desc_int = $rs->descuentos;
            $obj->total = '$'.formatearValor($rs->total);
            $obj->total_int = $rs->total;
			
			#Efectivo
			$obj->subtotal_efectivo = '$'.formatearValor($rs->subtotal_efectivo);
			$obj->subtotal_efectivo_int = $rs->subtotal_efectivo;
            $obj->descuentos_efectivo = '$'.formatearValor($rs->descuentos_efectivo);
            $obj->descuentos_efectivo_int = $rs->descuentos_efectivo;
            $obj->total_efectivo = '$'.formatearValor($rs->total_efectivo);	
            $obj->total_efectivo_int = $rs->total_efectivo;	

			#Debito
			$obj->subtotal_debito = '$'.formatearValor($rs->subtotal_debito);
            $obj->descuentos_debito = '$'.formatearValor($rs->descuentos_debito);
            $obj->total_debito = '$'.formatearValor($rs->total_debito);	
			
			#Credito
			$obj->subtotal_credito = '$'.formatearValor($rs->subtotal_credito);
            $obj->descuentos_credito = '$'.formatearValor($rs->descuentos_credito);
            $obj->total_credito = '$'.formatearValor($rs->total_credito);				
			
			#Nota Credito
            $obj->subtotal_notas = '$'.formatearValor($rs->subtotal_notas);
            $obj->total_descuentos_notas = '$'.formatearValor($rs->total_descuentos_notas);
            $obj->total_notas = '$'.formatearValor($rs->total_notas);

			#Monto Inicial Apertura
            $obj->monto_inicial = '$'.formatearValor($rs->monto_inicial);				
			
			if($rs->estado == 1){
				$obj->estado = '<label class="label label-danger">Abierto</label>';
			}else{
				$obj->estado = '<label class="label label-success">Cerrado</label>';
			}
			
			$obj->estado_num = $rs->estado;
			
            $obj->usuario = $rs->nombre;
            
            
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
                $caja->fecha_apertura = date('d/m/Y H:i',strtotime($rs->fecha_apertura));
                $caja->fecha_cierre = $rs->fecha_cierre;
                $caja->estado = $rs->estado;
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
            return 'si';
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    }
}
