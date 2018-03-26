<?php

class Plugin_LangSelector extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    { 
        $language = $request->getParam('language', 'es');
        $session = new Zend_Session_Namespace('language');
        
        $translate = new Zend_Translate(
                                'gettext',
                                APPLICATION_PATH.'/lang',
                                null,
                                array('scan'=>Zend_Translate::LOCALE_FILENAME)
                                );
        
        $locale = new Zend_Locale();
        
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
    
        $translate->setLocale($language);
        
        $view->translate = $translate;
        $view->url = (strtolower($language) != 'es' ? '/'.$language : '');
        
        Zend_Registry::set('translate', $translate);
        Zend_Registry::set('language', $view->language);
        
        $session->translate = $translate;
        $session->language = $view->language;
        $session->url = $language;
    }
}