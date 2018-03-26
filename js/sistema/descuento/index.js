
function listarDescuentos(){
	$.ajax
	({
		url:'/productos/descuento/listar/',
		async: true,
		type:"post",
		beforeSend:function(){
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			$("#table-descuentos tbody").html(response);	
			$('#table-descuentos').DataTable({
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
function cambiarEstado(id){
	$.ajax
	({
		url:'/productos/descuento/cambiar-estado/',
		async: true,
		data: {'id':id},
		type:"post",
		beforeSend:function(){
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			var html = (r.estado==1)?"<label class='label label-success estado' id='"+id+"'>Activo</label>":"<label class='label label-danger estado' id='"+id+"'>Inactivo</label>";
			$('#estado_'+id).html(html);
			mensajeLoad("Se ha actualizado el 'Estado' del descuento",'exito');			
			//listarDescuentos();
		}
	});	
}



$(function(){

	listarDescuentos();
	
	$("#table-descuentos").on('click','.label',function(e){
		e.preventDefault();
		var r = confirm("¿Está seguro que desea cambiar el estado de este descuento?");
		if (r == true) {
			cambiarEstado($(this).attr('id'));
		} else {
			return false;
		}
		
	})	
	
	
	
});