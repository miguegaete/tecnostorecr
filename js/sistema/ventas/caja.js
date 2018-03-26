$(function(){	
	
	$('#table-caja').DataTable({ 
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
		,columns: [{
			"className":      'details-control',
			"orderable":      false,
			"data":           null,
			"defaultContent": ''			
			
		}]
	} );	
	

	
});