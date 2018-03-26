$(function(){
	
	$('#table-categoria').DataTable({
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
	  
	$('#table-categoria').on('click','.eliminar',function(e){
		e.preventDefault();
		var link = $(this).attr("href");

		var txt;
		var r = confirm("¿Está seguro que desea eliminar esta categoria?");
		if (r == true) {
		    window.location = link;
		} else {
		    return false;
		}

	});	
	
	 $('#table-categoria').on('click','.btn-editar',function(e){
		 var id = $(this).val();
		 window.location = '/productos/categoria/obtener/'+id+'/';
	 });
});