﻿function InsertarAjax()
{
	var datos = $("#form1").serialize();

	$.ajax
	({
		url:'/proveedores/guardar/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response.rs){
				mensajeLoad('Se ha insertado correctamente','ok');
				limpiar('form1');
				
			}else{
				mensajeLoad(response.msg,'error');
			}
			
			
		}
	});	
}


$(function(){
	
	
	$('#txtRUT').Rut();
	
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});
	
	
	
	
});


