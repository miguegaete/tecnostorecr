<?php
class Zend_View_Helper_GaleriaMultimedia extends Zend_View_Helper_Abstract{
	
	public $limit_image = 3;
	public $width_img = 100;
	public $height_img = 100;
	public function galeriaMultimedia($imagenes=false, $videos=false){

		# LIBS
		$this->view->headLink()->appendStylesheet('/js/jquery/ui/1.8.16/ui-darkness/jquery-ui-1.8.16.custom.css');
		$this->view->headLink()->appendStylesheet('/js/jquery/colorbox/1.3.15/version_black/colorbox.css');
		$this->view->headLink()->appendStylesheet('/js/jquery/easyslider/1.7/slider-galeria.css');
        $this->view->headScript()->appendFile('/js/jquery/easyslider/1.7/easy_slider.js');
        $this->view->headScript()->appendFile('/js/jquery/colorbox/1.3.15/jquery.colorbox-min.js');
        $this->view->headScript()->appendFile('/js/jquery/ui/1.8.16/jquery-ui-1.8.16.custom.min.js');
        $this->view->headScript()->appendFile('/js/sistema/galeria/index.js');
	
	if($imagenes || $videos):
		$html = '
<div class="multimedia">
	<ul>';
		if($imagenes && $videos){
		$html .='<li class="tabL">
			<a href="#pics">
				'."Fotografías".'
			</a>		
		</li>
		<li class="tabR">
			<a href="#videos">
				'."Video".'
			</a>		
		</li>
		';
		}
		if($videos && !$imagenes):
		$html .= '
		<li class="tabL">
			<a href="#videos">
				'."Video".'
			</a>		
		</li>	
		';
		endif;
		if($imagenes && !$videos):
		$html .= '
		<li class="tabL">
			<a href="#pics">
				'."Fotografías".'
			</a>		
		</li>	
		';
		endif;
		if($imagenes){
		$html .=' 
	</ul>
<div class="imagenes" id="pics">
	<a href="'.$imagenes[0]->ruta_grande.'" rel="galeria" title="'.htmlentities($imagenes[0]->descripcion).'">
		<img src="'.$imagenes[0]->ruta_interna.'" alt="'.htmlentities($imagen->descripcion).'" />
	</a>
	<div class="clear"></div>';
		array_shift($imagenes);
		$total_img = count($imagenes);
		if($total_img){
			$html.= '
	<!-- [MINIATURAS] -->	
	<div id="'.(($total_img>$this->limit_image)?'content-slider':'content').'" class="contSlider">
		<div id="slider">	
		<ul style="width:30000000px;">';
			$i = 0;
			foreach($imagenes as $imagen):	
				
				
					if($i == 0):
						$html .= '<li>';
					else:
						if($i % $this->limit_image == 0 && $i != $total_img):
							$html.='</li><li>';
						endif;
					endif;					
					$html.= '
						<a href="'.$imagen->ruta_grande.'" class="img" rel="galeria" title="'.htmlentities($imagen->descripcion).'">
							<img src="'.$imagen->ruta_galeria.'" alt="'.htmlentities($imagen->descripcion).'" width="'.$this->width_img.'" height="'.$this->height_img.'" />
						</a>';
					$i++;
					if($i == $total_img)
						$html.='</li>';
				
				
				
				
				
				
			endforeach;
			$html.= '
		</ul>
		</div>
	</div>';
		}
		$html.= '
</div>';}
if($videos):
//TODO: Definir galeria de videos
$html.='<div id="videos">
				<object width="400" height="300" style="position: relative; z-index: 1000;">
					<param value="'.$videos.'" name="movie"/>
					<param value="true" name="allowFullScreen"/>
					<param value="always" name="allowscriptaccess"/>
					<param name="wmode" value="transparent" />
					<embed width="400" height="300" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" movie="'.$videos.'" src="'.$videos.'" wmode="transparent"/>
				</object>
</div>';
endif;

$html.='</div>';
else:
	//$html = "No existe contenido multimedia asociado a esta sección";
endif;

		return $html;
	}    
}
?>