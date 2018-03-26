

function guardarAccessosAjax()
{
	
	var cod = $("#hddIdUsuario").val();
	
	var datos = $("#form1").serialize();
	
	
	
	$.ajax
	({
		url:'/usuarios/insertar-accesos/'+cod+'/',
		data:datos,	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(rs)
		{
			loadhide();
			if(rs.rs){
				mensajeLoad(rs.msg,'exito');
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+rs.msg+"]",'error');
			}
		}
	});			
	
}




$(function(){
	

		$("#guardar").click(function(e){
			e.preventDefault();
			guardarAccessosAjax();
		})
	
		$("#limpiar").click(function(e){
			e.preventDefault();
			$("input[name='acceso[]']").prop("checked",false);
			
			
		});
	
})