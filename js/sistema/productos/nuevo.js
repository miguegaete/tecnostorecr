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


$(function(){
	
	
	$('#sin_sku').change(function(){
		
		if($(this).prop('checked') == true){
			GenerarNuevoCodigo();
		}else{
			$("#sku").val('');
		}
		
	});
	
	
})