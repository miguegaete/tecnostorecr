<?php

class Cartolas_Model_Cartola extends Zend_Db_Table_Abstract
{
   protected $_name = "CARTOLA";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($limit = false, $fecha = false)
    {
        
        $hoy = date('Y-m-d 00:00');
        $hoy2 = date('Y-m-d 00:00', strtotime($hoy . ' +1 day'));

        $sql = $this->select()
					->distinct("c.CAR_ID")
                    ->setIntegrityCheck(false)
                    ->from(array("c"=>"CARTOLA"),array("id" => "CAR_ID",
													"idie"=>"CAR_IDIE",
													"tipo"=>"CAR_TIPO",
													"fecha"=>"CAR_FECHA",
													"valor"=>"CAR_VALOR",
													"forma"=>"FOR_ID"
													));

                    if($fecha == NULL){
                        $sql->where("CAR_FECHA BETWEEN '$hoy' AND '$hoy2'");
                    }else{
                        $fecha = date('Y-m-d 00:00',strtotime($fecha));
						$fecha2 = date('Y-m-d 00:00', strtotime($fecha . ' +1 day'));
                        $sql->where("CAR_FECHA BETWEEN '$fecha' AND '$fecha2'");
                    }
		$sql->order(array("CAR_FECHA DESC"));

        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
		
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $cartola = new stdClass();
            $cartola->id = $rs->id;
            $cartola->idie = $rs->idie;
            $cartola->tipo = $rs->tipo;
            //$fecha = new pichoDateController($rs->fecha);
            $cartola->fecha = date('d/m/Y H:i:s', strtotime($rs->fecha));
            //$cartola->tiempo = $fecha->show('time');
            $cartola->valor = formatearValor($rs->valor);
			$cartola->valor2 = $rs->valor;
			$cartola->forma = ($rs->forma);
            
            $lista[] = $cartola;
        }
        return $lista;
    }

    public function insertar($datos){
        try {
            $id = $this->insert($datos);
            return $id;
        }catch(Zend_Exception $e){
            return false;

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
            $obj->id = (int)$result->SAL_ID;
            $obj->fecha = $result->SAL_FECHA;
            $obj->descripcion = $result->SAL_DESCRIPCION;
            $obj->valor = $result->SAL_VALOR;
        }
        else
        {
            $obj->id = false;
            $obj->descripcion = $this->translate->_('Salida no existe');
        }
        
    unset($sql,$result);
        return $obj;
    }        

}