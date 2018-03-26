$(document).ready(function(){
	$("#form-trabaje-con-nosotros").KoalaForm({ 
		use_css: true,
		disableButtonOnSubmit: false,
		onBlur: false,
		successMessage: {
			title: "<strong>Procesando</strong>:",
			msg:"Su mensaje está siendo procesado, espere un momento."
		},
		/*uploadFileAjax: {
			url: "/trabaje-con-nosotros/envio/",
			success: function(json){
				if(json.result == "OK"){
					window.location.href = json.redirect;
				}else{
					$.KoalaFormHide();
					$.KoalaFormMsgBox("<b>Error</b>:",json.result,'no',null,"ui-icon ui-icon-alert");
					$.KFWOverlay('hide');
				}
			}
		},*/
		onSuccess: function(form) {
			$(form).attr('action',"/trabaje-con-nosotros/envio/");
			return true;
		}
	});
	if($("#system_msg").html()!=null && $("#system_msg").html()!=''){
		if($("#system_msg").attr("class")=='exito'){
			$.KoalaFormMsgBox("",$("#system_msg").html(),null,null,"ui-icon ui-icon-ok");
			$("#koalaFormBox-p").addClass("ui-state-highlight-exito");
		}else{
			$.KoalaFormMsgBox("<b>Error</b>:",$("#system_msg").html(),'no',null,"ui-icon ui-icon-alert");
		}
	}
	
	$(".ubicacion_mapa").colorbox({iframe:true, width:'620px',height:'510px',overlayClose:false});
	
});