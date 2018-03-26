<?php

class Productos_Model_PedidoProducto extends Zend_Db_Table_Abstract
{
    protected $_name = "PEDIDO_PRODUCTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar_productos($limit = false , $where = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("pp"=>"PEDIDO_PRODUCTO"),array("cantidad"=>"PEDPRO_CANTIDAD",
																"valor" => "PEDPRO_VALOR",
																"descuento" => "PEDPRO_DESCUENTO" 
																))
					->joinLeft(array("p"=>"PRODUCTO"),"pp.PRO_ID = p.PRO_ID",array(	"id_producto" => "PRO_ID",
																					"producto"=>"PRO_NOMBRE",
																					"sku" => "PRO_SKU",
																					"descripcion" => "PRO_DESCRIPCION",
																					"valor_producto" => "PRO_VALOR",
																					"estado_oferta" => "PRO_ESTADO_VALOR_OFERTA",
																					"valor_oferta" => "PRO_VALOR_OFERTA",
																					))
					->joinLeft(array("c"=>"CATEGORIA"),"p.CAT_ID = c.CAT_ID",array(	"categoria" => "CAT_NOMBRE"));
					
                    //->order(array("c.CAT_NOMBRE ASC","c.CAT_ID ASC"));    
        if($where)
			$sql->where($where);
		
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $venta = new stdClass();
            $venta->id_producto = $rs->id_producto;
            $venta->producto = $rs->producto;
            $venta->descripcion = $rs->descripcion;
            $venta->cantidad = $rs->cantidad;
            $venta->valor = $rs->valor;
            $venta->categoria = $rs->categoria;
            $venta->descuento = $rs->descuento;
            $venta->sku = $rs->sku;
			if($rs->estado_oferta==1){
				$venta->valor_producto = $rs->valor_oferta;
			}else{
				$venta->valor_producto = $rs->valor_producto;
			}
            $lista[] = $venta;
        }
        return $lista;
    }

}

