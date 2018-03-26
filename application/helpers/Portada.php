<?php

class Zend_View_Helper_Portada extends Zend_View_Helper_Abstract
{
    public function portada($imagenes, $type='nivoslider' )
    {
            //$portada = new Portada();
            $this->imagenes = $imagenes;
            unset($portada);
            return $this->{$type}();
    }

    #S3SLIDER
    public function s3slider()
    {
        $this->view->headLink()->appendStylesheet('/helper/slider/s3slider_config/s3slider_custom.css');
        $this->view->headScript()->appendFile('/js/jquery/s3Slider/s3Slider.js');
        $this->view->headScript()->appendFile('/helper/slider/s3slider_config/s3slider_init.js');
        $html = '
        <div id="slider">
        	<ul id="sliderContent">';
        foreach($this->imagenes as $imagen){
        	$html .= '
            	<li class="sliderImage">';
        		$html .= (!$imagen->url) ? '<img src="'.$imagen->ruta.'" alt="'.$imagen->titulo.'" title="'.$imagen->titulo.'" />':'<a href="'.$imagen->url.'"><img src="'.$imagen->ruta.'" alt="'.$imagen->titulo.'" title="'.$imagen->titulo.'" /></a>';
            	$html .='<span class="top">'.$imagen->titulo.'</span>
            	</li>';
        }
        $html .='
            	<li class="sliderImage"></li>
            </ul>
        </div>
        ';
      return $html;
    }
    
	# NIVOSLIDER
	public function nivoslider() {
		$this->view->headLink()->appendStylesheet('/js/jquery/nivoslider/2.5.1/nivo-slider.css');
       	$this->view->headLink()->appendStylesheet('/js/jquery/nivoslider/2.5.1/nivoslider_custom.css');
       	$this->view->headScript()->appendFile('/js/jquery/nivoslider/2.5.1/jquery.nivo.slider.pack.js');
       	$this->view->headScript()->appendFile('/js/jquery/nivoslider/2.5.1/nivoslider_init.js');
        $html = '
        <div id="slider-wrapper">
	        <div id="slider" class="nivoSlider">';
        foreach($this->imagenes as $imagen){
			//$ext=($imagen->externo) ? ' target="_blank" ':'';
        	$html .= (!$imagen->url) ? '<img src="'.$imagen->ruta.'" alt="'.$imagen->titulo.'" title="#htmlcaption-'.$imagen->id.'" />':'<a href="'.$imagen->url.'" ><img src="'.$imagen->ruta.'" alt="'.$imagen->titulo.'" title="#htmlcaption-'.$imagen->id.'" /></a>';
		}
        $html .='
	        </div>';
			
		foreach($this->imagenes as $imagen){
			$ext=($imagen->externo) ? ' target="_blank" ':'';
			/*$html .= '<div id="htmlcaption-'.$imagen->id.'" class="nivo-html-caption"> <a href="'.$imagen->url.'" class="text-18" '.$ext.'>'.$imagen->titulo.'</a> '.$imagen->descripcion.' </div>';*/
		}
		
        $html .= '</div>';
		return $html;
    }
}
