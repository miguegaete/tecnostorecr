<?php

class Reparaciones_Model_Reparacion extends Zend_Db_Table_Abstract
{
    protected $_name = "REPARACION";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false,$orientacion = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("r"=>"REPARACION"),array("id"=>"r.REP_ID",
														  "costo"=>"r.REP_COSTO",
														  "valor"=>"r.REP_VALOR",
														  "estado"=>"r.REP_ESTADO",
														  "fecha"=>"r.REP_FECHA",
														  "vender"=> "r.REP_VENDER"
														  

														  ))
					->joinInner(array("m"=>"MODELO"),"m.MOD_ID = r.MOD_ID",array("modelo" => "m.MOD_NOMBRE"))
					->joinInner(array("ma"=>"MARCA"),"m.MAR_ID = ma.MAR_ID",array("marca" => "ma.MAR_NOMBRE"))
					->joinInner(array("t"=>"TIPO_REPARACION"),"t.TIP_ID = r.TIP_ID",array("tipo" => "t.TIP_NOMBRE"));
					if($orientacion){
						$sql->order(array("r.REP_ID $orientacion"));  
					}else{
						$sql->order(array("r.REP_ID ASC"));  
					}
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
			$obj->id = $rs->id;
			$obj->costo = $rs->costo;
			$obj->valor = $rs->valor;
			$obj->modelo = $rs->marca.' '.$rs->modelo;
			$obj->tipo = $rs->tipo;
			$obj->estado = $rs->estado;
            $obj->fecha = date('d/m/Y',strtotime($rs->fecha));
            $obj->hora = date('H:i',strtotime($rs->fecha));
            $obj->vender =$rs->vender;
            $lista[] = $obj;
        }
        return $lista;
    }

    public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("r"=>"REPARACION"),array("id"=>"r.REP_ID",
														  "costo"=>"r.REP_COSTO",
														  "valor"=>"r.REP_VALOR",
														  "estado"=>"r.REP_ESTADO"

														  ))
					->joinInner(array("m"=>"MODELO"),"m.MOD_ID = r.MOD_ID",array("id_modelo" => "m.MOD_ID",
																				 "modelo" => "m.MOD_NOMBRE"))
					->joinInner(array("ma"=>"MARCA"),"m.MAR_ID = ma.MAR_ID",array("marca" => "ma.MAR_NOMBRE"))																	
					->joinInner(array("t"=>"TIPO_REPARACION"),"t.TIP_ID = r.TIP_ID",array("id_tipo" => "t.TIP_ID",
																						  "tipo" => "t.TIP_NOMBRE"))
					->where("r.REP_ID = '$id'");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
				$obj->id = $rs->id;
				$obj->costo = $rs->costo;
				$obj->valor = $rs->valor;
				$obj->id_modelo = $rs->id_modelo;
				$obj->modelo = $rs->modelo;
				$obj->id_tipo = $rs->id_tipo;
				$obj->tipo = $rs->tipo;
				$obj->estado = $rs->estado;
				$obj->nombre = $rs->tipo.' '.$rs->marca.' '.$rs->modelo;
                
			}else{
				$obj->id = false;
				$obj->estado = 0;
			}
        
			unset($sql,$rs);
			return $obj;
        
    }
    public function guardar($datos){

        try {
            $id = $this->insert($datos);
            return 1;
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