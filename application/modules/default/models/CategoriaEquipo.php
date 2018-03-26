<?php

class Default_Model_CategoriaEquipo extends Zend_Db_Table_Abstract
{
    protected $_name = "CATEGORIA_EQUIPO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("c"=>"CATEGORIA_EQUIPO"),array("id"=>"CEQ_ID",
																"nombre"=>"CEQ_NOMBRE"
														  ))
                    ->order(array("c.CEQ_NOMBRE ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $categoria = new stdClass();
			$categoria->id = $rs->id;
			$categoria->nombre = $rs->nombre;
            $lista[] = $categoria;
        }
        return $lista;
    }

    public function obtener($rut)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("c"=>"CLIENTE"),array("rut"=>"CLI_RUT",
													  "nombre"=>"CLI_NOMBRE",
													  "telefono"=>"CLI_TELEFONO",
													  "email"=>"CLI_EMAIL",
													  "direccion"=>"CLI_DIRECCION",
													  "estado"=>"CLI_ESTADO"
														  ))
					->where("c.CLI_RUT = '$rut'");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
                $obj->rut = $rs->rut;
                $obj->nombre = $rs->nombre;
                $obj->telefono = $rs->telefono;
                $obj->email = $rs->email;
                $obj->direccion = $rs->direccion;
                $obj->estado = $rs->estado;
                
			}else{
				$obj->rut = false;
				$obj->nombre = 'Cliente no existe';
			}
        
			unset($sql,$rs);
			return $obj;
        
    }
    public function guardar($datos){

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
            return $id;
        }catch(Zend_Exception $e){
            return $e->getMessage();

        }
    }

}