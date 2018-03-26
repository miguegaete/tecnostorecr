<?php

class Default_Model_Portada extends Zend_Db_Table_Abstract
{
    protected $_name = "np_portada";
    private $prefix_url = "http://carroceriassharp.cl/";
    private $translate = null;
    private $url = null;
    private $idioma_codigo = null;
    
    public function  __construct()
    {
        $session = new Zend_Session_Namespace('language');
        
        if($session->url != 'es')
            $idioma_codigo = 2;
        else
            $idioma_codigo = 1;
        
        $this->translate = $session->translate;
        $this->url = $session->language;
        $this->idioma_codigo = $idioma_codigo;
        
        parent::__construct($config = array());
        unset($idioma_codigo, $session); 
    }
    
    public function listar_imagenes()
    {
    	$sql = $this->select()
                ->setIntegrityCheck(false)
    		->from("np_portada","*")
    		->where("p_estado = 1")
    		->order("p_orden ASC");
    	$imagenes =  $this->fetchAll($sql);

    	$lista = array();
        
    	foreach($imagenes as $aux){
    		$obj = new stdClass();
			$obj->externo = $aux->p_enlace;
			$obj->id = $aux->p_id;
    		$obj->titulo = $aux->p_titulo;
    		$obj->ruta = $this->prefix_url.$aux->p_imagen;
    		$obj->url = (!empty($aux->p_enlace)) 
                        ? "http://".str_replace(array("http://"),"",$aux->p_enlace)
                        : false;
    		$lista[] = $obj;
    	}
        
    	return $lista;
    }
}