<?php

class Movimientos_Model_ComprarProducto extends Zend_Db_Table_Abstract
{
    protected $_name = "COMPRAR_PRODUCTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($id_compra)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("cp"=>"COMPRAR_PRODUCTO"),array("id" => "COMPRO_ID",
																"costo" => "COMPRO_COSTO",
																"total" => "COMPRO_TOTAL",
																"cantidad" => "COMPRO_CANTIDAD"
																 ))
					->joinLeft(array("c"=>"COMPRAR"),"cp.COM_ID = c.COM_ID",array("subtotal"=>"COM_SUBTOTAL",
																				  "iva"=> "COM_IVA",
																				  "total_com"=> "COM_TOTAL"
																				  ))
				  ->joinLeft(array("p"=>"PRODUCTO"),"cp.PRO_ID = p.PRO_ID",array("producto"=>"PRO_NOMBRE"))						
				  ->joinLeft(array("pr"=>"PROVEEDOR"),"c.PROV_ID = pr.PROV_ID",array(
																					"proveedor"=>"PROV_NOMBRE",
																					"rut_proveedor"=>"PROV_RUT",
																					))						
                    ->order(array("cp.COMPRO_ID DESC"));    
					
        
        if($id_compra)
            $sql->where("cp.COM_ID = ".$id_compra);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;
            $obj->costo = '$'.formatearValor($rs->costo);
            $obj->total = '$'.formatearValor($rs->total);
            $obj->cantidad = $rs->cantidad;
            $obj->subtotal = '$'.formatearValor($rs->subtotal);
            $obj->iva = '$'.formatearValor($rs->iva);
            $obj->total_com = '$'.formatearValor($rs->total_com);
            $obj->producto = $rs->producto;
            $obj->proveedor = $rs->proveedor;
            $obj->rut_proveedor = $rs->rut_proveedor;
            $lista[] = $obj;
        }
        return $lista;
    }
	
	public function listar_saldos()
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("cp"=>"COMPRAR_PRODUCTO"),array("id" => "COMPRO_ID",
																"costo" => "COMPRO_COSTO",
																"total" => "COMPRO_TOTAL",
																"cantidad" => "COMPRO_CANTIDAD"
																 ))
					->joinLeft(array("c"=>"COMPRAR"),"cp.COM_ID = c.COM_ID",array("subtotal"=>"COM_SUBTOTAL",
																				  "iva"=> "COM_IVA",
																				  "total_com"=> "COM_TOTAL"
																				  ))
				  ->joinLeft(array("p"=>"PRODUCTO"),"cp.PRO_ID = p.PRO_ID",array("producto"=>"PRO_NOMBRE"))						
				  ->joinLeft(array("pr"=>"PROVEEDOR"),"c.PROV_ID = pr.PROV_ID",array(
																					"proveedor"=>"PROV_NOMBRE",
																					"rut_proveedor"=>"PROV_RUT",
																					))						
                    ->order(array("cp.COMPRO_ID DESC"));    
					
        
        if($id_compra)
            $sql->where("cp.COM_ID = ".$id_compra);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;
            $obj->costo = '$'.formatearValor($rs->costo);
            $obj->total = '$'.formatearValor($rs->total);
            $obj->cantidad = $rs->cantidad;
            $obj->subtotal = '$'.formatearValor($rs->subtotal);
            $obj->iva = '$'.formatearValor($rs->iva);
            $obj->total_com = '$'.formatearValor($rs->total_com);
            $obj->producto = $rs->producto;
            $obj->proveedor = $rs->proveedor;
            $obj->rut_proveedor = $rs->rut_proveedor;
            $lista[] = $obj;
        }
        return $lista;
    }	

    public function guardar($datos){

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

