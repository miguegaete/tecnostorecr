function loadshow(){
	$("#load").show();
}
function loadhide(){
	$("#load").hide();
}
function listarCategoriasAjax(){
	$.ajax
	({
		url:'/servicios/listar-categorias-equipo/',
		data:{'servicio':1},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();

			$("#categoria").html(response.rs);
			
		}
	});		
}
function InsertarServicioAjax()
{
	var datos = $("#form1").serialize();
	//var datos = new FormData($(".form1")[0]);

	$.ajax
	({
		url:'/servicios/guardar/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response.rs){
				$("#mensaje-ajax").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><strong> Éxito: </strong> Se ha insertado correctamente</div>');
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);
				listarAjax();
				$("#form1").reset();
			}else{
				$("#mensaje-ajax").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class=" glyphicon glyphicon-remove" aria-hidden="true"></span><strong> Error:</strong>'+response.msg+'</div>');	
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);
			}
			
			
		}
	});	
}
function InsertarClienteAjax()
{
	var datos = $("#form1").serialize();
	//var datos = new FormData($(".form1")[0]);

	$.ajax
	({
		url:'/clientes/guardar/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response.rs){
				$("#mensaje-ajax").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><strong> Éxito: </strong> Se ha insertado correctamente</div>');
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);
			}else{
				$("#mensaje-ajax").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class=" glyphicon glyphicon-remove" aria-hidden="true"></span><strong> Error:</strong>'+response.msg+'</div>');	
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);
			}
			
			
		}
	});	
}
function obtenerClienteAjax(rut){
	

	$.ajax
	({
		url:'/clientes/obtener/',
		data:{'rut':rut},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(response)
		{
			loadhide();
			if(response.rut!=false){
				$('#nombre').val(response.nombre);
				$('#fono').val(response.telefono);
				$('#correo').val(response.email);
				$('#tipo').val('1');				
				$("#mensaje-ajax").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><strong> Ã‰xito: </strong> Este cliente ya existe en nuestros registros, se han cargado sus datos.</div>');
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);
			}else{
				$("#mensaje-ajax").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><strong> Error: </strong>'+response.nombre+'</div>');
				$("#mensaje-ajax").fadeIn(500).delay(5000).fadeOut(500);				
			}
			
			
		}
	});		
	
}


$(function(){
	$('#rut').Rut();
	
	listarCategoriasAjax();
	
	$("#form1").validate({
	  submitHandler:function(form) {
			InsertarClienteAjax();	  
	  }
	});
	$("#form2").validate({
	  submitHandler:function(form) {
		InsertarServicioAjax();	  
	  }
	});	
	
	$('#rut').on('blur',function(){
		
		obtenerClienteAjax($(this).val());
	});
	$("#listado").on('click','.eliminar',function(e){
		e.preventDefault();
		var rut =  $(this).attr('href');
		eliminarAjax(rut);
		
	}); 
	
	$("#listado").on('click','.editar',function(e){
		e.preventDefault();
		var rut =  $(this).attr('href');
		obtenerAjax(rut);
		
	});	
	
	$('#abrir_listado').click(function(e){
		e.preventDefault();
		$('#listar_registros').modal();
		
	});	
	
	
	
})