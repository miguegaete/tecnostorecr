$(function()
{
    //Mapa
    $(".ubicacion_mapa").colorbox({iframe:true, width:'673px',height:'630px',overlayClose:false});
    
    $("#form-contacto").KoalaForm({
            use_css: true,
            disableButtonOnSubmit: true,
            onBlur: false,
            successMessage: {
                title: "<strong>Procesando</strong>:",
                msg:"Su mensaje está siendo procesado, espere un momento."
            },
            ajaxOptions: {
                    url: "/contacto/envio/",
                    success: function(json){
                            if(json.result){
                                    window.location.href = "/contacto/enviado/";
                            }else{
                                    $.KoalaFormHide();
                                    $.KoalaFormMsgBox("",json.msg,'no',null,"ui-icon ui-icon-alert");
                                    $.KFWOverlay('hide');
                            }
                    }
            },
            onSuccess: function(form){
                    return false;
            }
    });

    //Mensajes de exito y error
    if($("#system_msg").html()!=null && $("#system_msg").html()!=''){
            if($("#system_msg").attr('class')=='exito'){
                    $.KoalaFormMsgBox("",$("#system_msg").html(),null,null,"ui-icon ui-icon-ok");
                    $("#koalaFormBox-p").addClass("ui-state-highlight-exito");
            }else if($("#system_msg").attr('class')=='error'){
                    $.KoalaFormMsgBox("",$("#system_msg").html(),'no',null,"ui-icon ui-icon-alert");
            }
    }
});