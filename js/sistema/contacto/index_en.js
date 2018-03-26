$(function()

{

    //Mapa

    $("#ubicacion_mapa").colorbox({iframe:true, width:'600px',height:'500px',overlayClose:false});

    

    $("#form-contacto").KoalaForm({

            use_css: true,

            disableButtonOnSubmit: true,

            onBlur: true,

            successMessage: {

                title: "<strong>Processing</strong>:",

                msg:"Your message is being processed, wait a minute."

            },

            ajaxOptions: {

                    url: "/contacto/envio/en/",

                    success: function(json){

                            if(json.result){

                                    window.location.href = "/en/contact/sent/";

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