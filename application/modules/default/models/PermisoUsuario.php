<?php

class Default_Model_PermisoUsuario extends Zend_Db_Table_Abstract
{
    protected $_name = "PERMISO_USUARIO";

    public function  __construct()
    {
    	parent::__construct($config = array());        
    }

    public function listar($limit = false,$idpermiso = false)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)				
                    ->from(array("p"=>"PERMISO"),array( "id" => "PER_ID",
                                                        "nombre" => "PER_NOMBRE",
                                                        "nombre_validar" => "PER_NOMBRE_VALIDAR",
														"estado" => "PER_ESTADO"))
					->joinLeft(array("pu"=>"PERMISO_USUARIO"),"p.PER_ID = pu.PER_ID",array("cod" => "PUS_ID"))
                    ->joinLeft(array("u"=>"USUARIO"),"pu.USU_ID = u.USU_ID",array("usuario" => "USU_NOMBRE"));
					
					if($usuario_id){
						$sql->where("p.PER_ID = $idpermiso");
					}
					
					$sql->order(array("p.PER_NOMBRE ASC","p.PER_ID ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $usuario = new stdClass();
            $usuario->id = $rs->id;
            $usuario->nombre = $rs->nombre;
            $usuario->nombre_validar = $rs->nombre_validar;
            $usuario->estado = $rs->estado;
            $usuario->usuario = usuario;
			
            $lista[] = $usuario;
        }
        return $lista;
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

    public function obtener($usu_id = false,$per_id = false)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
					->from(array("pu"=>"PERMISO_USUARIO"),array( "id" => "PER_ID"));
					
					if($usu_id)
						$sql->where("pu.USU_ID = $usu_id");
					
					if($per_id)
						$sql->where("pu.PER_ID = $per_id");
					
        $result = $this->fetchRow($sql);
        
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }     
    
}