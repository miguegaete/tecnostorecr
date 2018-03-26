$(function(){
	
	$('#table-producto').DataTable({
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
  
  
  $('.options-prod').click(function(e){
		e.preventDefault();
		var id = $(this).attr('href');
		var menu = id.split('-');
		$('[id*=producto-').each(function(){
			($(this).hide());
		});
		
		$('.menu-prod').each(function(){
			($(this).removeClass('active'));
		});		
		
		$(id).show();
		$('.'+menu[1]).addClass('active');
		
  })

 $(".chosen-select").chosen();
 
 $('#table-producto').on('click','.image-link',function(e){
	e.preventDefault();
	//$(this).magnificPopup({type:'image'});
		$.magnificPopup.open({
		  items: {
			src: $(this).attr('href')
		  },
		  type: 'image'
		});	
 });
 
 
  $('#cont-img-detalle').on('click','.image-link',function(e){
	e.preventDefault();
	//$(this).magnificPopup({type:'image'});
		$.magnificPopup.open({
		  items: {
			src: $(this).attr('href')
		  },
		  type: 'image'
		});	
			
 });
 
 $('#table-producto').on('click','.btn-editar',function(e){
	 var id = $(this).val();
	 window.location = '/productos/obtener/'+id+'/';
	 
 })

 $('#table-producto').on('click','.eliminar',function(e){
	e.preventDefault();
	var link = $(this).attr("href");

	var txt;
	var r = confirm("¿Está seguro que desea eliminar este producto?");
	if (r == true) {
	    window.location = link;
	} else {
	    return false;
	}

});

	$('#sin_sku').change(function(){
		
		if($(this).prop('checked') == true){
			GenerarNuevoCodigo();
		}else{
			$("#sku").val('');
		}
		
	});

});


function GenerarNuevoCodigo(){
	
	$.ajax
	({
		url:'/productos/generate-code/',
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
			console.log(response);
			$("#sku").val(response.pro_sku);
		
			
		}
	});		
}