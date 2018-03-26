<?php

class Ventas_Model_Pedidoproducto extends Zend_Db_Table_Abstract
{
    protected $_name = "PEDIDO_PRODUCTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;

    public function listar($pedido)
    {
        $where = "pd.PED_ID = ".$pedido;
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("pd"=>"PEDIDO_PRODUCTO"),array("id" => "PEDPRO_ID",
                                                                "idpro"=>"PRO_ID",
                                                                "cantidad"=>"PEDPRO_CANTIDAD",
                                                                "valor"=>"PEDPRO_VALOR",
                                                                "descripcion"=>"PEDPRO_DESCRIPCION",
                                                                "impreso"=>"PEDPRO_IMPRESO"))
                    ->join(array("p"=>"PRODUCTO"),"pd.PRO_ID = p.PRO_ID", array(
                                                                                "nombre"=>"PRO_NOMBRE",
                                                                                "precio"=>"PRO_VALOR",
																				"estado_oferta"=>"PRO_ESTADO_VALOR_OFERTA",
																				"precio_oferta"=>"PRO_VALOR_OFERTA",
																				"stock_actual" => "PRO_STOCK",
																				"manejostock" => "PRO_MANEJOSTOCK"))
					->joinLeft(array("c"=>"CATEGORIA"),"p.CAT_ID = c.CAT_ID",array("categoria"=>"CAT_NOMBRE"))																	
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
            $pedido->stock = $rs->stock_actual;
			
			if($rs->estado_oferta==1){
				$pedido->precio = $rs->precio_oferta;
			}else{
				$pedido->precio = $rs->precio;
            }
			$pedido->tipo = 'producto';
			$pedido->categoria = $rs->categoria;
			$pedido->manejostock = $rs->manejostock;
            
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

