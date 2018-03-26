<?php

class Productos_Model_Descuento extends Zend_Db_Table_Abstract
{
    protected $_name = "DESCUENTO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;
	
	
  public function listar($limit = false,$fecha = false,$estado = false)
    {
        
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("d"=>"DESCUENTO"),array("id" => "DES_ID",
														 "barcode" => "DES_BARCODE",
                                                         "descripcion"=>"DES_DESCRIPCION",
                                                         "fecha_inicio"=>"DES_FECHAINICIO",
                                                         "fecha_fin"=>"DES_FECHAFIN",
                                                         "tipo"=>"DES_TIPO",
                                                         "valor"=>"DES_VALOR",
                                                         "estado"=>"DES_ESTADO"
														 
														 ))
                    ->order(array("d.DES_FECHAINICIO DESC","d.DES_FECHAFIN DESC"));    
					
					if($fecha){
						$sql->where("d.DES_FECHAINICIO <= '".date('Y-m-d')."'");
						$sql->where("d.DES_FECHAFIN >= '".date('Y-m-d')."'");
					}
					if($estado){
						$sql->where("d.DES_ESTADO = 1");
					}

        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $descuento = new stdClass();
            $descuento->id = $rs->id;
            $descuento->barcode = $rs->barcode;
            $descuento->descripcion = $rs->descripcion;
            $descuento->fecha_inicio = date('d-m-Y',strtotime($rs->fecha_inicio));
            $descuento->fecha_fin = date('d-m-Y',strtotime($rs->fecha_fin));
            $descuento->tipo = $rs->tipo;
            $descuento->valor = $rs->valor;
            $descuento->estado = $rs->estado;
            $lista[] = $descuento;
        }
        return $lista;
    }

    public function insertar($datos){

        try {
            $id = $this->insert($datos);
			$rs = array('result' => true
						,'message' => $id);
            return $rs;
        }catch(Zend_Exception $e){
			$rs = array('result' => false
						,'message' => $e->getMessage());
            return $rs;
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
            $obj->id = (int)$result->DES_ID;
            $obj->barcode = $result->DES_BARCODE;
            $obj->descripcion = $result->DES_DESCRIPCION;
            $obj->fecha_inicio = $result->DES_FECHAINICIO;
            $obj->fecha_fin = $result->DES_FECHAFIN;
            $obj->tipo = $result->DES_TIPO;
            $obj->valor = $result->DES_VALOR;
            $obj->estado = $result->DES_ESTADO;
        }
        else
        {
            $obj->id = false;
            $obj->nombre = $this->translate->_('Descuento no existe');
        }
        
		unset($sql,$result);
        return $obj;
    }  

	public function ultimoid(){
		

		
       
	   $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("i"=>"information_schema.TABLES"),array("*"))
                    ->where('TABLE_SCHEMA = "devmobil_puntodeventa"')
                    ->where('TABLE_NAME = "DESCUENTO"');
        
        $result = $this->fetchRow($sql);

        $obj = new stdClass();
        
        if($result)
        {
            $obj->id = $result->AUTO_INCREMENT;
        }
        
		unset($sql,$result);
        return $obj;		
		
		
	}




}

