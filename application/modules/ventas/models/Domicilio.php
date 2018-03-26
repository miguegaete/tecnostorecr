<?php

class Ventas_Model_Domicilio extends Zend_Db_Table_Abstract
{
    protected $_name = "PEDIDO_DOMICILIO";
    private $prefix_url = ADMINISTRACION_URL;
    public $_error = "";
    public $translate = null;
    public $language = null;


    public function listar($limit = false)
    {
    	$sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("pd"=>"PEDIDO_DOMICILIO"),array("id" => "PEDDOM_ID",
                                                                "idped"=>"PED_ID",
                                                                "nombre"=>"PEDDOM_NOMBRE",
                                                                "fono"=>"PEDDOM_FONO",
                                                                "dir"=>"PEDDOM_DIRECCION",
                                                                "estado"=>"PEDDOM_ESTADO",
                                                                "hora"=>"PEDDOM_HORA"))
                    ->where("pd.PEDDOM_ESTADO = 1");
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    
        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $domicilio = new stdClass();
            $domicilio->id = $rs->id;
            $domicilio->idped = $rs->idped;
            //$domicilio->nombre = $rs->nombre;
            //$domicilio->fono = $rs->fono;
           // $domicilio->dir = $rs->dir;
            $domicilio->estado = $rs->estado;
            $domicilio->hora = date('H:i',strtotime($rs->hora));
            $domicilio->hace = hace($rs->hora);
            $domicilio->das = cortar_frase_mejorada("sdal asdjas dlasjdlasd la sdjlajs dlasjd asldj alsjd alsjd lasjdalsj dlajsd lasjdla sdlajs dlajsd lasjd alsjdl",2);
            
            $lista[] = $domicilio;
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

