<?php

class Salidas_Model_Salida extends Zend_Db_Table_Abstract
{
   protected $_name = "SALIDA";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($limit = false,$fechaI = false , $fechaF = false)
    {
        
        $hoy = date('Y-m-d 00:00');
        //$hoy2 = date('Y-m-d 00:00');
        $hoy2= date('Y-m-d 00:00', strtotime($hoy . ' +1 day'));


        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("s"=>"SALIDA"),array("id" => "SAL_ID",
                                                         "fecha"=>"SAL_FECHA",
                                                         "descripcion"=>"SAL_DESCRIPCION",
                                                         "valor"=>"SAL_VALOR"));
                    if((!$fechaI) || (!$fechaF)){
                        $sql->where("SAL_FECHA BETWEEN '$hoy' AND '$hoy2'");
                    }else{
                        $fecha1 = date('Y-m-d H:i',strtotime($fechaI)); 
                        $fecha2 = date('Y-m-d H:i',strtotime($fechaF)); 
                        $sql->where("SAL_FECHA BETWEEN '$fecha1' AND '$fecha2'");
                    }   
       

        $sql->order(array("SAL_FECHA ASC","SAL_ID ASC"));

        

        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
        //echo $sql;

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        $total = 0;

        foreach($resultado as $rs)
        {
            $total = $total + $rs->valor;
            $salida = new stdClass();
            $salida->id = $rs->id;
            $fecha = new pichoDateController($rs->fecha);
            $salida->fecha = $fecha->show();
            $salida->tiempo = $fecha->show('time');
            $salida->descripcion = $rs->descripcion;
            $salida->valor = "$".formatearValor($rs->valor);
            $salida->total = "$".formatearValor($total);
            
            
            $lista[] = $salida;
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
            $obj->descripcion = $result->SAL_DESCRIPCION;
            $obj->fecha = $result->SAL_FECHA;
            $obj->valor = formatearValor($result->SAL_VALOR);
            $obj->id = (int)$result->SAL_ID;
            
            
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