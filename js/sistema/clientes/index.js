

function listarAjax(){
	$.ajax
	({
		url:'/clientes/listar/',
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

			$("#table-clientes tbody").html(response.rs);
			$('#table-clientes').DataTable({
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



function eliminarAjax(rut){ 
	var datos = {'rut':rut}
	
	$.ajax
	({
		url:'/clientes/eliminar/',
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
	
	//$('').DataTable();
	listarAjax();
	
	
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var rut =  $(this).attr('href');
		
		var r = confirm("¿Está seguro que desea eliminar a este Cliente?");
		if (r == true) {
			eliminarAjax(rut);
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


