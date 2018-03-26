<?php

class Default_Model_Empresa extends Zend_Db_Table_Abstract
{
    protected $_name = "np_datos_de_la_empresa";

    public function  __construct()
    {
    	parent::__construct($config = array());        
    }
    
    public function datos($id = false)
    {
    	$sql = $this->select()->setIntegrityCheck(false)
    		->from("np_datos_de_la_empresa",array(
    			"nombre"=>"d_nombre",
    			"direccion"=>"d_direccion",
    			"telefono"=>"d_telefonos",
    			"email"=>"d_email_contacto",
    			"email_copia"=>"d_email_copia_contacto",
    			"email_venta"=>"d_email_ventas",
    			"email_venta_copia"=>"d_email_copia_ventas",
    			"ubicacion"=>"d_mapa"))
    		->where("d_estado='1'");
			
		if($id)
			$sql->where("d_id ='".$id."'");
		else
			$sql->where("d_id ='1'");
			
    	$datos = $this->fetchRow($sql);
    	
    	//$datos->ubicacion = str_replace("(", "", $datos->ubicacion);
    	//$datos->ubicacion = str_replace(")", "", $datos->ubicacion);
    	//$datos->ubicacion = explode(",",$datos->ubicacion);
        
    	return  $datos;
    }
	public function listar($limit = 2){
	
	
    $sql = $this->select()->setIntegrityCheck(false)
    		->from(array("d" => "np_datos_de_la_empresa"),array(
				"id" => "d_id",
    			"nombre"=>"d_nombre",
    			"direccion"=>"d_direccion",
    			"telefono"=>"d_telefonos",
    			"email"=>"d_email_contacto",
    			"email_copia"=>"d_email_copia_contacto",
    			"email_venta"=>"d_email_ventas",
    			"email_venta_copia"=>"d_email_copia_ventas",
    			"ubicacion"=>"d_mapa"))
    		->where("d_estado='1'");
			
		if($limit)
			$sql->limit($limit);
			
    	$datos = $this->fetchAll($sql);
    	
    	$lista = array();
        
    	foreach($datos as $aux)
        {
		$obj = new stdClass();
		
		$obj->id = $aux->id;
		$obj->nombre = $aux->nombre;
		$obj->direccion = $aux->direccion;
		$obj->telefono = $aux->telefono;
		$obj->email = $aux->email;
		$obj->email_copia = $aux->email_copia;
		$obj->email_venta = $aux->email_venta;
		$obj->email_venta_copia = $aux->email_venta_copia;
		
		
    	$obj->ubicacion = str_replace("(", "", $aux->ubicacion);
    	$obj->ubicacion = str_replace(")", "", $obj->ubicacion);
    	$obj->ubicacion = explode(",",$obj->ubicacion);
		
		$lista[] = $obj;
		}
        
    	return  $lista;	
	
	}
}