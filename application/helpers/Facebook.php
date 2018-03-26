<?php

class Zend_View_Helper_Facebook extends Zend_View_Helper_Abstract
{
    public function facebook($type, $options)
    {
        if(!Zend_Registry::isRegistered('initFacebook'))
        {
            Zend_Registry::set('initFacebook', true);
            $this->headScript();
            $this->root(); 
        }
        
        if(is_string($type) && is_array($options))
        {   
            return $this->{$type}($options);
        } 
        else
            exit($this->view->translate->_('Error al intentar crear el helper para Facebook'));
    }
    
    private function headScript()
    {   
        $this->view->headScript()->appendFile('http://connect.facebook.net/es_ES/all.js#xfbml=1','text/javascript');
    }
    
    private function root()
    {   
        $script  = '<div id="fb-root"></div>';
        $script .= '<script>';
        $script .= '(function(d, s, id){';
        $script .= 'var js, fjs = d.getElementsByTagName(s)[0];';
        $script .= 'if (d.getElementById(id)) {return;}';
        $script .= 'js = d.createElement(s); js.id = id;';
        $script .= 'js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1&appId=180562432025916";';
        $script .= 'fjs.parentNode.insertBefore(js, fjs);';
        $script .= "}(document, 'script', 'facebook-jssdk'));";
        $script .= '</script>';
        
        echo $script;
    }
    
    private function headMeta($options)
    {
        /* ___________Opciones____________________________
         * 
         * title        : título del registro
         * type         : article - blog - website, etc...
         * url          : enlace del sitio
         * image        : imagen del registro
         * description  : descripción del registro
         * site_name    : nombre del sitio
         * ________________________________________________
         */
        
        if(isset($options['url']) ? $url = $options['url'] : $url = '');
        if(isset($options['type']) ? $type= $options['type'] : $type = 'article');
        if(isset($options['title']) ? $title = $options['title'] : $title = '');
        if(isset($options['image']) ? $image = $options['image'] : $image = '');
        if(isset($options['description']) ? $description = $options['description'] : $description = '');
        if(isset($options['site_name']) ? $site_name = $options['site_name'] : $site_name = '');
        
        $this->view->headMeta()->appendName('og:title', $title);
        $this->view->headMeta()->appendName('og:type', $type);
        $this->view->headMeta()->appendName('og:url', $url);
        $this->view->headMeta()->appendName('og:image', $image);
        $this->view->headMeta()->appendName('og:description', strip_tags($description));
        $this->view->headMeta()->appendName('og:site_name', $site_name);
        
        return true;
    }
    
    private function buttonLike($options)
    {
        /* ___________Opciones____________________________
         * 
         * url      : enlace del sitio
         * send     : false - true
         * layout   : button_count  - box_count - standard
         * width    : número entero
         * faces    : false - true
         * action   : like - recommend
         * font     : arial - tahoma
         * ________________________________________________
         */
        
        $div  = '<div class="fb-like" ';
        
        if(isset($options['url']) 
                ? $div .= 'data-href="'.$options['url'].'" '
                : $div .= 'data-href="http://'.$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'].'" ');
        if(isset($options['send']) 
                ? $div .= 'data-send="'.$options['send'].'" '
                : $div .= 'data-send="false" ');
        if(isset($options['layout']) 
                ? $div .= 'data-layout="'.$options['layout'].'" '
                : $div .= 'data-layout="standard" ');
        if(isset($options['width']) 
                ? $div .= 'data-width="'.$options['width'].'" '
                : $div .= 'data-width="120" ');
        if(isset($options['faces']) 
                ? $div .= 'data-show-faces="'.$options['faces'].'" '
                : $div .= 'data-show-faces="false" ');
        if(isset($options['action']) 
                ? $div .= 'data-action="'.$options['action'].'" '
                : $div .= 'data-action="like" ');
        if(isset($options['font']) 
                ? $div .= 'data-font="'.$options['font'].'" >'
                : $div .= 'data-font="arial"> ');

        $div .= '</div>';
        
        return $div;
    }
    
    private function comments($options)
    {
        /* ___________Opciones____________________________
         * 
         * url          : enlace del sitio
         * num-posts    : número de post por comentario
         * width        : número entero
         * ________________________________________________
         */
        
        $div  = '<div class="fb-comments" ';
        
        if(isset($options['url']) 
            ? $div .= 'data-href="'.$options['url'].'" '
            : $div .= 'data-href="http://'.$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'].'" ');
        if(isset($options['num-posts']) 
            ? $div .= 'data-num-posts="'.(int)$options['num-posts'].'" '
            : $div .= 'data-num-posts="2" ');
        if(isset($options['width']) 
            ? $div .= 'data-width="'.(int)$options['width'].'">'
            : $div .= 'data-width="500">');
        
        $div .= '</div>';
        
        return $div;
    }
    
    private function likeBox($options)
    {
        /* ___________Opciones____________________________
         * 
         * url          : enlace del sitio
         * width        : número entero
         * height       : número entero
         * faces        : true - false
         * stream       : true - false
         * header       : true - false
         * ________________________________________________
         */
        
        $div  = '<div class="fb-like-box" ';
        
        if(isset($options['url'])
            ? $div .= 'data-href="'.$options['url'].'" '
            : $div .= 'data-href="http://"'.$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'].'" ');
        if(isset($options['width '])
            ? $div .= 'data-width="'.(int)$options['height '].'" '
            : $div .= 'data-width="292" ');
        if(isset($options['height'])
            ? $div .= 'data-height="'.(int)$options['height'].'" '
            : $div .= 'data-height="292" ');
        if(isset($options['faces'])
            ? $div .= 'data-show-faces="'.$options['faces'].'" '
            : $div .= 'data-show-faces="true" ');
        if(isset($options['stream'])
            ? $div .= 'data-stream="'.$options['stream'].'" '
            : $div .= 'data-stream="false" ');
        if(isset($options['header'])
            ? $div .= 'data-header="'.$options['header'].'">'
            : $div .= 'data-header="true">');

        $div .= '</div>';
        
        return $div;
    }
}