<?php

class TiposReparaciones_Model_TipoReparacion extends Zend_Db_Table_Abstract
{
    protected $_name = "TIPO_REPARACION";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("t"=>"TIPO_REPARACION"),array("id"=>"TIP_ID",
														  "nombre"=>"TIP_NOMBRE",
														  "estado"=>"TIP_ESTADO"
														  ))
					
                    ->order(array("t.TIP_NOMBRE ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $modelo = new stdClass();
			$modelo->id = $rs->id;
			$modelo->nombre = $rs->nombre;
			$modelo->estado = $rs->estado;
            $lista[] = $modelo;
        }
        return $lista;
    }

    public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("t"=>"TIPO_REPARACION"),array("id"=>"TIP_ID",
													  "nombre"=>"TIP_NOMBRE",
													  "estado"=>"TIP_ESTADO"
														  ))
					->where("t.TIP_ID = '$id'");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
                $obj->id = $rs->id;
                $obj->nombre = $rs->nombre;
                $obj->estado = $rs->estado;
                
			}else{
				$obj->id = false;
				$obj->nombre = 'Tipo de Reparación no existe';
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