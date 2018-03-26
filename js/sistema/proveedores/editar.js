function InsertarAjax()
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
				mensajeLoad('Se ha actualizado correctamente','ok');
				obtenerAjax($("#txtId").val())
			}else{
				mensajeLoad(response.msg,'error');
			}
			
			
		}
	});	
}

function obtenerAjax(id)
{
	var datos = {'txtId':id}
	
	$.ajax
	({
		url:'/proveedores/obtener/',
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
			$("#txtTipo").val(1);
			$("#txtRUT").val(response.rut);
			$("#txtNombre").val(response.nombre);
			$("#txtEmail").val(response.email);
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


