

function listarAjax(){
	$.ajax
	({
		url:'/tipos-reparaciones/listar/', 
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

			$("#table-tipos-reparaciones tbody").html(response.rs);
			$('#table-tipos-reparaciones').DataTable({
				destroy: true,
				dom: 'Bfrtip',
				stateSave: true,
				buttons: [
											{
												extend: 'excel',
												text: 'Exportar a Excel'
											},
											{
												extend: 'colvis',
												text: 'Columnas'
											}							
											
				]
			} );
			
			
		}
	});		
}



function eliminarAjax(id){ 
	var datos = {'id':id}
	
	$.ajax
	({
		url:'/tipos-reparaciones/eliminar/',
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
	
	listarAjax();
	
	
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var id =  $(this).attr('href');
		
		var r = confirm("¿Está seguro que desea eliminar a este Tipo de Reparacion?");
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


