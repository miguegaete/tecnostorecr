

function listarAjax(){
	$.ajax
	({
		url:'/proveedores/listar/',
		data:{'rut':1},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();

			//$("#listado").html(response.rs);
			$("#table-proveedores tbody").html(response.rs);
			$("#table-proveedores").DataTable();
			
		}
	});		
}



function eliminarAjax(id){ 
	var datos = {'id':id}
	
	$.ajax
	({
		url:'/proveedores/eliminar/',
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
			if(response.rs){
				mensajeLoad("Se ha eliminado correctamente","ok");
				listarAjax();
			}else{
				mensajeLoad(response.msg,"error");
			}
			
		}
	});		
}


$(function(){
	
	//$('#table-proveedores').DataTable();
	listarAjax();
	
	
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var id =  $(this).attr('href');
		
		var r = confirm("¿Está seguro que desea eliminar a este Proveedor?");
		if (r == true) {
			eliminarAjax(id);
		} else {
			return false;
		}		
		
		
	}); 
		
	
	$("#listado").on('click','.editar',function(e){
		e.preventDefault();
		var url =  $(this).val();
		window.location = url; 
		
	});	
	
	
	
});


