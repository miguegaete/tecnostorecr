<?php

class Movimientos_Model_Comprar extends Zend_Db_Table_Abstract
{
    protected $_name = "COMPRAR";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false)
    {

        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"COMPRAR"),array("id" => "COM_ID",
                                                        "fecha" => "COM_FECHA",
                                                        "subtotal" => "COM_SUBTOTAL",
                                                        "iva" => "COM_IVA",
                                                        "total" => "COM_TOTAL",
                                                        "num_documento" => "COM_NUMERODOCUMENTO"
														 ))
					->joinLeft(array("f"=>"FORMA_PAGO"),"c.FOR_ID = f.FOR_ID",array("forma_pago"=>"FOR_NOMBRE"))						
					->joinLeft(array("d"=>"DOCUMENTO"),"c.DOC_ID = d.DOC_ID",array("documento"=>"DOC_NOMBRE"))						
					->joinLeft(array("u"=>"USUARIO"),"c.USU_ID = u.USU_ID",array("usuario"=>"USU_LOGIN"))						
					->joinLeft(array("p"=>"PROVEEDOR"),"c.PROV_ID = p.PROV_ID",array("proveedor"=>"PROV_NOMBRE",
																					"rut_proveedor"=>"PROV_RUT"))						
                    ->order(array("c.COM_ID DESC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $obj = new stdClass();
            $obj->id = $rs->id;
            $obj->fecha = date('d/m/Y',strtotime($rs->fecha));
            $obj->subtotal = '$'.formatearValor($rs->subtotal);
            $obj->iva = '$'.formatearValor($rs->iva);
            $obj->total = '$'.formatearValor($rs->total);
            $obj->num_documento = $rs->num_documento;
            $obj->forma_pago = $rs->forma_pago;
            $obj->documento = $rs->documento;
            $obj->usuario = $rs->usuario;
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

