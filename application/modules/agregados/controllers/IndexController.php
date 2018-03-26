<?php

class Agregados_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        Zend_Session::start();
        /* Initialize action controller here */
		 $this->view->current = 3;
        $session_usu = new Zend_Session_Namespace('login');
        if(!isset($session_usu->tipo)){
            $this->_redirect("/");
        }else{
            if($session_usu->tipo!=1){
                $this->_redirect("/ventas/");
            }
        }          
    }

    public function indexAction(){

        $session_agr = new Zend_Session_Namespace('agregados');
        $agregado = new Agregados_Model_Agregado();

        #Archivos JS
        $this->view->headScript()->appendFile('/js/sistema/agregados/index.js');

        #Eliminar Validación
        if(isset($session_agr->eliminado)){
            if($session_agr->eliminado == 'si'){

                echo alerta("Eliminado Correctamente","success");
                $session_agr->unsetAll();

            }elseif($session_agr->eliminado == 'no'){

                echo alerta($session_agr->mensaje,"error");
                $session_agr->unsetAll();
            }
        }   

        #Cargar datos si este se edita

         if(isset($session_agr->agregado_editar)){

            $this->view->datos_editar = $session_agr->agregado_editar;
            $session_agr->unsetAll();


         }       

        #Insertar Validación
        if ($this->getRequest()->isPost()) {

            if($_FILES['adjunto']['name'] !=""){
                $imagen = $_FILES['adjunto'];
                if (!empty($imagen)) {
                    $archivo_tipo = explode('/', $_FILES['adjunto']['type']);
                    $formato = $archivo_tipo[1];
                
                    $ruta_imagen = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
                    $ruta_imagen2 = ($_FILES['adjunto']['name'] != '') ? ADMINISTRACION_IMG_SERVER . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name'])) : '';
                
                if ($formato == "jpg" || $formato == "jpeg" || $formato == 'png' || $formato == 'gif'){
                    if(move_uploaded_file($_FILES['adjunto']['tmp_name'], $ruta_imagen)){
                        $img_original = imagecreatefromjpeg($ruta_imagen);
                        list($ancho,$alto)=getimagesize($ruta_imagen);                      
                        $ancho_normal = 500;
                        $alto_normal = 350;
                        $ancho_galeria = 200;
                        $alto_galeria = 200;
                        if(($ancho >= $ancho_normal) && ($alto >= $alto_normal)){
                            $calidad=95;
                            $img_normal = imagecreatetruecolor($ancho_normal,$alto_normal);
                            imagecopyresampled($img_normal,$img_original,0,0,0,0,$ancho_normal, $alto_normal,$ancho,$alto);
                            $ruta_1_n =  'uta_normal_' . date('His') . '_' . implode('_', explode(' ', $_FILES['adjunto']['name']));
                            $ruta_2_n = ADMINISTRACION_IMG_SERVER .'r'.$ruta_1_n;
                            $ruta_imagen_normal =  ADMINISTRACION_IMG.'/r'.$ruta_1_n;
                            imagejpeg($img_normal,$ruta_imagen_normal,$calidad);
                            unlink($ruta_imagen);       
                        }else{
                            echo alerta("la imagen es muy pequeña, debe ser mayor  a Ancho : $ancho_normal px Alto : $alto_normal","error");
                            return false;
                        }   
                        
                    }
                }else{
                    echo alerta("formato de imagen no soportado","error");
                    return false;
                }
            }
            }



            
            $datos = array( 'AGR_NOMBRE' => $this->_getParam('nombre'),
                            'AGR_DESCRIPCION' => $this->_getParam('descripcion'),
                            'AGR_VALOR' => $this->_getParam('valor'));

                            



            
            if($this->_getParam('id')!=""){
                
                if(!empty($ruta_2_n)){
                    $datos['AGR_IMAGEN'] = (!empty($ruta_2_n))?$ruta_2_n:'';
                }
                

                $where = "AGR_ID = ".$this->_getParam('id');
                $action_db =  $agregado->actualizar($datos,$where);
                if(is_numeric($action_db)){
                   echo alerta("Se ha actualizado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 

            }else{
                $datos['AGR_IMAGEN'] = (!empty($ruta_2_n))?$ruta_2_n:'';
                $action_db =  $agregado->insertar($datos);   
                if(is_numeric($action_db)){
                   echo alerta("Se ha insertado correctamente","success");
                }else{
                    echo alerta($action_db,"error");
                }                 
            }
        }        

        $this->view->agregados = $agregado->listar();
		$this->view->title = "Agregados";
		#navigation
		$this->view->nav = $this->view->navegacion(array($this->view->translate->_("Agregados")=>"/"));

        unset($_POST);
    }

    public function eliminarAction(){
            $session_agr = new Zend_Session_Namespace('agregados');
            $id = $this->_getParam("id");
            $agregado = new Agregados_Model_Agregado();
            $where = "AGR_ID = $id";
            $accion_db  = $agregado->eliminar($where); 
            if($accion_db == 'si'){
                $session_agr->eliminado = $accion_db;            
            }else{
                $session_agr->eliminado = 'no';
                $session_agr->mensaje = $accion_db;
            }
            $this->_redirect("/agregados/");
            exit;
    }

    public function obtenerAction(){

            $session_agr = new Zend_Session_Namespace('agregados');
            $id = $this->_getParam("id");

            $agregado = new Agregados_Model_Agregado();
            $where = "AGR_ID = $id";
            $session_agr->agregado_editar = $agregado->obtener($where);
            $this->_redirect("/agregados/");
            exit;            
    }


}