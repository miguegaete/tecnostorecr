<?php

class Ventas_Model_Documento extends Zend_Db_Table_Abstract
{
    protected $_name = "DOCUMENTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($where = false)
    {
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"DOCUMENTO"),array(		"id" => "DOC_ID",
															"nombre"=>"DOC_NOMBRE",
													   		"descripcion" =>"DOC_DESCRIPCION"
															));	
                    if($where){
						$sql->where($where);
                    }                
					
        
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;			
			$obj->nombre = $rs->nombre;
            $obj->descripcion = $rs->descripcion;
            
            
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

