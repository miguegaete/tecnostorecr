<?php

class Default_Model_Permiso extends Zend_Db_Table_Abstract
{
    protected $_name = "PERMISO";

    public function  __construct()
    {
    	parent::__construct($config = array());        
    } 


    public function listar($limit = false,$usu_id = false)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PERMISO"),array( "id" => "PER_ID",
                                                        "nombre" => "PER_NOMBRE",
                                                        "nombre_validar" => "PER_NOMBRE_VALIDAR",
														"estado" => "PER_ESTADO"))
                    ->order(array("PER_NOMBRE ASC","PER_ID ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
		$permisousu = new Default_Model_PermisoUsuario();
		
        foreach($resultado as $rs)
        {
            $usuario = new stdClass();
            $usuario->id = $rs->id;
            $usuario->nombre = $rs->nombre;
            $usuario->nombre_validar = $rs->nombre_validar;
            $usuario->estado = $rs->estado;
			$usuario->activo = $permisousu->obtener($usu_id,$rs->id);
            
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

    public function obtener($where)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this, '*')
                    ->where($where);
        $result = $this->fetchRow($sql);
        $obj = new stdClass();
        
        if($result)
        {
            $obj->id = (int)$result->USU_ID;
            $obj->usuario = $result->USU_LOGIN;
            $obj->nombre = $result->USU_NOMBRE;
            $obj->tipo = $result->USU_TIPO;
            $obj->email = $result->USU_EMAIL;
            $obj->estado = $result->USU_ESTADO;
            
        }
        else
        {
            $obj->id = false;
            $obj->nombre = $this->translate->_('Usuario no existe');
        }
        
    unset($sql,$result);
        return $obj;
    }     
    
}