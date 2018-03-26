<?php
class pichoPaginacionController{

   private $_prev = "<span class='ant'><a href='[PREV_LINK]'>&lt;&lt; Anterior</a></span>";
   private $_next = "<span class='sig'><a href='[NEXT_LINK]'>Siguiente &gt;&gt;</a></span>";
   private $_pagina = '<span class="pagina"><a href="[PAG_LINK]">[PAG]</a></span>';
  
   
   
   private $_total = 0;
   private $_paginas = 0;
   private $_pagina_actual = 0;


   public function __construct($elementos,$total,$pagina,$cantidad,$elemento_nombre,$url,$url_compose=false) {
        $this->_total = $total;
        $actual = ceil((($desde-1)/$cantidad)+1);
        $this->_pagina_actual = $pagina;
        $this->_desde = ($pagina-1)*$cantidad;
        $this->_paginas = ceil($total/$cantidad);
        $this->_elementos = $elementos;
        $this->_elemento_nombre = ($total>1) ? $elemento_nombre."s":$elemento_nombre;
        $this->_url = $url;
        $this->_url_compose = $url_compose;        
    }

    public function info(){
        $out = 'Mostrando '.$this->_elementos.' de '.$this->_total.' '.$this->_elemento_nombre;
        return $out;
    }

    public function paginas(){
        $out = '';
        if($this->_paginas>1){
            if(!$this->_url_compose)
                $out .= ($this->_pagina_actual==1) ? '':str_replace('[PREV_LINK]',$this->_url.'/'.((($this->_pagina_actual-1)==1) ? '':($this->_pagina_actual-1).'/'), $this->_prev);
            else
                $out .= ($this->_pagina_actual==1) ? '':str_replace('[PREV_LINK]',str_replace('[PAG_VALUE]',(($this->_pagina_actual-1==1)?'':($this->_pagina_actual-1)),$this->_url), $this->_prev);
            for($i=1;$i<=$this->_paginas;$i++){
                if(!$this->_url_compose)
                    $out .= ($this->_pagina_actual==$i) ? '<span class="current">'.$i.'</span>' : str_replace('[PAG_LINK]',$this->_url.'/'.(($i==1) ? '':$i.'/'), str_replace('[PAG]',$i."", $this->_pagina));
                else
                    $out .= ($this->_pagina_actual==$i) ? ''.$i.'':str_replace('[PAG_LINK]',str_replace('[PAG_VALUE]',$i,$this->_url), str_replace('[PAG]',$i."", $this->_pagina));
            }
            if(!$this->_url_compose)
                $out .= ($this->_paginas>1 and $this->_pagina_actual!=$this->_paginas) ? str_replace('[NEXT_LINK]',$this->_url.'/'.($this->_pagina_actual+1)."/", $this->_next):'';
            else
                $out .= ($this->_paginas>1 and $this->_pagina_actual!=$this->_paginas) ? str_replace('[NEXT_LINK]',str_replace('[PAG_VALUE]',($this->_pagina_actual+1),$this->_url), $this->_next):'';
        }
        return $out;
    }   
}
?>