<?php

class Zend_View_Helper_GaleriaImagenes extends Zend_View_Helper_Abstract
{
    public $limit_image = 3;
    public $width_grande = 440;
    public $height_grande = 305;
    public $width_img = 120;
    public $height_img = 120;
    
    public function galeriaImagenes($imagenes){

        # LIBS
        $this->view->headLink()->appendStylesheet('/js/jquery/colorbox/1.3.15/version_black/colorbox.css');
        $this->view->headLink()->appendStylesheet('/js/jquery/easyslider/1.7/slider-galeria.css');
        $this->view->headScript()->appendFile('/js/jquery/easyslider/1.7/easy_slider.js');
        $this->view->headScript()->appendFile('/js/jquery/colorbox/1.3.15/jquery.colorbox-min.js');
        $this->view->headScript()->appendFile('/js/sistema/galeria/index.js');
    
        $html = '<div class="detalle-right">
    <a href="'.$imagenes[0]->ruta_grande.'" rel="galeria" title="'.$imagenes[0]->descripcion.'">
        <img src="'.$imagenes[0]->ruta_interna.'" alt="'.$imagenes[0]->descripcion.'" width="'.$this->width_grande.'" height="'.$this->height_grande.'" />
    </a>';
        array_shift($imagenes);
        $total_img = count($imagenes);
        if($total_img){
            $html.= '
    <!-- [MINIATURAS] -->
    <div id="content-slider">
        <div id="slider">
        <ul>';
            $i = 0;
            foreach($imagenes as $imagen){
                if($i == '0'){
                    $html.= '
            <li>';
                }
                $html.= '
            <a href="'.$imagen->ruta_grande.'" rel="galeria" title="'.$imagen->descripcion.'">
                <img src="'.$imagen->ruta_galeria.'" alt="'.$imagen->descripcion.'" width="'.$this->width_img.'" height="'.$this->height_img.'" />
            </a>';
                $i++;
                if($i % $this->limit_image == 0){
                    $i = '0';
                    $html.= '
            </li>';
                }
            }
            if($i < $this->limit_image && $i != 0){
                $html.= '
            </li>';
            }
            $html.= '
        </ul>
        </div>
    </div>';
        }
        $html.= '
        </div>';
                
        return $html;
    }    
}

?>