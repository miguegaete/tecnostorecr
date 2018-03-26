<?php

class Configuraciones_Model_Configurar extends Zend_Db_Table_Abstract
{
    protected $_name = "CONFIGURAR";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;



    public function obtener($id)
    {
		$sql = $this->select()
					->setIntegrityCheck(false)
					->from(array("c"=>"CONFIGURAR"),array("nombre"=>"CON_NOMBRE",
														  "direccion"=>"CON_DIRECCION",
														  "email"=>"CON_EMAIL",
														  "fono"=>"CON_TELEFONO",
														  "rut"=>"CON_RUT",
														  "tipo"=>"CON_TIPO",
														  "imagen"=>"CON_IMAGEN",
														  "tbk_credito"=>"CON_TBK_CREDITO",
														  "tbk_debito"=>"CON_TBK_DEBITO",
														  "iva"=>"CON_IVA",
														  "id"=> "CON_ID",
														  "sin_sku_inicio" => "CON_SKU",
														  "imprimir_boleta" => "CON_IMPRIMIR"
														  
														  ))
					->where("CON_ID = ".$id);
            
            $resultado = $this->fetchAll($sql);
            $lista = array();
            
            foreach($resultado as $rs)
            {
                $empresa = new stdClass();
                $empresa->nombre = $rs->nombre;
                $empresa->direccion = $rs->direccion;
                $empresa->email = $rs->email;
                $empresa->rut = $rs->rut;
                $empresa->fono = $rs->fono;
                $empresa->tipo = $rs->tipo;
                $empresa->imagen = $rs->imagen;
                $empresa->tbk_credito = (float)$rs->tbk_credito;
                $empresa->tbk_debito = (float)$rs->tbk_debito;
                $empresa->iva = (float)$rs->iva;
                $empresa->id = $rs->id;
                $empresa->sin_sku_inicio = $rs->sin_sku_inicio;
                $empresa->imprimir_boleta = $rs->imprimir_boleta;
				
                //$empresa->imagen = (!empty($rs->imagen)) ? $rs->imagen:"/imagenes/sitio/default_gallery_2.jpg";
                
                $lista[] = $empresa;
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