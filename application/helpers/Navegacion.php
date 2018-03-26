<?php

class Zend_View_Helper_Navegacion extends Zend_View_Helper_Abstract
{
    public function navegacion($rutas)
    {
        $html = '<li id="navigation"><a href="'.$this->view->url.'/">'.$this->view->translate->_('Inicio').'</a></li>';
	$i = 1;
	$ruta_master = '/';
	foreach($rutas as $nombre=>$ruta)
        {
            $ruta_master .= $ruta."/";
            $html .= ($i==count($rutas))? '<li>'.$nombre.'</span>':'<a href="'.$this->view->url.$ruta_master.'">'.$this->view->translate->_($nombre).'</a></li>';
            $i++;
	}	
        
	return $html;	
    }    
}
