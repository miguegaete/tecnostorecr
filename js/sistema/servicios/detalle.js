$(document).ready(function(){

    //Mensajes de exito y error
    if($("#system_msg").html()!=null && $("#system_msg").html()!=''){
            if($("#system_msg").attr('class')=='exito'){
                    $.KoalaFormMsgBox("",$("#system_msg").html(),null,null,"ui-icon ui-icon-ok");
                    $("#koalaFormBox-p").addClass("ui-state-highlight-exito");
            }else if($("#system_msg").attr('class')=='error'){
                    $.KoalaFormMsgBox("",$("#system_msg").html(),'no',null,"ui-icon ui-icon-alert");
            }
    }
   	$('.comentar').click(function () {
       $('html, body').animate({
           scrollTop: $(document).height() - 350
       },
       1500);
	   $('#nombre').focus();
       return false;
   });
	
   // GALERIA
	var control = false;
	//if($("#slider2 ul li").length > 1) control = true;
	//$("#slider2").easySlider({controlsFade: false, controlsShow: control});
	$("a[rel='galeria']").colorbox({transition:"elastic",current: "Imagen {current} de {total}"});
	$(".multimedia").tabs();
   
});