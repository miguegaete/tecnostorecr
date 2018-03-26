function cambiarEstadoAjax(id){
	$.ajax
	({
		url:'/usuarios/cambiar-estado/',
		data:{id:id},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			
			if(r.rs){
				//alert('exito')
				var html = (r.estado==1)?"<label class='label label-success estado' id='"+id+"'>Activo</label>":"<label class='label label-danger estado' id='"+id+"'>Inactivo</label>";
				$('#estado_'+id).html(html);
				mensajeLoad("Se ha actualizado el 'Estado' del Usuario",'exito');
				
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+r.msg+"]",'error');
			}
		}
	});
	
}
function setPassword(){
	
	var datos = $('#form-pass').serialize();
	
	
	$.ajax
	({
		url:'/usuarios/pass/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			
			if(r.rs){
				$('#modalPassword').modal('hide');
				$('#clave').val('');
				$('#clave_2').val('');
				
				mensajeLoad("Se ha actualizado 'Contraseña' del Usuario",'exito');
				
				
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+r.msg+"]",'error');
			}
		}
	});		
}


$(function(){


	$('#table-usuario').DataTable();
	//Valida Form Password
	$('#form-pass').validate({
		rules : {
			clave : {
				minlength : 5
			},
			clave_2 : {
				minlength : 5,
				equalTo : "#clave"
			}
		},
        submitHandler: function (form) { 
			//$(this).submit();
			setPassword();
        }		
	});
			

	$('#guardar').click(function(){
		$("#form-pass").submit();
	})
	
	$('.password').click(function(){
		var id = $(this).attr('id');
		
		$("#id_usuario_password").val(id);
		
		$("#modalPassword").modal();
	
	});
	

$('.estado-content').on('click','.estado',function(){
	
	var id =  $(this).attr('id');
	
	var r = confirm("¿Está seguro que desea cambiar el estado de este usuario?");
	if (r == true) {
		if(id!=""){
			cambiarEstadoAjax(id);
		}else{
			alert('Ocurre un problema');
		}
		   
	} else {
	    return false;
	}	

})

$("#table-usuario").on('click','.eliminar',function(e){
	e.preventDefault();
	var link = $(this).attr("href");

	var txt;
	var r = confirm("¿Está seguro que desea eliminar este usuario?");
	if (r == true) {
	    window.location = link;
	} else {
	    return false;
	}

});


$('#table-usuario').on('click','.accesos',function(e){
	e.preventDefault();
	
	var link = $(this).attr('href');
	window.location = link;

});


});