var mesa = null;
var pedido = null;
var estado = 0;
var tipop = 0;
var eliminarp = new Array();
var eliminara = new Array();
var guardar = 0;
var finalizar = 0;
var popfinalizar = 0;
var montoInicial = 0;

//Scanner POS
var inputStart, inputStop, firstKey, lastKey, timing, userFinishedEntering;
var minChars = 3;

function inputBlur(){
    clearTimeout(timing);
    if ($("#scanInput").val().length >= minChars){
        userFinishedEntering = true;
        inputComplete();
    }
};

function resetValues() {
    // clear the variables
    inputStart = null;
    inputStop = null;
    firstKey = null;
    lastKey = null;
    // clear the results
    inputComplete();
}

// Assume that it is from the scanner if it was entered really fast
function isScannerInput() {
    return (((inputStop - inputStart) / $("#scanInput").val().length) < 15);
}

// Determine if the user is just typing slowly
function isUserFinishedEntering(){
    return !isScannerInput() && userFinishedEntering;
}

function inputTimeoutHandler(){
    // stop listening for a timer event
    clearTimeout(timing);
    // if the value is being entered manually and hasn't finished being entered
    if (!isUserFinishedEntering() || $("#scanInput").val().length < 3) {
        // keep waiting for input
        return;
    }
    else{
        reportValues();
    }
}

// here we decide what to do now that we know a value has been completely entered
function inputComplete(){
    // stop listening for the input to lose focus
    $("body").off("blur", "#scanInput", inputBlur);
    // report the results
    reportValues();
}

function reportValues() {
	
    if (!inputStart) {
        $("#resultsList").html("");
        $("#scanInput").focus().select();
    } else {
		skuRecuperaId($("#scanInput").val());
        $("#scanInput").focus().select();
        inputStart = null;
    }
}
//Fin code Scanner
$(document).bind('keyup', function(e){
    if(e.which==112) {
      $("#finalizar-2").click();
    }
    if(e.which==113) {
      $("#add_cliente").click();
    }	
    if(e.which==46) {
	  if(popfinalizar!=1){
		$("#cancelar").click();
	  }
    }
});
$(document).ready(function() {
	
	$('html').on("mouseover","body",function(){
		$("[data-toggle='tooltip']").tooltip();
	});
	$("html").on("click",".image-link",function(e){
		e.preventDefault();
	});
	


});
function loadshow(){
	$("#load").show();
}
function loadhide(){
	$("#load").hide();
}

$(function(){	

	$("#quitar-descuento").click(function(e){
		e.preventDefault();
		quitarDescuento();
		
	})
	//Valida Cliente
	$("#form-cliente").validate({
	  submitHandler:function(form) {
		guardarCliente();	  
	  }
	});
	
	$("#form-cliente-sel").validate({
	  submitHandler:function(form) {
		seleccionarCliente();	  
	  }
	});		
	
	//Valida Descuento
	$("#form-descuento").validate({
	  submitHandler:function(form) {
		guardarDescuento();	  
	  }
	});	
	
	//Valida Venta
	$("#form-venta").validate({
	  submitHandler:function(form) {
		finalizarVenta();
	  }
	});	

	//Valida Venta
	$("#frmMontoIncial").validate({
	  submitHandler:function(form) {
		ingresarMontoInicial();
	  }
	});		

	$("input[name='rut']").Rut({
		on_error: function(){ 
			alert('Rut incorrecto');
			$("#rut").val('');
		},
		format_on: 'keyup'
	});
	
    $(".cssload-btn").click(function(e){
		e.preventDefault();
		
		//$('.modal').modal();
		
		var r = confirm("Efectuando apertura de caja ,¿Desea confirmar esta operación?");
		if (r == true) {
			aperturaCaja();
		} else {
			return false;
		}			
		
		
	});
    $("#cerrar-caja").click(function(e){
		e.preventDefault();
		var r = confirm("¿Está seguro que desea cerrar la caja?");
		if (r == true) {
			cierreCaja($(this).attr('alt'));
		} else {
			return false;
		}		
		
		
	});
	
	$('#myModal').on('hidden.bs.modal', function (e) {
		popfinalizar = 0;
		$("#scanInput").focus();
		resetearValorTotal();
	})

	$('#modal_add_cliente').on('hidden.bs.modal', function (e) {
		$("#form-cliente").reset();
	})	
	$('#modal_add_cliente').on('hidden.bs.modal', function (e) {
		$("#form-descuento").reset();
	})
	$('#mdalMontoInicial').on('hidden.bs.modal', function (e) {
		
		if(montoInicial == 0)
			mensajeLoad('Usted no ha ingresado un monto inicial con la apertura de la caja, esta quedará en $0','info');
	
	});	
	$('#myModal').on('shown.bs.modal', function (e) {
		popfinalizar = 1;
		$("#monto_paga").focus();
	})		
	
	$("#scanInput").focus();
	
	$("#scanInput").keypress(function (e) {
		// restart the timer
		if (timing) {
			clearTimeout(timing);
		}
		
		// handle the key event
		if (e.which == 13) {
			e.preventDefault();
			if ($("#scanInput").val().length >= minChars){
				userFinishedEntering = true; // incase the user pressed the enter key
				inputComplete();
				$(this).val('');
			}
		}
		else {
			// some other key value was entered
			
			// could be the last character
			inputStop = performance.now();
			lastKey = e.which;
			
			// don't assume it's finished just yet
			userFinishedEntering = false;
			
			// is this the first character?
			if (!inputStart) {
				firstKey = e.which;
				inputStart = inputStop;
				
				// watch for a loss of focus
				$("body").on("blur", "#scanInput", inputBlur);
			}
			
			// start the timer again
			timing = setTimeout(inputTimeoutHandler, 500);
		}
	});
	// reset the page
	$("#reset").click(function (e) {
		e.preventDefault();
		resetValues();
	});
	
	$(".chosen-select").chosen();
	$('[data-toggle="tooltip"]').tooltip();
	$("#anular").hide();
	
	$( "#productos" ).on("click", ".pro", function(e){
		e.preventDefault();
		agregar($(this).attr("id"),'producto',false);
	});
	
	$( "#productos-2" ).on("change", ".pro", function(e){
		e.preventDefault();
		if($(this).val()!=""){
			agregar($(this).val(),'producto',false);
			$("#select-prod").val('').trigger('chosen:updated');
		}
	});	
	
	//Carga los productos de acuerdo a la CATEGORIA
	$(".categoria-link").click(function(e){
		
		
		e.preventDefault();
		var id = $(this).attr("id");
		
		$.ajax({
			url:'/venta/categoria/'+id+'/',
			method: 'POST',
			async: true,
			dataType: "json",
			beforeSend: function(){
				//$("#productos").html('<div class="col-sm-12 col-md-12 placeholder"><button type="button" class="list-group-item"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>  Cargando productos... </button></div>');
				loadshow();
			},
			success:
			function(response){
				loadhide();
				var productos = '';
				if(response.length == 0){
					productos += '<div class="col-md-12"><button type="button" class="list-group-item">No existen productos para esta categoría</button></div>';
				}
				else {
					$.each(response, function(i, item){
						productos += '<div class="col-md-3"><a href="'+item.imagen+'" class="image-link"><img src="'+item.icono+'" id="'+item.id+'" class="img-responsive thumbnail pro" title="'+item.descripcion+'" style="cursor:pointer; margin-bottom:0;" data-toggle="tooltip" data-placement="bottom"></a><p class="text-center txt-productos">'+item.nombre_corto+'</p></div>';
					});
				}
				$("#productos").html(productos);
			}
		})
	});
	
	
	$("#sel_cliente").click(function(e){
		e.preventDefault();
		$('#modal_listar_cliente').modal();
		
	});	

	$(".pedido").click(function(e){
		
		 
		e.preventDefault();
		//Estado = 1 Cuando actualiza pedido
		//Estado = 0 Inserta pedido (1mera vez)
		
		if(estado != 1){ //Insertando
			if(!$("#pedido tbody tr").hasClass("noPedido")){ // Verificamos que exitan productos
				if(tipop != 1){ //Si es en MESA
					if(mesa != null){
						var total = $("#Total").html().replace('$', '').replace(/\./g, '');
						var desc = ($("#descripcion").val() != '') ? $("#descripcion").val() : 'nulo';
						$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Cargando: Espere por favor...</strong></div>');
						$("#mensaje-load").fadeIn(500);
						
						$.ajax({
							method: "POST",
							url:'/venta/guardar/'+mesa+'/'+total+'/'+desc+'/'+tipop+'/',
							async: true,
							success:
							function(response){
								if(response != 'false'){
									cargar_pedido(response);
								}else{
									$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Cargando: Estamos procesando su solicitud favor espere... </strong></div>');
									$("#mensaje-load").fadeIn(500);									
								}
							}
						})
					}
					else{
						alert("Por favor seleccione una mesa");
					}
				}
				else{ //Si es Domicilio

					var total = $("#Total").html().replace('$', '').replace(/\./g, ''); //obtenemos el total
					var desc = ($("#descripcion").val() != '') ? $("#descripcion").val() : 'nulo'; //Obtenemos la descripcion

					$.ajax({
						method: "POST",
						url:'/venta/guardar/-1/'+total+'/'+desc+'/'+tipop+'/',
						async: true,
						beforeSend:function(){
							$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Guardando pedido...</strong></div>');
							$("#mensaje-load").fadeIn(500);						
						},
						success:
						function(response){
							cargar_pedido(response);
							guardar_domicilio(response);
						}
					})

				}
			}
			else{
				alert("Por favor seleccione productos a pedir");
			}
		}
		else{ // Actualizando Pedido
			if(!$("#pedido tbody tr").hasClass("noPedido")){// Verificamos que exitan productos
				
				
				//Tipop = 1 Pedido Externo
				//Tipop = 0 Pedido Interno
				
				if(tipop != 1){ //Si es Pedido Interno
					
					var total = $("#Total").html().replace('$', '').replace(/\./g, '');	//Formateamos el total Ej: $ 2.000 -> 2000
					var desc = ($("#descripcion").val() != '') ? $("#descripcion").val() : 'nulo'; // Validamos la descripcion
					
					//Cargamos Mensaje
					$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Cargando: Espere por favor...</strong></div>');
					$("#mensaje-load").fadeIn(500);
					
					$.ajax({
						method: "POST",
						url:'/venta/actualizar/'+pedido+'/'+total+'/'+desc+'/'+tipop+'/',
						async: true,
						data:{idmesa:mesa},
						success:
						function(response){
							
							
							actualizar_pedido(pedido);
						
						}
					});
					
				}
				else{

					var total = $("#Total").html().replace('$', '').replace(/\./g, '');
					var desc = ($("#descripcion").val() != '') ? $("#descripcion").val() : 'nulo';
					$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Cargando: Espere por favor...</strong></div>');
					$("#mensaje-load").fadeIn(500);
					$.ajax({
						url:'/venta/actualizar/'+pedido+'/'+total+'/'+desc+'/'+tipop+'/',
						method: "POST",
						async: true,
						success:
						function(response){
							actualizar_pedido(pedido);
							//actualizar_domicilio(pedido);
						}
					});


				}
			}
			else{
				alert("Por favor seleccione productos a pedir");
			}
		}
	});

	$("#txtMontoInicial").keyup(function(){
		
		var monto = ($(this).val()!="") ? parseInt($('#txtMontoInicial').val()):0;
		var monto2 = formatNumber.new(parseInt(monto));
		$("#lblMontoInicial").html('$ '+monto2);
		
	})

	$("#monto_paga").keyup(function(){
		
		var total = parseInt($("#total_a_pagar").val());
		var monto = parseInt($(this).val());
		
		if($(this).val()!=''){
			var vuelto = (monto - total);
		}else{
			var vuelto = 0;
		}
		
		$("#vuelto").val(vuelto);
		var vuelto = parseInt($("#vuelto").val());
		$("#vuelto_2").html('$'+ formatNumber.new(vuelto));
		
		
		
	});
	
	$("#add_cliente").click(function(){
		
		$('#modal_add_cliente').modal();
		
	});
	$("#add_descuento").click(function(){
		
		if(!$("#pedido tbody tr").hasClass("noPedido")){
			cargarDescuentos();
			$('#modal_add_descuento').modal();
			
		}else{
			//alert("Venta no posee productos");
			mensajeLoad('Venta no posee productos','error');
		}		
		
		
	});	
	/*$("#guardar-cliente").click(function(e){
		e.preventDefault();
		guardarCliente();
		//alert("Funcionalidad no disponible");
		
	});*/
	$("#buscar-cliente").click(function(e){
		e.preventDefault();
		alert("Funcionalidad no disponible");
		
	});
	
	$("#finalizar-2").click(function(e){
		e.preventDefault();
		var html="";
		
		$("#monto_paga").val('');
		$("#vuelto").val('');
		$("#vuelto_2").html('');
		
		if(tipop!=2){//Si es distinto de una Nota de credito
			$(".section_monto").show();
			$(".section_vuelto").show();	
		}
		
		$(".section_ticket").hide();	
		$(".section_ticket").val('');	
		$("#finalizar-3").prop('disabled',false);	
		
		
		$("#total_a_pagar").val($("#Total").html().replace('$', '').replace(/\./g, ''));
		$("#total_a_pagar_2").html('$'+formatNumber.new(parseInt($("#total_a_pagar").val())));
		
		$("#subtotal_pagar").val($("#Total").html().replace('$', '').replace(/\./g, ''));
		$("#subtotal_pagar_2").html('$'+formatNumber.new(parseInt($("#total_a_pagar").val())));	
		
		

		
		if(tipop!=2){//Si es distinto de una Nota de credito
		$.ajax({
			url: '/ventas/formas-pago/',
			type: 'POST',
			dataType: 'json',
			async:true,
			beforeSend: function()
			{
						$("#formas_pagos").html('<option value="">Cargando formas de pago...</option>');
			},				
			success: function (data)
			{
				if(data)
				{
					$.each(data, function(key,value)
					{
						html +="<option value='"+value.id+"'>";
						html += value.nombre;
						html += "</option>";
					});
				}else
				{
					html +="<option value=''>No existen resultados</option>"; 
				}
				$("#formas_pagos").html(html);
			}
		});	
		
		}
		
		
		if(!$("#pedido tbody tr").hasClass("noPedido")){
			
			$('#myModal').modal();
			
		}else{
			mensajeLoad('Venta no posee productos','error');
		}
		
	});
	
    $("#formas_pagos").change(function(){
		
		var forma = ($(this).val());
		if(forma != 1){
			$(".section_monto").hide();
			$(".section_vuelto").hide();
			$(".section_ticket").show();
			$("#monto_paga").focus();
			
		}else{
			$(".section_monto").show();
			$(".section_vuelto").show();	
			$(".section_ticket").hide();	
			$("#ticket").focus();
			
		}
		
	});
	
	
	$("#generar").click(function(e){
		// 
		e.preventDefault();
		guardar = 0;
		//$(".pedido").click();
		pedidos();
	});

	$("#guardar").click(function(e){
	
		// 
		e.preventDefault();
		guardar = 1;
		pedidos();
	
	});
	
	$("#anular").click(function(e){
		 
		e.preventDefault();
		var mesita = (tipop == 1) ? -1 : mesa;
		if(confirm("Está seguro de anular la compra?")){
			$.ajax({
				url:'/venta/anular/'+pedido+'/'+mesita+'/',
				method: 'POST',
				async: true,
				dataType: "json",
				beforeSend: function(){
					$("#mensaje-load").html('<div class="alert alert-info alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <strong>Cargando: Espere por favor...</strong></div>');
					$("#mensaje-load").fadeIn(500);
				},
				success:
				function(response){
					$("#mensaje-load").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <strong>&Eacute;xito: Se ha anulado la venta correctamente</strong></div>');
					setTimeout(function() {$("#mensaje-load").fadeOut(1000);},3000);
					$("#pedido tbody").html('<tr class="noPedido"><td colspan="5" align="center">No existe venta en curso</td></tr>');
					$("#descripcion").val('');
					$("#subTotal").html("$0");
					$("#subtotal_a_pagar").val(0);
					$(".total").html("$0");
					$("#productos").html('<div class="col-md-12"><button type="button" class="list-group-item">Por favor seleccione una categoría</button></div>');
					$(".mesa option[value='0']").prop('selected', true);
					$("#pedidoMesa").html('Pedido <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>');
					if(tipop == 1){
						$("#sel-dom").click();
						$("#estado").removeClass("circle-ocupado");
						$("#estado").addClass("circle-libre");
					}
					else{
						$("#sel-mesas").click();
						$("#estado").removeClass("circle-libre");
						$("#estado").removeClass("circle-ocupado");
					}
				}
			})
		}
	});
	
	$("#cancelar").click(function(e){
		 
		e.preventDefault();
		$("#pedido tbody").html('<tr class="noPedido"><td colspan="5" align="center">No existe venta en curso</td></tr>');
		$("#descripcion").val('');
		$("#subTotal").html("$0");
		$("#subtotal_a_pagar").val(0);
		$(".total").html("$0");
		$("#total_a_pagar").val(0);
		$("#productos").html('<div class="col-md-12"><button type="button" class="list-group-item">Por favor seleccione una categoría</button></div>');
		
		//Limpiar Descuentos
		quitarDescuento();
		
		//Limpiar Cliente
		$("#rut_cliente_seleccionado").val('')
		$("#cliente-sel").html('No hay cliente seleccionado');
		
	});

	$("#search-table").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#my-table tbody>tr").hide();
			$("#my-table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#my-table tbody>tr").show();
		}
	});


	$("#pedido").on('change','.input_cantidad',function(e){
			var aux = $(this).attr('id').split('_');
			agregar(aux[2],'producto',true);
	})	
	//Valida entradas enteras
	$('#pedido').on('keyup','.solo-numero',function (){
		this.value = (this.value + '').replace(/[^0-9]/g, '');
	});	
	
	$("#tipo_venta").change(function(){
		
		tipop = $(this).val();
		
		if(tipop!=0){

			$("#venta_title").html("Finalizar Nota de Credito");
			$("#total_pagar_title").html("TOTAL NOTA DE CREDITO");
			$(".section_formas").hide();
			$(".section_monto").hide();
			$(".section_vuelto").hide();
			
		}else{
			$("#venta_title").html("Finalizar Venta");
			$("#total_pagar_title").html("TOTAL A PAGAR");
			$(".section_formas").show();
			$(".section_monto").show();
			$(".section_vuelto").show();
		}
		
	})
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

function resetearValorTotal(){
	$("#total_a_pagar").val($("#Total").html().replace('$', '').replace(/\./g, ''));
	$("#total_a_pagar_2").html('$'+formatNumber.new(parseInt($("#total_a_pagar").val())));
}

function finalizarVenta(){
    $("#finalizar-3").prop('disabled',true);
	
	var forma = $("#formas_pagos").val();
	
	var total = parseInt($("#total_a_pagar").val());
	var monto = parseInt($('#monto_paga').val());		
	
		if(forma == 1){//Pago Efectivo
			if( total > monto){
				alert("El 'EFECTIVO' debe ser mayor que el 'TOTAL A PAGAR'");
				$("#finalizar-3").prop('disabled',false);
				$("#monto_paga").focus();
			}else{
				finalizarPedido();
			}
		}else if(forma!=""){
				
			finalizarPedido();	
		
		}else{ //Pago TBK Deb. o Cred.
			if(validaCampo('ticket')){
				finalizarPedido();
			}else{
				$("#finalizar-3").prop('disabled',false);
			}
		}

}
function finalizarPedido(){
		guardar = 0;
		finalizar = 1;
		if(tipop==2){
			var forma = 0;
		}else{
			var forma = $("#formas_pagos").val();
		}
		var ticket = ($("#ticket").val()!="")?$("#ticket").val():"null";
		
		if(estado != 1){
			if(!$("#pedido tbody tr").hasClass("noPedido")){
				
				
				var total = $("#total_a_pagar").val();
				var subtotal = $("#subtotal_a_pagar").val();
				var descuento = $("#descuentos_aplicados").val();//Valor descuentos Aplicados
				var id_caja = $("#cerrar-caja").attr('alt');//Codigo de la session caja
				var cliente = ($('#rut_cliente_seleccionado').val()!='')?$('#rut_cliente_seleccionado').val():'';
				
				$.ajax({
					method: "POST",
					url:'/venta/finalizar/',
					data:{'total':total,'tipo':tipop,'cliente':cliente,'forma': forma,'ticket':ticket,'subtotal':subtotal,'descuento':descuento,'id_caja':id_caja},
					async: true,							
					beforeSend:function(){
						loadshow();
					},
					success:
					function(response){
						loadhide();
						cargar_pedido(response.resultado);
						
						//Mensaje
						if(tipop!=2){
							mensajeLoad('&Eacute;xito: venta generada correctamente','ok');
						}else{
							mensajeLoad('&Eacute;xito: Nota de credito correcta','ok');
						}
					}
				});
			}
			else{
				mensajeLoad('Venta no posee productos','error');
				//alert("Venta no posee productos");
			}
		}	
	
}

function format(input){
	var num = input;
	if(!isNaN(num)){
		num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
		num = num.split('').reverse().join('').replace(/^[\.]/,'');
		return num;
	}
	else{ 
		alert('Solo se permiten numeros');
		input.value = input.value.replace(/[^\d\.]*/g,'');
	}
}

//Agrega el producto a la venta
function agregar(producto, tipo, manual){
	var id = producto;
	var tproducto = 'producto';
	
	$.ajax({
		url:'/venta/obtener/'+id+'/'+tipo+'/',
		method: 'POST',
		async: true,
		dataType: "json",
		beforeSend: function(){ 
			loadshow();
		},		
		success:
		function(response){
			loadhide();
			
			if(response!=""){
				var producto = '';
				var subTotal = 0;
				var stock =  parseInt(response[0].stock);
				var cant_venta = 0;
				var dif = 0;
				var error = 0;
				
				if(stock > 0){
				//Recorre el elemento agreegado y aumenta stock en la venta.
				$.each(response, function(i, item){
					
					
					if(manual){
						$("#cantidad_"+tproducto+'_'+item.id).attr('max',stock);
						cant_venta = parseInt($("#cantidad_"+tproducto+'_'+item.id).val()); 
						dif = (stock - cant_venta); 
						if(dif >= 0){
							var cantidad = ($("#cantidad_"+tproducto+'_'+item.id).val());
							$("#cantidad_"+tproducto+'_'+item.id).val(cantidad);
							$("."+tproducto+'_'+item.id).children("td:nth-child(4)").html("$"+formatNumber.new(cantidad*item.valor));
						}else{
							error = 1;
						}
					}
					else if($("#pedido tbody tr").hasClass(tproducto+'_'+item.id)){
						$("#cantidad_"+tproducto+'_'+item.id).attr('max',stock);
						
						cant_venta = parseInt($("#cantidad_"+tproducto+'_'+item.id).val());
						dif = (stock - cant_venta); 
						if(dif > 0){
							var cantidad = ($("#cantidad_"+tproducto+'_'+item.id).val());
							cantidad++;
							$("#cantidad_"+tproducto+'_'+item.id).val(cantidad);
							$("."+tproducto+'_'+item.id).children("td:nth-child(4)").html("$"+formatNumber.new(cantidad*item.valor));
						}else{
							error = 1;
						}
					}
					else{
						producto += '<tr class="'+tproducto+'_'+item.id+'"><td><input id="cantidad_'+tproducto+'_'+item.id+'" min="1" max="'+stock+'" onKeyPress="return soloNumeros(event)" class="form-control input-lg text-center input_cantidad" type="number" value="1"></td><td style="text-align:left !important;">'+'[<b>'+item.sku+'</b>] '+item.nombre+'</td><td>$'+formatNumber.new(item.valor)+'</td><td>$'+formatNumber.new(item.valor)+'</td><td align="center"><a href="#" title="Eliminar" class="eliminar" onClick="eliminar(this);"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td></tr>';
					}
				});
				
				if(error!=1){
					//Valida si existe pedido en curso para pegar HTML de forma append o directa.
					if($("#pedido tbody tr").hasClass("noPedido")){
						$("#pedido tbody").html(producto);
					}else{
						$("#pedido tbody").append(producto);
					}
				
					//Actualiza los valores en el total de la venta.
					$("#pedido tbody").find('tr').each(function(){
						subTotal += parseInt($('td:nth-child(1) input[type=number]',this).val())*parseInt($(this).children("td:nth-child(3)").text().replace('$', '').replace(/\./g, ''));
					});
					
					//Actualiza los valores de la venta
					actualizarValoresVenta(subTotal);
					
				}else{
					$('#cantidad_'+tproducto+'_'+response[0].id).val(stock);	
					mensajeLoad('No posee stock disponible','error');
				}
				}else{
					mensajeLoad('No posee stock disponible','error');					
					
				}
			}else{	
					mensajeLoad('Producto no se encuentra o no dispone de stock','error');
				
				
			}
		}
	});
}

function actualizaCantidadProductos(){
	var cant = 0;
	
	
	$("#pedido > tbody > tr > td input[type=number]").each(function(){
		cant= cant + parseInt($(this).val());
	});
	$('#cantidad_productos').html(cant);
	cantidad_productos =  cant;	
	//alert('actualice');
}
function seleccionarCliente()
{
	var datos = $("#form-cliente-sel").serialize();
	//var datos = new FormData($(".form1")[0]);

	$.ajax
	({
		url:'/clientes/obtener/',
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
			if(response.rut){
				$("#form-cliente-sel").reset();
				$('#modal_listar_cliente').modal('hide');
				
				
				if(!$("#pedido tbody tr").hasClass("noPedido")){
					if(response.vendedor == 1 ){
						var r = confirm('Se ha detectado que este cliente se registró como "Vendedor", se borrará la venta en curso?\n¿Desea efectuar esta acción?');
						if(r){
							$('#cliente-sel').html(response.nombre + '('+response.rut+')');
							$('#rut_cliente_seleccionado').val(response.rut);
							limpiar();
							valor_mayor = 1;
						}

					}else{
							$('#cliente-sel').html(response.nombre + '('+response.rut+')');
							$('#rut_cliente_seleccionado').val(response.rut);						
					}
				}else{
					$('#cliente-sel').html(response.nombre + '('+response.rut+')');
					$('#rut_cliente_seleccionado').val(response.rut);
					valor_mayor = 1;
				}
				
				
			}else{
				$("#mensaje-load").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class=" glyphicon glyphicon-remove" aria-hidden="true"></span><strong> Error:</strong>Cliente no existe</div>');	
				$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);
			}
			
			
		}
	});	
}


function cargar_pedido(ped_id){
	var nro = $("#pedido tbody").find('tr').length;
	var i = 0;
	
	$("#pedido tbody").find('tr').each(function(){
		var clase = $(this).attr("class").split("_");
		var id = clase[1]; //Id del producto
		var tipo = clase[0]; // Tipo Servicio o Producto
		var cantidad = $('td:nth-child(1) input[type=number]',this).val();//$(this).children("td:nth-child(1)").text();
		var valor = $(this).children("td:nth-child(4)").text().replace('$', '').replace(/\./g, '');
		if(clase[0] == 'producto'){
			$.ajax({
				url:'/venta/almacenarpro/'+id+'/'+ped_id+'/'+cantidad+'/'+valor+'/',
				data:{'tipo':tipop},
				method: 'POST',
				async: true,
				dataType: "json",
				success:
				function(response){
					i++;
					if(i == nro){
						$('#myModal').modal('hide');
						mensajeLoad('&Eacute;xito: Se ha Finalizado la venta correctamente','ok');
						$("#cancelar").click();
						if(guardar != 1){
							imprimir(ped_id,tipop);
						}
					}
				}
			});
		}
	});
}

function eliminar(input){
	var subTotal = 0;
	var clase = $(input).parent().parent().attr("class");
	var tipo = clase.split("_");
	var cantidad = parseInt($("#cantidad_"+clase).val());
	
		if(estado == 1){
			var id = $(input).parent().parent().attr("id");
			if(tipo[0] == 'producto'){
				eliminarp.push(id);
			}
			else{
				eliminara.push(id);
			}
			$("."+clase).remove();
		}
		else{
			$("."+clase).remove();
		}
	if($("#pedido tbody").find('tr').length != 0){//Si tiene productos entra
		$("#pedido tbody").find('tr').each(function(){
			subTotal += parseInt($('td:nth-child(1) input[type=number]',this).val())*parseInt($(this).children("td:nth-child(3)").text().replace('$', '').replace(/\./g, ''));
		});
		actualizarValoresVenta(subTotal);
	}
	else{ // si no hay productos
		$("#pedido tbody").html('<tr class="noPedido"><td colspan="5" align="center">No existe venta en curso</td></tr>');
		actualizarValoresVenta(0);
	}
}


function skuRecuperaId(sku){
	
		$.ajax({
			url:'/productos/obtener-sku/',
			method: 'POST',
			async: true,
			dataType: "json",
			data: {'sku':sku},
			beforeSend:
			function(){
				loadshow();				
			},
			success:
			function(response){
				loadhide();	
				if(response.id!=false){
					agregar(response.id,'producto',false);
				}else{
					$("#mensaje-load").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <strong>Producto inexistente o fuera de Stock, verifique</strong></div>');
					$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);
				}
			}
		})
	
	
}

function validaCampo(id){
	
	var valor = $("#"+id).val();
	
	if(valor!=""){
		return true;
	}else{
		alert("Ingrese N° ticket Transbank");
		$("#"+id).focus();
		return false;
	}
	
}

function cargarDescuentos(){
		var html='';
		
		$.ajax({
			url: '/productos/descuento/listar-select/',
			type: 'POST',
			dataType: 'json',
			async:true,
			beforeSend: function()
			{
						$("#descuentos").html('<option value="">Cargando descuentos...</option>');
			},				
			success: function (data)
			{	
				if(data!="")
				{
					html +="<option value='' selected >Seleccione Descuento</option>"; 
					$.each(data, function(key,value)
					{
						html +="<option value='"+value.id+"' alt='"+value.tipo+"-"+value.valor+"'>";
						html += value.descripcion;
						html += "</option>";
					});
				}else
				{
					html +="<option value=''>No existen resultados</option>"; 
				}
				$("#descuentos").html(html);
			}
		});	
}

function aperturaCaja(){
		$.ajax({
			url: '/venta/apertura-caja/',
			type: 'POST',
			dataType: 'json',
			async:true,
			beforeSend: function()
			{
						loadshow();	
			},				
			success: function (data)
			{
					loadhide();	
					if(!isNaN(data.resultado)){					
						
						modalIngresoMontoInicial();
						//var monto  =  ingresarMontoInicial();
						
						mensajeLoad('Apertura correcta','ok');
						$("#caja-lock").hide();
						//Boton Cerrar Caja
						$("#cerrar-caja").attr('alt',data.resultado);
						$("#cerrar-caja").show();
						
						$("#detalle-caja").html('<p style="margin-bottom:0;">APERTURA CAJA: <strong>'+ data.caja_inicio +' hrs.</strong> USUARIO: <strong>'+ data.usuario +'</strong></p>');
						
					}else{						
						mensajeLoad('Error en la apertura de la caja','error');
					}
				
			}
		});			
}

function modalIngresoMontoInicial(){
	$("#mdalMontoInicial").modal();
}

function ingresarMontoInicial()
{
		var monto = ($('#txtMontoInicial').val()!="") ? parseInt($('#txtMontoInicial').val()):0;
		var monto2 = formatNumber.new(parseInt(monto));
		$.ajax({
			url: '/venta/ingresar-saldo/',
			type: 'POST',
			dataType: 'json',
			data: {'monto':monto,'id':$("#cerrar-caja").attr('alt')},
			async:true,
			beforeSend: function()
			{
						loadshow();	
			},				
			success: function (data)
			{
					loadhide();	
					if(data.rs){					
						montoInicial = 1; //Esta variable sirve para no mostrar el mensaje al cerrar el modal
						$("#mdalMontoInicial").modal('hide');
						$('#txtMontoInicial').val('');
						$('#lblMontoInicial').html('');
						mensajeLoad('Monto Inicial ingresado correctamente, $'+ monto2 ,'ok');
					}else{				
						montoInicial = 1; //Esta variable sirve para no mostrar el mensaje al cerrar el modal
						$("#mdalMontoInicial").modal('hide');
						mensajeLoad('Error en el ingreso del Monto Inicial, quedará en $0 la apertura','error');
					}
				
			}
		});	
}
function cierreCaja($id_caja){
	
	
		
		$.ajax({
			url: '/venta/cierre-caja/',
			type: 'POST',
			data:{'id_caja':$id_caja},
			dataType: 'json',
			async:true,
			beforeSend: function()
			{
						loadshow();	
			},				
			success: function (data)
			{
					loadhide();	
					if(!isNaN(data.resultado)){					
						$("#mensaje-load").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <strong>&Eacute;xito: Cierre de caja correcto</strong></div>');
						$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);	
						$("#caja-lock").show();
						
						$("#cerrar-caja").attr('alt',0);
						$("#cerrar-caja").hide();	

						$("#detalle-caja").html('<p style="margin-bottom:0;">INICIE APERTURA CAJA</p>');
						
					}else{
						$("#mensaje-load").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <strong>Error en el cierre de la caja</strong></div>');
						$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);							
					}
				
			}
		});			
}
function guardarCliente()
{
	var datos = $("#form-cliente").serialize();
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
				$("#mensaje-load").html('<div class="alert alert-success alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><strong> Éxito: </strong> Se ha insertado correctamente</div>');
				$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);
				$("#form-cliente").reset();
				$('#modal_add_cliente').modal('hide');
			}else{
				$("#mensaje-load").html('<div class="alert alert-danger alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button><span class=" glyphicon glyphicon-remove" aria-hidden="true"></span><strong> Error:</strong>'+response.msg+'</div>');	
				$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);
			}
			
			
		}
	});	
}
function guardarDescuento()
{
		var d1  = $("option:selected",'#descuentos').attr('alt');
		var d2 = d1.split('-');
		var total_descuento = 0;
		var total = parseInt($("#subtotal_a_pagar").val());
		
		var tipo = d2[0];
		var monto = parseInt(d2[1]);
		
		
		if(tipo == '%'){
			 total_descuento =  ((total * monto) / 100);
		}else if(tipo == '$'){
			 total_descuento =  monto;
		}else{
			alert("Formato no implementado");
		}
		
		$("#descuentos_aplicados").val(total_descuento);
		$("#descuentos_aplicados_2").html('- $'+formatNumber.new(parseInt($("#descuentos_aplicados").val())));			
		
		
		var final_total = (parseInt($("#subtotal_a_pagar").val()) - parseInt(total_descuento));
		
		$("#total_a_pagar").val(final_total);
		$("#Total").html('$'+formatNumber.new(parseInt(final_total)));	
		
		$('#modal_add_descuento').modal('hide');
}

function soloNumeros(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function actualizarValoresVenta(valor){
	
	$("#subTotal").html("$"+formatNumber.new(valor));
	$("#subtotal_a_pagar").val(valor);
	$(".total").html("$"+formatNumber.new(valor));
	$("#total_a_pagar").val(valor);
	quitarDescuento();
	
}
/*function quitarDescuento(){
	$("#descuentos_aplicados").val(0);
	$("#descuentos_aplicados_2").html('$0');
	
}*/
function quitarDescuento(){
	$("#descuentos_aplicados").val(0);
	$("#descuentos_aplicados_2").html('$0');
	//El total será igual al subtotl al quitar el descuento
	$("#total_a_pagar").val($('#subtotal_a_pagar').val());
	$("#Total").html($('#subTotal').html());	
}
function limpiar(){
	$("#pedido tbody").html('<tr class="noPedido"><td colspan="6" align="center">No existe venta en curso</td></tr>');
	$("#subTotal").html("$0");
	$("#subtotal_a_pagar").val(0);
	$(".total").html("$0");
	$("#total_a_pagar").val(0); 
	$("#productos").html('<div class="col-md-12"><button type="button" class="list-group-item">Por favor seleccione una categoría</button></div>');	
}
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
jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}


    function show5(){
        if (!document.layers&&!document.all&&!document.getElementById)
        return

         var Digital=new Date()
         var hours=Digital.getHours()
         var minutes=Digital.getMinutes()
         var seconds=Digital.getSeconds()

        var dn="PM"
        if (hours<12)
        dn="AM"
        if (hours>12)
        hours=hours-12
        if (hours==0)
        hours=12

         if (minutes<=9)
         minutes="0"+minutes
         if (seconds<=9)
         seconds="0"+seconds
        //change font size here to your desire
        myclock="<font style='white-space:nowrap;' ><b>"+hours+":"+minutes+":"
         +seconds+" "+dn+"</b></font>"
        if (document.layers){
        document.layers.liveclock.document.write(myclock)
        document.layers.liveclock.document.close()
        }
        else if (document.all)
        liveclock.innerHTML=myclock
        else if (document.getElementById)
        document.getElementById("liveclock").innerHTML=myclock
        setTimeout("show5()",1000)
         }


        window.onload=show5


