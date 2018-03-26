function InsertarAjax()
{
	var datos = $("#form1").serialize();

	$.ajax
	({
		url:'/clientes/guardar/',
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
				mensajeLoad('Se ha actualizado correctamente','exito');
				//obtenerAjax($('#rut').val());
			}else{
				mensajeLoad(response.msg,'error');
			}
			
			
		}
	});	
}

function obtenerAjax(rut)
{
	var datos = {rut:rut}
	
	$.ajax
	({
		url:'/clientes/obtener/',
		async: true,
		data:datos, 
		type:"post",
		dataType: "json",
		beforeSend:function()
		{
			loadshow();
		},		
		success:function(response)
		{
			loadhide();
			$("#tipo").val(1);
			$("#rut").val(response.rut);
			$("#nombre").val(response.nombre);
			$("#telefono").val(response.telefono);
			$("#email").val(response.email);
			$("#direccion").val(response.direccion);
		}
	});		
	
}


$(function(){
	
	
	$('#rut').Rut();
	
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});
	
	
	
	
});


