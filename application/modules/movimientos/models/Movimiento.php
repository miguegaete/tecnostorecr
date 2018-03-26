<?php

class Movimientos_Model_Movimiento extends Zend_Db_Table_Abstract
{
    protected $_name = "MOVIMIENTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("m"=>"MOVIMIENTO"),array("id" => "MOV_ID",
                                                        "fecha" => "MOV_FECHA",
                                                        "cantidad" => "MOV_CANTIDAD",
                                                        "nombre_producto" => "MOV_NOMBRE_PRODUCTO",
                                                        "codigo_producto" => "PRO_ID",
                                                        "tipo" => "MOV_TIPO",
														"stock_insertar" => "MOV_STOCK_AL_INSERTAR"
														 ))
					->joinLeft(array("p"=>"PRODUCTO"),"p.PRO_ID = m.PRO_ID",array("sku"=>"PRO_SKU",
																				  "stock"=> "PRO_STOCK"))						
                    ->order(array("m.MOV_ID DESC"));    
					
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $movimiento = new stdClass();
            $movimiento->id = $rs->id;
            $movimiento->fecha = date('d/m/Y',strtotime($rs->fecha));
            $movimiento->cantidad = $rs->cantidad;
            $movimiento->nombre_producto = $rs->nombre_producto;
            $movimiento->codigo_producto = $rs->codigo_producto;
            $movimiento->tipo = $rs->tipo;
            $movimiento->sku = $rs->sku;
            $movimiento->stock = $rs->stock;
            $movimiento->stock_insertar = $rs->stock_insertar;
            $lista[] = $movimiento;
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

