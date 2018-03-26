
function InsertarAjax(){
	
	var datos = $("#form1").serialize();

	$.ajax
	({
		url:'/productos/descuento/guardar/',
		async: true,
		dataType: 'json',
		data:datos, 
		type:"post",
		beforeSend:function(){
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response.result){
				mensajeLoad('Se ha insertado correctamente','exito');
				limpiar('form1');
			}else{
				mensajeLoad(response.message,'error');
				
			}
		}
	});
	
}


function generarBarcode(){
	$.ajax
	({
		url:'/productos/descuento/generar-barcode/',
		async: true,
		type:"post",
		dataType:'json',
		beforeSend:function(){
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			
			$("input[name='barcode']").val(response.id);
			
			
		}
	});		
}


$(function(){

	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});	
	
	$('#fecha_inicio,#fecha_final').datetimepicker({locale: 'es',format:'DD-MM-YYYY'});

	
	$("#table-descuentos tbody").on('click','.label',function(e){
		e.preventDefault();
		var r = confirm("¿Está seguro que desea cambiar el estado de este descuento?");
		if (r == true) {
			cambiarEstado($(this).attr('id'));
		} else {
			return false;
		}
		
	})	
	
	$('.barcode-btn').click(function(){
		generarBarcode();
	});
	
	
});