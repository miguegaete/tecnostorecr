<?php

class Agregados_Model_Agregado extends Zend_Db_Table_Abstract
{
    protected $_name = "AGREGADOS";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($limit = false)
    {
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("a"=>"AGREGADOS"),array("id" => "AGR_ID",
                                                         "nombre"=>"AGR_NOMBRE",
                    									 "descripcion"=>"AGR_DESCRIPCION",
                    									 "valor"=>"AGR_VALOR",
                    									 "imagen"=>"AGR_IMAGEN"))
                    ->order(array("a.AGR_NOMBRE ASC","a.AGR_ID ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
	

    	$resultado = $this->fetchAll($sql);
        $lista = array();
        
    	foreach($resultado as $rs)
        {
            $agregado = new stdClass();
            $agregado->id = $rs->id;
            $agregado->nombre = $rs->nombre;
            $agregado->descripcion = $rs->descripcion;
            $agregado->valor = $rs->valor;
            $agregado->imagen = (!empty($rs->imagen)) ? $rs->imagen:"/imagenes/sitio/default_gallery_2.jpg";
            
            $lista[] = $agregado;
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
            $obj->id = (int)$result->AGR_ID;
            $obj->nombre = $result->AGR_NOMBRE;
            $obj->descripcion = $result->AGR_DESCRIPCION;
            $obj->valor = $result->AGR_VALOR;
        }
        else
        {
            $obj->id = false;
            $obj->nombre = $this->translate->_('Agregado no existe');
        }
        
    unset($sql,$result);
        return $obj;
    }        


}

