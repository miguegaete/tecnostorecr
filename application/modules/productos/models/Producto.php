<?php

class Productos_Model_Producto extends Zend_Db_Table_Abstract
{
    protected $_name = "PRODUCTO";
	protected $_primary = "PRO_ID";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false, $venta = true,$manejostock = false)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PRODUCTO"),array("id" => "PRO_ID",
                                                        "sku" => "PRO_SKU",
                                                        "id_categoria" => "CAT_ID",
                                                         "nombre"=>"PRO_NOMBRE",
                                                         "descripcion"=>"PRO_DESCRIPCION",
                                                         "valor"=>"PRO_VALOR",
                                                         "activar_vr"=>"PRO_ESTADO_VALOR_OFERTA",
                                                         "valor_rebaja"=>"PRO_VALOR_OFERTA",
                                                         "stock"=>"PRO_STOCK",
                                                         "imagen"=>"PRO_IMAGEN",
														 "icono"=>"PRO_ICONO",
														 "estado"=>"PRO_ESTADO"
														 ))
                    ->joinLeft(array("c"=>"CATEGORIA"),"p.CAT_ID = c.CAT_ID",array("categoria"=>"CAT_NOMBRE"))   
                    ->order(array("p.PRO_NOMBRE ASC","p.PRO_ID ASC"));    
					
					if($venta){
						$sql->where("PRO_ESTADO <> 0");
					}
					
					if($manejostock){
						$sql->where("PRO_MANEJOSTOCK = 1");
					}
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $producto = new stdClass();
            $producto->id = $rs->id;
            $producto->sku = $rs->sku;
            $producto->categoria = $rs->categoria;
            $producto->id_categoria = $rs->id_categoria;
            $producto->nombre = $rs->nombre;
            $producto->descripcion = $rs->descripcion;
            $producto->valor = $rs->valor;
            $producto->activar_vr = $rs->activar_vr;
            $producto->valor_rebaja = $rs->valor_rebaja;
            $producto->stock = $rs->stock;
            $producto->estado = $rs->estado;
            $producto->imagen = (!empty($rs->imagen)) ? $rs->imagen:"/imagenes/sitio/default_gallery_2.jpg";
            $producto->icono = (!empty($rs->icono)) ? $rs->icono:"/imagenes/sitio/default_gallery_3.jpg";
            $lista[] = $producto;
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
            $obj->id = (int)$result->PRO_ID;
            $obj->sku = (int)$result->PRO_SKU;
            $obj->id_categoria = (int)$result->CAT_ID;
            $obj->nombre = $result->PRO_NOMBRE;
            $obj->descripcion = $result->PRO_DESCRIPCION;
            $obj->valor = $result->PRO_VALOR;
            $obj->costo = $result->PRO_COSTO;
            $obj->estado = $result->PRO_ESTADO;
            $obj->stock = $result->PRO_STOCK;
            $obj->valor_rebaja = $result->PRO_VALOR_OFERTA;
            $obj->activar_vr = $result->PRO_ESTADO_VALOR_OFERTA;	
			$obj->imagen = (!empty($result->PRO_IMAGEN)) ? $result->PRO_IMAGEN:"/imagenes/sitio/default_gallery_2.jpg";
			$obj->sin_sku = $result->PRO_SIN_SKU;
			$obj->manejostock = $result->PRO_MANEJOSTOCK;
			$obj->idReparacion = $result->REP_ID;
        }
        else
        {
            $obj->id = false;
            $obj->nombre = 'Producto no existe';
        }
        
    unset($sql,$result);
        return $obj;
    }  

	public function obtenerCodigoProducto()
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PRODUCTO"),array("pro_sku" => "PRO_SKU"))
					->where("PRO_SIN_SKU = 1")
                    ->order("PRO_ID DESC");

        $result = $this->fetchRow($sql);
		
        $obj = new stdClass();
		
        
        if($result)
        {
            $obj->pro_sku = $result->pro_sku + 1;		
        }
        else
        {
			$conf = new Configuraciones_Model_Configurar();
			$rs = $conf->obtener(2);
            $obj->pro_sku = $rs[0]->sin_sku_inicio;
        }
        
		unset($sql,$result);
        return $obj;
    } 
	
	public function validaExisteProductoServicio($idReparacion)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PRODUCTO"),array("pro_sku" => "PRO_SKU"))
					->where("REP_ID = $idReparacion");

        $result = $this->fetchRow($sql);
		
        $obj = new stdClass();
		
        
        if($result)
        {
			unset($sql,$result);
            return true;		
        }
        else
        {
			unset($sql,$result);
			return false;
			
        }
        

    } 	
	
	
	
 public function listarSaldos($mes, $year)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("p"=>"PRODUCTO"),array("id" => "PRO_ID",
                                                         "nombre"=>"PRO_NOMBRE",
														 "sku" => "PRO_SKU",
														 
														 //"salida"=> "fn_SalidaProductoMensual($mes,$year,PRO_ID)",
														 //"entrada"=> "fn_EntradaProductoMensual($mes,$year,PRO_ID)",
														 "stock"=> "PRO_STOCK"
														 ))
                    ->joinLeft(array("s"=>"SALDO"),"p.PRO_ID = s.PROD_ID AND (YEAR(s.SAL_FECHA) = $year AND MONTH(s.SAL_FECHA) = $mes) ",array("saldo_inicial"=>"CASE WHEN (SAL_STOCK_INICIAL) IS NULL THEN 0 ELSE (SAL_STOCK_INICIAL) END"))   
                    ->where("p.PRO_ESTADO = 1")
					->group("p.PRO_ID");
        $resultado = $this->fetchAll($sql);
        $lista = array();
        $cont = 0;
        foreach($resultado as $rs)
        {
			$cont++;
            $producto = new stdClass();
            $producto->id = $rs->id;
            $producto->nombre = $rs->nombre;
            $producto->salida = 0;//$rs->salida;
            $producto->entrada = 0;//$rs->entrada;
            $producto->stock = $rs->stock;
            $producto->saldo_inicial = $rs->saldo_inicial;
            $producto->num = $cont;
            $producto->sku = $rs->sku;

            $lista[] = $producto;
        }
        return $lista;
    }
	




}

