<?php

class pichoDateController{

    protected $_error = false;
    private $_month = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
    private $_smonth = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
    private $_smonth_en = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Agu","Sep","Oct","Nov","Dec");
    private $_days_week = array("lunes","martes","miercoles","jueves","viernes","sabado","domingo");
    private $_sday_week = array("Lun","Mar","Mie","Jue","Vie","Sab","Dom");
    private $_sday_week_en = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");

    public $date = '';
    public $time = '';
    public $datatime = '';
    public $mktime = '';

    public $hour = '';
    public $mins = '';
    public $segs = '';

    public $day;
    public $day_week = '';
    public $sday_week = '';

    public $month;
    public $month_text = '';
    public $smonth_text = '';

    public $year = '';
    public $syear = '';



    public function __construct($date = false) {
        $this->_assign($this->_format($date));
    }

    private function _assign($fecha){
        if(!$this->_error){
            $aux_datetime = explode(" ", $fecha);
            $this->date = $aux_datetime[0];
            $this->time = $aux_datetime[1];
            $this->datetime = $fecha;
            $aux_time = explode(":",$this->time);
            $this->hour = $aux_time[0];
            $this->mins = $aux_time[1];
            $this->segs = $aux_time[2];
            $this->_separator = substr($this->date,4,1);
            $aux_date = explode($this->_separator,$this->date);
            $this->year = $aux_date[0];
            $this->syear = substr($aux_date[0],2);
            $this->month = $aux_date[1];
            $this->month_text = $this->_month[((int)$aux_date[1])-1];
            $this->smonth_text = $this->_smonth[(int)$aux_date[1]-1];
            $this->day = $aux_date[2];
            $this->day_week = date("N",mktime(0,0,0,$this->month,$this->day,$this->year));
            unset($aux_datetime,$aux_time,$aux_date,$day_week_aux); 
        }
    }

    private function _format($fecha){
        $lenght = strlen($fecha);
        switch ($lenght){
            case 19:
                $aux = explode(" ",$fecha);
                $_fecha = (substr($aux[0],2,1)==":") ? $aux[1]:$aux[0];
                $_fecha = (is_numeric(substr($fecha,2,1))) ? $_fecha:$this->_revert($_fecha);
                $_time = (substr($aux[1],2,1)==":") ? $aux[1]:$aux[0];
                $out = $_fecha." ".$_time;
                break;
            case 10:
                $out = (is_numeric(substr($fecha,2,1))) ? $fecha." 00:00:01":$this->_revert($fecha)." 00:00:01";
                break;
            case 8:
                $out = "0000-00-00 ".$fecha;
                break;
            case 0:
                $out = date("Y-m-d H:i:s");
                break;
            default:
                $out = false;
                $this->_error = "no soportado";
                break;
        }
        return $out;
    }
    
    private function _revert($fecha){
        $_aux = explode("-",$fecha);
        return  $_aux[2]."-".$_aux[1]."-".$_aux[0];
    }

    public function show($type='normal'){
        if(!$this->_error){
            switch ($type){
                case  'url':
                    $out = $this->year."/".$this->month."/".$this->day;
                    break;
				case 'date':
					$out = $this->day ."-". $this->month."-".$this->year;
					break;
                case  'min':
                    $out = $this->day." de ".$this->smonth_text;
                    break;
                case  'rss':
                    $out = $this->_sday_week_en[$this->day_week-1].", ".$this->day." ".$this->_smonth_en[$this->month-1]." ".$this->year." ".$this->time." -0400";
                    break;
				case 'time':
					$out = $this->time;
					break;
                default:
                    $out = $this->day." de ".$this->month_text." de ".$this->year;
                   break;
            }
        }else{
            
            $out = $this->_error;
        }
        return $out;
    }

    //ToDO
    /*
    public function add_days();

    public function sub_date();

    public function add_minutes();

    public function diff_date();

    public function sub_date();
    */


}