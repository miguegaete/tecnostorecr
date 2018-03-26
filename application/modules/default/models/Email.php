<?php

class Default_Model_Email
{
    public $_error = "";
    
    public function  __construct($contacto,$empresa,$cuerpo=false)
    {
    	$this->contacto = $contacto;
    	$this->empresa = $empresa;
        
    	if(!$cuerpo)
        {
            $this->generar_cuerpo();  
        }    
    }
    
    private function generar_cuerpo()
    {
    	/*$this->cuerpo = '
            Contacto desde el Sitio '.utf8_decode($this->empresa->nombre).'
            ------------------------------------------------------
			
            Nombre = '.(trim($this->contacto->nombres)).'
            Tipo = '.(trim($this->contacto->tipo)).'
            Email = '.(trim($this->contacto->email)).'
            Teléfono = '.(trim($this->contacto->telefono)).'
            Fecha de envío = '.date("d-m-Y").'

            Mensaje = '.((trim($this->contacto->mensaje))).'
        ';*/
        
        $ciudades = array('','Antofagasta','Santiago','Chillán','Concepción','Los Ángeles','Temuco','Osorno','Otra');
	$asuntos = array('','Cotización Vigilancia Física','Cotización Seguridad Electrónica','Otro asunto');
        
    	$this->cuerpo = '
            <p style="text-decoration: underline;">'.(($this->contacto->tipo == 'Servicio')?'Consulta de producto':'Contacto').'  '.' desde el sitio de'.' Sharp Carrocerias</p>
            <table style="border: 1px solid #999999; border-spacing: 0;">
            <tr>
            <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.'Nombre'.'</td>
            <td style="padding: 2px; border-bottom: 1px solid #999999;">'.utf8_decode(trim($this->contacto->nombres)).'</td>
            </tr>
			<tr>
            <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.'Apellidos'.'</td>
            <td style="padding: 2px; border-bottom: 1px solid #999999;">'.utf8_decode(trim($this->contacto->apellidos)).'</td>
            </tr>
            <tr>
            <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.utf8_decode('Email').'</td>
            <td style="padding: 2px; border-bottom: 1px solid #999999;">'.utf8_decode(trim($this->contacto->email)).'</td>
            </tr>
            <tr>
            <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.utf8_decode('Teléfono').'</td>
            <td style="padding: 2px; border-bottom: 1px solid #999999;">'.(trim($this->contacto->telefono)).'</td>
            </tr>
            <tr>
            <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.utf8_decode('Fecha de envío').'</td>
            <td style="padding: 2px; border-bottom: 1px solid #999999;">'.date("d-m-Y").'</td>
            </tr>';
			
            if(isset($this->contacto->ser))
            {
			$this->cuerpo.= '<tr>
				 <td style="padding: 2px; border-right: 1px solid #999999; border-bottom: 1px solid #999999; vertical-align: top;">'.'Servicio'.'</td>
				 <td style="padding: 2px; border-bottom: 1px solid #999999;">'.utf8_decode((trim($this->contacto->ser))).'</td>
                                </tr>';
            }
			
			
            
            
		$this->cuerpo.= '<tr>
				<td style="padding: 2px; border-right: 1px solid #999999; vertical-align: top;">'.'Mensaje'.'</td>
				<td style="padding: 2px;">'.utf8_decode((trim(nl2br($this->contacto->mensaje)))).'</td>
				</tr>
				</table>';
        
    }
    
    public function enviar($asunto = "Contacto")
    {
/*    	$config = array(
           'ssl' => 'ssl',
           'port' => 465,
           'auth' => 'login',
           'username' => 'web@aeurus.cl',
           'password' => 'web2010'           
   		);*/
		
		$config = array(
           'auth' => 'login',
           'username' => 'web@carroceriassharp.cl',
           'password' => 'web2013'           
   		);		
        
       	$transport = new Zend_Mail_Transport_Smtp('mail.carroceriassharp.cl', $config);
        
       	$mail = new Zend_Mail();
        
		$mail->setReplyTo($this->contacto->email, $this->contacto->nombres);
      	#$mail->setBodyText(utf8_decode($this->cuerpo));
      	$mail->setBodyHtml($this->cuerpo);
		$mail->setFrom($this->contacto->email, $this->contacto->nombres);
		$mail->addTo($this->empresa->email);	
        
		if(trim($this->empresa->email_copia)!='')
            $mail->addCc($this->empresa->email_copia);
        
		$mail->setSubject(utf8_decode($asunto)." desde el sitio Sharp Carrocerias [".date('d/m/y')." ".date('H:i:s')."]");
	
        if($this->contacto->attachment_file)
        { 
            #debug($this->contacto->attachment_mime,true);
            $mail->createAttachment(file_get_contents($this->contacto->attachment_file),
            $this->contacto->attachment_mime, Zend_Mime::DISPOSITION_INLINE,Zend_Mime::ENCODING_BASE64, $this->contacto->attachment_name);
		}
        
		return $mail->send($transport);  
    }
	
	
    public function enviar2($asunto)
    {
    	/*$config = array(
           'ssl' => 'ssl',
           'port' => 465,
           'auth' => 'login',
           'username' => 'web@aeurus.cl',
           'password' => 'web2010'           
   		);*/
		
		
        
       	$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
        
       	$mail = new Zend_Mail();
        
		$mail->setReplyTo($this->contacto->email, $this->contacto->nombres);
      	#$mail->setBodyText(utf8_decode($this->cuerpo));
      	$mail->setBodyHtml($this->cuerpo);
		$mail->setFrom($this->contacto->email, ($this->contacto->nombres.' '.$this->contacto->apellidos));
		$mail->addTo($this->empresa->email_venta);	
        
		if(trim($this->empresa->email_venta_copia)!='')
            $mail->addCc($this->empresa->email_venta_copia);
        
		$mail->setSubject(utf8_decode($asunto)." desde el sitio Human Consult [".date('d/m/y')." ".date('H:i:s')."]");
	
        if($this->contacto->attachment_file)
        { 
            #debug($this->contacto->attachment_mime,true);
            $mail->createAttachment(file_get_contents($this->contacto->attachment_file),
            $this->contacto->attachment_mime, Zend_Mime::DISPOSITION_INLINE,Zend_Mime::ENCODING_BASE64, $this->contacto->attachment_name);
		}
        
		return $mail->send($transport);  
    }

}