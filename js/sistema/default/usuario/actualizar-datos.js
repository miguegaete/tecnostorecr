
function actualizarDatosUsuarioAjax(){
	
	var datos = $('#form1').serialize();
	
	$.ajax
	({
		url:'/usuarios/actualizar-datos-ajax/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			
			if(r.rs){
				mensajeLoad(r.msg,'exito');
				
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+r.msg+"]",'error');
			}
		}
	});		
}
function actualizarPassUsuarioAjax(){
	
	var datos = $('#form-pass').serialize();
	
	$.ajax
	({
		url:'/usuarios/actualizar-pass-ajax/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			
			if(r.rs){
				mensajeLoad(r.msg,'exito');
				limpiar('form-pass');
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+r.msg+"]",'error');
				limpiar('form-pass');
			}
		}
	});		
}



$(function(){
	
	$('#form1').validate({
        submitHandler: function (form) { 
			actualizarDatosUsuarioAjax();
        }		
	});
	
	$('#form-pass').validate({
		rules : {
			password2 : {
				minlength : 5
			},
			password3 : {
				minlength : 5,
				equalTo : "#password2"
			}
		},
        submitHandler: function (form) { 
			actualizarPassUsuarioAjax();
        }		
	});	
	
});