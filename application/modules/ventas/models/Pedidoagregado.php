<?php

class Ventas_Model_Pedidoagregado extends Zend_Db_Table_Abstract
{
    protected $_name = "PEDIDO_AGREGADO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($pedido)
    {
        $where = "pd.PED_ID = ".$pedido;
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("pd"=>"PEDIDO_AGREGADO"),array("id" => "PEDAGR_ID",
                                                                "idpro"=>"AGR_ID",
                                                                "cantidad"=>"PEDAGR_CANTIDAD",
                                                                "valor"=>"PEDAGR_VALOR",
                                                                "descripcion"=>"PEDAGR_DESCRIPCION",
                                                                "impreso"=>"PEDAGR_IMPRESO"))
                    ->join(array("a"=>"AGREGADOS"),"pd.AGR_ID = a.AGR_ID", array(
                                                                                "nombre"=>"AGR_NOMBRE",
                                                                                "precio"=>"AGR_VALOR"))
                    ->where($where);
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $pedido = new stdClass();
            $pedido->id = $rs->id;
            $pedido->idpro = $rs->idpro;
            $pedido->cantidad = $rs->cantidad;
            $pedido->valor = $rs->valor;
            $pedido->descripcion = $rs->descripcion;
            $pedido->impreso = $rs->impreso;
            $pedido->nombre = $rs->nombre;
            $pedido->precio = $rs->precio;
            $pedido->tipo = 'agregado';
            $pedido->categoria = 'Agr';
            
            $lista[] = $pedido;
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

