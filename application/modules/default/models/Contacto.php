<?php

class Default_Model_Contacto extends Zend_Db_Table_Abstract
{
    protected $_name = "np_contactos";
    public $_error = "";
    public $nombres;
    public $apellidos;
    public $email;
    public $telefono;
    public $mensaje;
    
    public function  __construct()
    {
        parent::__construct($config = array());        
    }
    
    # OBTENER ID DEL ULTIMO REGISTRO
    public function get_max()
    {
    	$sql = $this->select()->setIntegrityCheck(false)
			->from($this,"MAX(c_id) as MAXIMO");
        
    	$resultado = $this->fetchRow($sql);
        
    	return $resultado->MAXIMO+1;
    }
    
    # REGISTRAR CONTACTO EN CMS Y EMAIL
    public function registrar($empresa)
    {
        $email = new Default_Model_Email($this, $empresa);
        
		if($this->tipo == 'Servicio'){
			$asunto = 'Consulta de producto';	
    	if($email->enviar2($asunto))
        {
            $id = $this->get_max();
            $data = array(
                    "c_id" => $this->get_max(),
                    "c_codigo" => $this->get_max(),
                    "c_nombres" => $this->nombres,
                    "c_apellidos" => $this->apellidos,
                    "c_email"=> $this->email,
                    "c_telefono" => $this->telefono,
					"c_mensaje" => $this->mensaje,					
                    "c_empresa" => '',
                    "c_ciudad" => '',                    
                    "c_tipo" => $this->tipo,                    
                    "c_fecha" => date("Y-m-d"),
                    "c_hora" => date("G:i:s")					
                    );
            
            $this->insert($data);
            
            return true;
    	}
        else
        {
            return false;
    	}
		}else{
					$asunto = 'Contacto';	
    	if($email->enviar($asunto))
        {
            $id = $this->get_max();
            $data = array(
                    "c_id" => $this->get_max(),
                    "c_codigo" => $this->get_max(),
                    "c_nombres" => $this->nombres,
                    "c_apellidos" => $this->apellidos,
                    "c_email"=> $this->email,
                    "c_telefono" => $this->telefono,
					"c_mensaje" => $this->mensaje,					
                    "c_empresa" => '',
                    "c_ciudad" => '',                    
                    "c_tipo" => $this->tipo,                    
                    "c_fecha" => date("Y-m-d"),
                    "c_hora" => date("G:i:s")					
                    );
            
            $this->insert($data);
            
            return true;
    	}
        else
        {
            return false;
    	}
		
		
		
		}
    }
}