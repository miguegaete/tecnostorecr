<?php

class Ventas_Model_Formas extends Zend_Db_Table_Abstract
{
    protected $_name = "FORMA_PAGO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($limit = false)
    {
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("fp"=>"FORMA_PAGO"),array("id" => "FOR_ID",
                                                           "nombre"=>"FOR_NOMBRE",
                                                           "estado"=>"FOR_ESTADO"))
                    ->where("fp.FOR_ESTADO = 1");
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $formaspago = new stdClass();
            $formaspago->id = $rs->id;
            $formaspago->nombre = $rs->nombre;
            $formaspago->estado = $rs->estado;
            
            $lista[] = $formaspago;
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
}

