

function listarAjax(){
	$.ajax
	({
		url:'/movimientos/listar-compras/',
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

				$('#table-consultar-compras tbody').html(response.rs);
				$('#table-consultar-compras').DataTable({
		dom: 'Bfrtip',
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
	});

		}
	});		
}
function eliminarAjax(id){ 
	var datos = {'id':id}
	
	$.ajax
	({
		url:'/movimientos/eliminar/',
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

function buscarProductos(id)
{
	
	
	$.ajax
	({
		url:'/movimientos/listar-productos-compras/',
		data:{'id_compra':id},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			
			$('#table-prod-compras tbody').html(response.rs);
			$('.modal').modal();
			
		}
	});		
}

$(function(){
	
	
	listarAjax();
	
	$("#listado").on('click','.num_doc',function(e){
		e.preventDefault();
		var id = $(this).attr('id');
		buscarProductos(id);
	});
	
	
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var id =  $(this).attr('href');
		
		var r = confirm("¿Está seguro que desea eliminar esta Compra?");
		if (r == true) {
			eliminarAjax(id);
		} else {
			return false;
		}		
		
		
	}); 
			
	
	
	
});


