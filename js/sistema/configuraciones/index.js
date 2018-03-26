var fileExtension = "";
var fileName = ""
$(document).ready(function(){
 
    $(".messages").hide();
    //queremos que esta variable sea global
    
    //función que observa los cambios del campo file y obtiene información
    $(':file').change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#imagen")[0].files[0];
        //obtenemos el nombre del archivo
        fileName  = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
        showMessage("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</span>");
    });
 
    //al enviar el formulario
    $('.solo-imagen').click(function(){
		
		
		var entra = true;
		
		if(fileName!=""){
			if(!isImage(fileExtension)){
				entra = false;
			}
		}
		
		if(entra){
        //información del formulario
        var formData = new FormData($(".form1")[0]);
        var message = ""; 
        //hacemos la petición ajax  
        $.ajax({
            url: '/subir-imagen/',  
            type: 'POST',
            // Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){
                message = $("<span class='before'>Subiendo la imagen, por favor espere...</span>");
                showMessage(message)        
            },
            //una vez finalizado correctamente
            success: function(data){
                message = $("<span class='success'>La imagen ha subido correctamente.</span>");
                showMessage(message);
                if(isImage(fileExtension))
                {
                    $(".showImage").html("<img src='files/"+data+"' />");
                }
            },
            //si ha ocurrido un error
            error: function(){
                message = $("<span class='error'>Ha ocurrido un error.</span>");
                showMessage(message);
            }
        });
		}else{
			alert("Debes subir una imagen!");
		}
	});
})
 
//como la utilizamos demasiadas veces, creamos una función para 
//evitar repetición de código
function showMessage(message){
    $(".messages").html("").show();
    $(".messages").html(message);
}
 
//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido
function isImage(extension)
{
    switch(extension.toLowerCase()) 
    {
        case 'jpg': case 'gif': case 'png': case 'jpeg':
            return true;
        break;
        default:
            return false;
        break;
    }
}

function InsertarAjax()
{
	//var datos = $("#form1").serialize();
	
	var datos = new FormData($(".form1")[0]);
	var entra = true;
	
	if(fileName!=""){
		if(!isImage(fileExtension)){
			entra = false;
		}
	}
	
	if(entra){
	$.ajax
	({
		url:'/configurar/guardar/',
		data:datos, 
		cache: false,
		contentType: false,
		processData: false,			
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response!=2){
				mensajeLoad("Se ha insertado correctamente","ok")
				var input = $("#imagen");
				input.replaceWith(input.val('').clone(true));
				ObtenerDatos();
			}
		}
	});	
	}else{
		mensajeLoad("Debe subir un archivo tipo imagen, png,jpg o gif.","error");
	}
}

function ObtenerDatos()
{
	var datos = {cargar:1}
	
	$.ajax
	({
		url:'/configurar/obtener/',
		async: true,
		data:datos, 
		type:"post",
		dataType: "json",
		beforeSend: function(){
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			$("#nombre_empresa").val(response[0].nombre);
			$("#direccion").val(response[0].direccion);
			$("#rut").val(response[0].rut);
			$("#fono").val(response[0].fono);
			$("#tipo").val(response[0].tipo);
			$("#email").val(response[0].email);
			$("#tbk_debito").val(response[0].tbk_debito);
			$("#tbk_credito").val(response[0].tbk_credito);
			$("#txtIVA").val(response[0].iva);
			$("#sin_sku_inicio").val(response[0].sin_sku_inicio);
			
			if(response[0].imprimir_boleta == 1){
			
				$("#imprimir_boleta").prop('checked', true);
			
			}else{
				
				$("#imprimir_boleta").prop('checked', false);
			}
			$(".showImage").html("<img src='"+response[0].imagen+"' class='img img-thumbnail' width=150 >");
		}
	});		
	
}
$(function(){
	
	ObtenerDatos();
	
	$('#rut').Rut();
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});
	
	
	
})


