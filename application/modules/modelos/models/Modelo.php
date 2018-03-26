<?php

class Modelos_Model_Modelo extends Zend_Db_Table_Abstract
{
    protected $_name = "MODELO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("m"=>"MODELO"),array("id"=>"MOD_ID",
														  "nombre"=>"MOD_NOMBRE",
														  "estado"=>"MOD_ESTADO"
														  ))
					->joinInner(array("ma"=>"MARCA"),"m.MAR_ID = ma.MAR_ID",array("marca" => "ma.MAR_NOMBRE"))
                    ->order(array("ma.MAR_NOMBRE ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $modelo = new stdClass();
			$modelo->id = $rs->id;
			$modelo->nombre = $rs->nombre;
			$modelo->nombre_2 = $rs->marca.' '.$rs->nombre;
			$modelo->estado = $rs->estado;
			$modelo->marca = $rs->marca;
            $lista[] = $modelo;
        }
        return $lista;
    }

    public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("m"=>"MODELO"),array("id"=>"MOD_ID",
													  "nombre"=>"MOD_NOMBRE",
													  "estado"=>"MOD_ESTADO"
														  ))
					->joinInner(array("ma"=>"MARCA"),"m.MAR_ID = ma.MAR_ID",array("id_marca" => "ma.MAR_id",
																				  "marca" => "ma.MAR_NOMBRE"))
					->where("m.MOD_ID = '$id'");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
                $obj->id = $rs->id;
                $obj->nombre = $rs->nombre;
                $obj->marca = $rs->marca;
                $obj->id_marca = $rs->id_marca;
                $obj->estado = $rs->estado;
                
			}else{
				$obj->id = false;
				$obj->nombre = 'Modelo no existe';
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
            return 1;
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