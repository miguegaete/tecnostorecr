<?php

class Clientes_Model_Cliente extends Zend_Db_Table_Abstract
{
    protected $_name = "CLIENTE";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

  public function listar($limit = false)
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
                    ->order(array("c.CLI_NOMBRE ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $cliente = new stdClass();
			$cliente->rut = $rs->rut;
			$cliente->nombre = $rs->nombre;
			$cliente->telefono = $rs->telefono;
			$cliente->email = $rs->email;
			$cliente->direccion = $rs->direccion;
			$cliente->estado = $rs->estado;
            $lista[] = $cliente;
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