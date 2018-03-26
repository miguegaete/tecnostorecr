<?php

class Movimientos_Model_Proveedor extends Zend_Db_Table_Abstract
{
    protected $_name = "PROVEEDOR";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PROVEEDOR"),array("id" => "PROV_ID",
                                                        "nombre" => "PROV_NOMBRE",
                                                        "rut" => "PROV_RUT",
                                                        "email" => "PROV_EMAIL"
														 ))						
                    ->order(array("p.PROV_ID DESC"));    
					
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;
            $obj->nombre = $rs->nombre;
            $obj->rut = $rs->rut;
            $obj->email = $rs->email;
            $lista[] = $obj;
        }
        return $lista;
    }

	public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("p"=>"PROVEEDOR"),array("rut"=>"PROV_RUT",
													  "id"=>"PROV_ID",
													  "nombre"=>"PROV_NOMBRE",
													  "email"=>"PROV_EMAIL"
														  ))
					->where("p.PROV_ID = $id");
            
            $rs = $this->fetchRow($sql);
            $obj = new stdClass();
            
            if($rs)
            {
                
                $obj->id = $rs->id;
                $obj->rut = $rs->rut;
                $obj->nombre = $rs->nombre;
                $obj->email = $rs->email;
                
			}else{
				$obj->rut = false;
				$obj->nombre = 'Proveedor no existe';
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

