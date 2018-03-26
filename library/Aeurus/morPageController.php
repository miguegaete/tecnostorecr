<?php
/*
* PagedList
* @package PagedList
* @author Ariel Mora A.
* @copyright 2010 Ariel Mora A
* @license morfreek
* @create 15 Oct 2010
* @last update 19 Oct 2010
* -- Modificacion para solo mostrar hasta 5 paginas por ves.
*/

if (version_compare(PHP_VERSION, '5.0.0', '<') ) 
	exit("Sorry, this version of PagedList will only run on PHP version 5 or greater!\n");

class funciones_PagedList{
	private $_pagina;
	private $_elementos;
	public  $_total;
	private $_titulo;
	private $_url;
	private $_inicio = 1;
	public  $_limite = 10;
	public  $_totalPaginas;
	public  $_mostrando="";
	public  $_links="";
	public  $_style_div ="";
	public  $_pagedError = false;
	
	public function __construct($pagina, $elementos, $total, $titulo, $url=false, $style_div=""){
		$this->_pagina = $pagina;
		$this->_elementos = $elementos;
		$this->_elementosPagina = $this->_elementos*$this->_pagina;
		$this->_total = $total;
		$this->_titulo = $titulo;
		$this->_url = ($url) ? $url : "/" ;
		$this->_style_div = $style_div;
		$this->_detalleElementos();
		$this->_creaLinks();
	}
	
	private function _totalPaginas(){
		$i=0;
		do{
			$i+=$this->_elementos;
			if($this->_total <= $i){
				$this->_totalPaginas = $i / $this->_elementos;
				$resp = false;
			}else{
				$resp = true;
			}
		}while($resp);
	}
	
	private function _detalleElementos(){
		$elements = ($this->_elementosPagina>$this->_total)?$this->_total:$this->_elementosPagina;
		$this->_mostrando = "<div id=\"paginacion\" $this->_style_div ><p class=\"text-fecha text-align-right\">$elements de $this->_total Paginas.</p>";
	}
	
	private function _creaLinks(){
		$this->_totalPaginas();
		$this->_links.= "<p class=\"text-align-center\"><span class=\"background-i\"><span class=\"background-c\">";
		$this->_links.= "$this->_titulo ";
		if(($this->_pagina-1)==0){
//        	$this->_links.= "<span>Anterior</span>&nbsp;";
		}else{
			$previous_page = (($this->_pagina-1)==1)?'':($this->_pagina-1);
			$this->_links.= "<a href=\"$this->_url$previous_page\" class=\"text-ant-sig\" >Anterior</a>&nbsp;";
		}
		$this->_fin = $this->_totalPaginas;
		if($this->_totalPaginas > $this->_limite){
			$this->_fin = $this->_limite;
			if($this->_pagina>=$this->_fin){
				$this->_fin = ($this->_pagina-($this->_limite-1))+$this->_limite;
				$this->_inicio = $this->_fin-($this->_limite-1);
				if($this->_fin > $this->_totalPaginas){
					$this->_fin = $this->_totalPaginas;
					$this->_inicio = $this->_fin-($this->_limite-1);
				}
			}
		}
		for($i=$this->_inicio; $i<=$this->_fin; $i++){
			if($this->_pagina == $i){
				$this->_links.= "<span class=\"current\">$i</span>";
			}else{
				$page_number = ($i==1)?'':$i;
				$this->_links.= "<a href=\"$this->_url$page_number\" >$i</a>";
			}
		}
		if(($this->_pagina+1)>$this->_totalPaginas){
			//$this->_links.= "&nbsp;<span>Siguiente</span>";
		}else{
			$next_page = ($this->_pagina+1);
			$this->_links.= "&nbsp;<a href=\"$this->_url$next_page\" class=\"text-ant-sig\" >Siguiente</a>";
		}
		$this->_links.="</span></span></p></div>";
		if($this->_pagina > $this->_totalPaginas)
			$this->_pagedError = true;
	}
}
?>