<?php

class Configuraciones_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
		$session_usu = new Zend_Session_Namespace('login');
		if(!isset($session_usu->usuario))
		{
			$this->_redirect("/");
		}
		

    }

    public function indexAction()
    {
		$this->view->current2 = 'mod_configurar';
        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/configuraciones/index.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryrut/jqueryrut.js');
        
		$this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/jquery.validate.js');
        $this->view->headScript()->appendFile('/js/jquery/jqueryvalidate/messages_es.js');
		
		#Titulo
		$this->view->title = "Configurar";
    }
	public function guardarAction(){
		
		if ($this->getRequest()->isPost()) {
		$configurar = new Configuraciones_Model_Configurar();
		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			if(!empty($_FILES['archivo']['name'])){
				$file = $_FILES['archivo']['name'];
				$ruta_server = RUTA_SERVER_LOGO.'/'.$file;
				$ruta_http = RUTA_HTTP_LOGO.$file;
				move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta_server);
				$rs = $configurar->obtener(2);
				
				unlink(RUTA_ELIMINAR.$rs[0]->imagen);
				
				
				
			}
		}	
		
		
		if(isset($ruta_http)){
		$datos = array('CON_NOMBRE' => $this->_getParam("nombre_empresa"),
						'CON_EMAIL' => $this->_getParam("email"),
						'CON_TELEFONO' => $this->_getParam('fono'),
						'CON_DIRECCION' => $this->_getParam('direccion'),
						'CON_RUT' => $this->_getParam('rut'),
						'CON_TIPO' => $this->_getParam('tipo'),
						'CON_IMAGEN' => $ruta_http,
						'CON_TBK_CREDITO' => $this->_getParam('tbk_credito'),
						'CON_TBK_DEBITO' => $this->_getParam('tbk_debito'),
						'CON_IVA' => $this->_getParam('txtIVA'),
						'CON_SKU' => $this->_getParam('sin_sku_inicio'),
						'CON_IMPRIMIR' => $this->_getParam('imprimir_boleta')
					);
		}else{
					$datos = array('CON_NOMBRE' => $this->_getParam("nombre_empresa"),
						'CON_EMAIL' => $this->_getParam("email"),
						'CON_TELEFONO' => $this->_getParam('fono'),
						'CON_DIRECCION' => $this->_getParam('direccion'),
						'CON_RUT' => $this->_getParam('rut'),
						'CON_TIPO' => $this->_getParam('tipo'),
						'CON_TBK_CREDITO' => $this->_getParam('tbk_credito'),
						'CON_TBK_DEBITO' => $this->_getParam('tbk_debito'),						
						'CON_IVA' => $this->_getParam('txtIVA'),
						'CON_SKU' => $this->_getParam('sin_sku_inicio'),
						'CON_IMPRIMIR' => $this->_getParam('imprimir_boleta')
					);
		}
					
		$where = "CON_ID = 2";
		$rs = $configurar->actualizar($datos,$where);
		
		echo $rs;
		exit;

		}
		
		
		
	}
	public function subirImagenAction(){
		
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{
			$file = $_FILES['archivo']['name'];
			echo $ruta_server = RUTA_SERVER_LOGO.$file;
			echo $ruta_http = RUTA_HTTP_LOGO.$file;
			exit;
			move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta_server);
		}		
	}
	
	public function obtenerAction()
	{
		$configurar = new Configuraciones_Model_Configurar();
		$rs = $configurar->obtener(2);
		$this->_helper->json($rs);
		exit;
		
	}
	
	public function imprimirVentaAction()
	{
		$configurar = new Configuraciones_Model_Configurar();
		$rs = $configurar->obtener(2);
		$this->_helper->json($rs);
		exit;
		
	}
	
	public function respaldobdAction(){
		$dbhost = 'devmobilecl.ipagemysql.com';
		$dbname = 'tecnostorecr';
		$dbuser = 'tecnostorecr';
		$dbpass = 'ExpE_{}#2';
		 
		$backup_file = $dbname . date("Y-m-d-H-i-s") . '.sql';
		 
		// comandos a ejecutar
		$commands = array(
				"mysqldump --opt -h $dbhost -u $dbuser -p$dbpass -v $dbname > $backup_file"
		);
		 
		// ejecución y salida de éxito o errores
		foreach ( $commands as $command ) {
				system($command,$output);
		}
		
		$file = "$backup_file";

		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
		}

		 exit;
		
	}
		
	


}