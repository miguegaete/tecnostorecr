function InsertarAjax()
{
	var datos = $("#form1").serialize();

	$.ajax
	({
		url:'/reparaciones/guardar/',
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
				mensajeLoad('Se ha insertado correctamente','exito');
				limpiar('form1');
				ListarUltimasAjax();
			}else{
				mensajeLoad(response.msg,'error');
			}
			
			
		}
	});	
}


function ListarUltimasAjax()
{

	$.ajax
	({
		url:'/reparaciones/listar-ultimas/', 
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

			$("#ultimas-reparaciones").html(response.rs);
			
		}
	});	
}




$(function(){
	
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  },
	  rules:{
		'costo':{
			
                required: true,
                currency: ["$", false] // dollar sign optional
		},
		'costo':{
			
                required: true,
                currency: ["$", false] // dollar sign optional
		}		
	  }
	});
	
	
	
	
});


