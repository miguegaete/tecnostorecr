

function listarAjax(){
	$.ajax
	({
		url:'/reparaciones/listar/',
		async: false,
		data:{'rut':1},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			

			$("#table-reparaciones tbody").html(response.rs);
			$('#table-reparaciones').DataTable({
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
			loadhide();
			
			
		}
	});		
}



function eliminarAjax(id){ 
	var datos = {'id':id}
	
	$.ajax
	({
		url:'/reparaciones/eliminar/',
		async: false,
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


function venderServicio(id){ 

	var datos = {'id':id}
	
	$.ajax
	({
		url:'/reparaciones/vender-servicio/',
		async: false,
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
				mensajeLoad("Servicio convertido en producto correctamente","ok");
			}else{
				mensajeLoad(response.msg,"error");
			}
			
		}
	});		
}

function quitarVenderServicio(id){ 

	var datos = {'id':id}
	
	$.ajax
	({
		url:'/reparaciones/quitar-vender-servicio/',
		async: false,
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
				mensajeLoad("Servicio deshabilitado en productos","ok");
			}else{
				mensajeLoad(response.msg,"error");
			}
			
		}
	});		
}
function activarVenderServicio(id){ 

	var datos = {'id':id}
	
	$.ajax
	({
		url:'/reparaciones/activar-vender-servicio/',
		async: false,
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
				mensajeLoad("Servicio habilitado en productos y se ha actualizado","ok");
			}else{
				mensajeLoad(response.msg,"error");
			}
			
		}
	});		
}

function validaVendido(id){ 

	var datos = {'id':id}
	var valida = false;
	
	$.ajax
	({
		url:'/reparaciones/valida-vendido/',
		async: false,
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
				valida = response.rs;//mensajeLoad("Servicio deshabilitado en productos","ok");
			}else{
				valida = response.rs
			}
			
		}
	});	

	return valida;
}



$(function(){
	
	listarAjax();
	
	
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var id =  $(this).attr('href');
		
		var r = confirm("¿Está seguro que desea eliminar a esta Reparación?");
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
	
	$("#listado").on('click','.vender',function(e){
		var aux = $(this).attr('id').split('_');
		var id =  aux[1];
		
		var chequeado = $(this).is(':checked');
		
		if(!chequeado){
			
			var r = confirm("¿Desea quitar de venta este producto? al quitar de venta este producto no eliminara el producto, solo se deshabilitara de la venta.");
			if (r == true) {
				quitarVenderServicio(id);
			} else {
				return false;
			}				
			
		}else{ 
			
		
			if(!validaVendido(id)){
				var r = confirm("Este servicio jamàs se ha vendido como producto , ¿Desea vender este producto?");
				if (r == true) {
					venderServicio(id);
				} else {
					return false;
				}		
			}else{
				var r = confirm("Este servicio ya estaba registrado como producto , ¿Desea activarlo para vender este producto?");
				if (r == true) {
					activarVenderServicio(id);
				} else {
					return false;
				}				
			}
		}
	}); 	
	
});


