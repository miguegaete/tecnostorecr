<?php

class Productos_Model_Categoria extends Zend_Db_Table_Abstract
{
    protected $_name = "CATEGORIA";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false, $activo = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"CATEGORIA"),array("id" => "CAT_ID",
                                                         "nombre"=>"CAT_NOMBRE",
														 "nombre_corto" => "CAT_NOMBRE_CORTO",
														 "estado" => "CAT_ESTADO"))
                    ->order(array("c.CAT_NOMBRE ASC","c.CAT_ID ASC"));   
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
		
		if($activo)
			$sql->where("CAT_ESTADO = 1");
		
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $categoria = new stdClass();
            $categoria->id = $rs->id;
            $categoria->nombre = $rs->nombre;
			$categoria->nombre_corto =  $rs->nombre_corto;
			$categoria->estado = $rs->estado;
			
            $lista[] = $categoria;
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
            $obj->id = (int)$result->CAT_ID;
            $obj->nombre = $result->CAT_NOMBRE;
			$obj->nombre_corto = $result->CAT_NOMBRE_CORTO;
			$obj->estado = $result->CAT_ESTADO;
        }
        else
        {
            $obj->id = false;
            $obj->nombre = $this->translate->_('Categoría no existe');
        }
        
    unset($sql,$result);
        return $obj;
    }        




}

