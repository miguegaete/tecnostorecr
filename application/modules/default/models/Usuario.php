<?php

class Default_Model_Usuario extends Zend_Db_Table_Abstract
{
    protected $_name = "USUARIO";

    public function  __construct()
    {
    	parent::__construct($config = array());        
    }

    public function obtener_login($user, $pass) {

        $contrasena = $this->obtenerpass($user);
        
        
        if ($contrasena){
            $password = crypt($pass, $contrasena->pass);

            $sql =   $this->select()->setIntegrityCheck(false)
                    ->from(array("u" => "USUARIO"), array("user" => "USU_LOGIN", "pass" => "USU_PASS"))
                    ->where("USU_LOGIN = ?", $user)
                    ->where("USU_PASS = ?", $password)
                    ->where("USU_ESTADO = ?", 1);
            
            $resultado = $this->fetchRow($sql);

            if (is_null($resultado)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }   

    public function obtenerpass($usuario) {

        $sql = $this->select()->setIntegrityCheck(false)
                ->from(array("u" => "USUARIO"), array("pass" => "USU_PASS"))
                ->where("USU_LOGIN = '$usuario'");

        $resultado = $this->fetchRow($sql);
        $datos_user = new stdClass();
        if ($resultado) {

            $datos_user->pass = $resultado->pass;

            return $datos_user;
        } else {
            return false;
        }
    }     

    public function RandomString($length = 10, $uc = TRUE, $n = TRUE, $sc = FALSE) {
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if ($uc == 1)
            $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($n == 1)
            $source .= '1234567890';
        if ($sc == 1)
            $source .= '|@#~$%()=^*+[]{}-_';
        if ($length > 0) {
            $rstr = "";
            $source = str_split($source, 1);
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double) microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr .= $source[$num - 1];
            }
        }
        return $rstr;
    }    

    public function crypt_blowfish_bycarluys($password, $digito = 7) {
        $set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = sprintf('$2a$%02d$', $digito);
        for ($i = 0; $i < 22; $i++) {
            $salt .= $set_salt[mt_rand(0, 63)];
        }
        return crypt($password, $salt);
    }    
    

    public function listar($limit = false)
    {
        $sql = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array("u"=>"USUARIO"),array( "id" => "USU_ID",
                                                        "usuario" => "USU_LOGIN",
                                                        "nombre"=>"USU_NOMBRE",
                                                        "tipo"=>"USU_TIPO",
                                                        "email"=>"USU_EMAIL",
							"estado" => "USU_ESTADO",
                                                        "comision"=>"USU_COMISION"
                                                        ))
                    ->order(array("USU_NOMBRE ASC","USU_ID ASC"));    
        
        if($limit != false && is_numeric($limit))
            $sql->limit($limit);
    

        $resultado = $this->fetchAll($sql);
        $lista = array();
        
        foreach($resultado as $rs)
        {
            $usuario = new stdClass();
            $usuario->id = $rs->id;
            $usuario->usuario = $rs->usuario;
            $usuario->nombre = $rs->nombre;
            $usuario->tipo = $rs->tipo;
            $usuario->email = $rs->email;
            $usuario->comision = $rs->comision;
            $usuario->estado = $rs->estado;
            
            $lista[] = $usuario;
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
            $obj->id = (int)$result->USU_ID;
            $obj->usuario = $result->USU_LOGIN;
            $obj->nombre = $result->USU_NOMBRE;
            $obj->tipo = $result->USU_TIPO;
            $obj->email = $result->USU_EMAIL;
            $obj->comision = $result->USU_COMISION;
            $obj->estado = $result->USU_ESTADO;
            
        }
        else
        {
            $obj->id = false;
            $obj->nombre = $this->translate->_('Usuario no existe');
        }
        
    unset($sql,$result);
        return $obj;
    }     
    
}