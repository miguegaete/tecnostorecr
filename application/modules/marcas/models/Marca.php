<?php

class Marcas_Model_Marca extends Zend_Db_Table_Abstract
{
    protected $_name = "MARCA";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("m"=>"MARCA"),array("id"=>"MAR_ID",
														  "nombre"=>"MAR_NOMBRE",
														  "estado"=>"MAR_ESTADO"
														  ))
                    ->order(array("m.MAR_NOMBRE ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
			$obj->id = $rs->id;
			$obj->nombre = $rs->nombre;
			$obj->estado = $rs->estado;
            $lista[] = $obj;
        }
        return $lista;
    }

    public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("m"=>"MARCA"),array("id"=>"MAR_ID",
													  "nombre"=>"MAR_NOMBRE",
													  "estado"=>"MAR_ESTADO"
														  ))
					->where("m.MAR_ID = '$id'");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
                $obj->id = $rs->id;
                $obj->nombre = $rs->nombre;
                $obj->estado = $rs->estado;
                
			}else{
				$obj->id = false;
				$obj->nombre = 'Marca no existe';
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