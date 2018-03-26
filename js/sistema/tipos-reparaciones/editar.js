function InsertarAjax()
{
	var datos = $("#form1").serialize();

	$.ajax
	({
		url:'/tipos-reparaciones/guardar/',
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
		url:'/tipos-reparaciones/obtener/',
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
			$("#id").val(response.id);
			$("#nombre").val(response.nombre);
			$("#estado").val(response.estado);

		}
	});		
	
}


$(function(){
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});
	
	
	
	
});


