<?php

class Zend_View_Helper_Twitter extends Zend_View_Helper_Abstract
{
    public function twitter($type, $options)
    {
		
        if(!Zend_Registry::isRegistered('initTwitter'))
        {
            Zend_Registry::set('initTwitter', true);
            $this->headScript();
            $this->root();
        }
        
        if(is_string($type) && is_array($options))
        {   
            return $this->{$type}($options);
        } 
        else
            exit($this->view->translate->_('Error al intentar crear el helper para Twitter'));
    }
    
    private function headScript()
    {   
        $this->view->headScript()->appendFile('http://platform.twitter.com/widgets.js','text/javascript');
    }
    
    private function root()
    {   
        $script  = '<script>';
        $script .= '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];';
        $script .= 'if(!d.getElementById(id)){';
        $script .= 'js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";';
        $script .= 'fjs.parentNode.insertBefore(js,fjs);}}';
        $script .= '(document,"script","twitter-wjs");';
        $script .= '</script>';

        echo $script;
    }
    
    private function tweet($options)
    {
        /* ___________Opciones____________________________
         * 
         * class     : twitter-share-button - twitter-follow-button - 
         * text      : texto referente al tema del registro
         * url       : enlace del sitio
         * count     : horizontal - vertical
         * via       : cuenta twitter
         * lang      : es - en, etc...
         * show      : texto a mostrar en el enlace
         * ________________________________________________
         */
        $enlace  = '<a href="http://twitter.com/share" ';
        
        if(isset($options['text'])
            ? $enlace .= 'data-text="'.$options['text'].'" '
            : $enlace .= 'data-text="" ');
        if(isset($options['class'])
            ? $enlace .= 'class="'.$options['class'].'" '
            : $enlace .= 'class="twitter-share-button" ');
        if(isset($options['url'])
            ? $enlace .= 'data-url="'.$options['url'].'" '
            : $enlace .= 'data-url="http://'.$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'].'" ');
        if(isset($options['count'])
            ? $enlace .= 'data-count="'.$options['count'].'" '
            : $enlace .= 'data-count="horizontal" ');
        if(isset($options['via'])
            ? $enlace .= 'data-via="'.$options['via'].'" '
            : $enlace .= 'data-via="" ');
        if(isset($options['lang'])
            ? $enlace .= 'data-lang="'.$options['lang'].'">'
            : $enlace .= 'data-lang="es">');
        if(isset($options['show'])
            ? $enlace .= $options['show'].'</a>'
            : $enlace .= 'Tweetear'.'</a>');
                
        return $enlace;
    }
    
    public function post($options)
    {
        /* ___________Opciones____________________________
         * 
         * user     : usuario de twitter
         * num      : número de posteros
         * ________________________________________________
         */
        if(isset($options['num']) ? $num = (int)$options['num'] : (int)4);
            
        $script  = '<script type="text/javascript">';
        $script .= 'function relative_time(time_value) {';
        $script .= 'var values = time_value.split(" ");';
        $script .= 'time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];';
        $script .= 'var parsed_date = Date.parse(time_value);';
        $script .= 'var relative_to = (arguments.length > 1) ? arguments[1] : new Date();';
        $script .= 'var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);';
        $script .= 'delta = delta + (relative_to.getTimezoneOffset() * 60);';
        $script .= 'if(delta < 60){';
        $script .= 'return \''.'menos de un minuto'.'\';';
        $script .= '} else if(delta < 120) {';
        $script .= 'return \''.'hace un minuto'.'\';';
        $script .= '} else if(delta < (45*60)) {';
        $script .= 'return (parseInt(delta / 60)).toString() + \' '.'minutos atrás'.'\';';
        $script .= '} else if(delta < (90*60)) {';
        $script .= 'return \''.'hace una hora'.'\';';
        $script .= '} else if(delta < (24*60*60)) {';
        $script .= 'return (parseInt(delta / 3600)).toString() + \' '.'horas atrás'.'\';';
        $script .= '} else if(delta < (48*60*60)) {';
        $script .= 'return \''.'ayer'.'\';';
        $script .= '} else {';
        $script .= 'return (parseInt(delta / 86400)).toString() + \''.'días atrás'.'\';';
        $script .= '} }';
        $script .= 'function twitterCallback(obj){';
        $script .= 'num='.($num+1).';';
        $script .= 'for(i=0;i<num-1;i++){';
        $script .= 'var id = obj[i].user.id;';
        $script .= 'document.getElementById(\'post-twitter\').innerHTML += \'<a href="https://twitter.com/#!/'.$options['user'].'" target="_blank">\' + obj[i].text + \'</a>\' +  \'<p>\' + relative_time(obj[i].created_at) + \'</p>\';';
        $script .= '} }';
        $script .= '</script>';
        $script .= '<div id="post-twitter">';
        $script .= '<script type="text/javascript" src="http://www.twitter.com/statuses/user_timeline/@'.$options['user'].'.json?callback=twitterCallback&count='.$num.'"></script>';
        $script .= '</div>';
        
        return $script;
    }
}