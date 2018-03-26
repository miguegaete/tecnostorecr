function cargarDatos(){
		var sku = $('input[name=sku]').val();
		
		
		var datos = {sku:sku,modulo:1};
	
			$.ajax
			({
				url:'/productos/obtener-sku/',
				async: true,
				data:datos, 
				type:"post",
				dataType: "json",
				beforeSend:function(){
					loadshow();
				},
				success:function(response)
				{
					loadhide();
					if(response.id && response.manejostock == 1){
						$("#cod").val(response.sku);
						$("#id").val(response.id);
						$("#nombre").val(response.nombre);
						$("#precio").val(formatNumber.new(response.valor));
						$("#stock").val(response.stock);
						$("cantidad").focus();
						$('input[name=sku]').val('');
					}else{
						mensajeLoad('Este código no está registrado o el producto no tiene manejo de stock, verifique.','error');
					}
				}
			});	
}

$(function(){
	
	
	$("#cantidad").keyup(function(){
		
		var stock = ($("#stock").val()!="")?$("#stock").val():0;
		var cant = ($(this).val()!="")?$(this).val():0;
		if($("#tipo").val() == 'Entrada'){
			var total = parseInt(cant) + parseInt(stock); 
			$("#stock_nuevo").val(total);
		}else if($("#tipo").val() == 'Salida'){
			var total =  parseInt(stock) - parseInt(cant);
			$("#stock_nuevo").val(total);
		}else{
			$("#stock_nuevo").val('');
		}
		
	});
	
	$("#tipo").change(function(){
		
		$("#cantidad").keyup();
		
	})
	
	$("#form1").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});
	
	$("#form2").validate({
	  submitHandler:function(form) {
		cargarDatos();
	  }
	});	
	
	
});

var formatNumber = {
 separador: ".", // separador para los miles
 sepDecimal: ',', // separador para los decimales
 formatear:function (num){
 num +='';
 var splitStr = num.split('.');
 var splitLeft = splitStr[0];
 var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
 var regx = /(\d+)(\d{3})/;
 while (regx.test(splitLeft)) {
 splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
 }
 return this.simbol + splitLeft +splitRight;
 },
 new:function(num, simbol){
 this.simbol = simbol ||'';
 return this.formatear(num);
 }
}