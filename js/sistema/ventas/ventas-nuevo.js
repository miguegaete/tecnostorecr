var tipop = 0;		
		
	$(function(){
		
		$("#scanInput").focus();
		//Funciones GENERALES
		
		$('html').on("mouseover","body",function(){
			$("[data-toggle='tooltip']").tooltip();
		});
		$("html").on("click",".image-link",function(e){
			e.preventDefault();
		});		
		
		//Capturar Tecla de la entrada de codigo
		$("#scanInput").keypress(function(e){
			if (e.which == 13) {
				e.preventDefault();
				if($(this).val() != ""){
					
					if(!isNaN($(this).val())){
						obtenerProducto($(this).val(),false);
					}else{
						mensajeLoad('Debe ingresar un código válido.', 'error');
					}
				}else{
					
					mensajeLoad('Debe ingresar un código válido.', 'error');
					
				}
			}
		});
		
		//Descuentos
		
		
		$("#add_descuento").click(function(){

			if(productosExisten()){
				cargarDescuentos();
				$('#modal_add_descuento').modal();
				
			}else{
				//alert("Venta no posee productos");
				mensajeLoad('Venta no posee productos','error');
			}		


		});			
		//Valida Descuento
		$("#form-descuento").validate({
		  submitHandler:function(form) {
			guardarDescuento();	  
		  }
		});		
		
		$("#quitar-descuento").click(function(e){
			e.preventDefault();
			quitarDescuento();
			
		});		
		
		
		
		//VENTAS		
		
		//Valida Venta
		$("#form-venta").validate({
		  submitHandler:function(form) {
			finalizarVenta();
		  }
		});	
	
		$('#productos-venta').on('keyup','*[id*=cantidad_producto_]',function (){
			var claseProducto = $(this).attr("id");
			var idClase = claseProducto.split("_");
			obtenerProducto(obtenerSKUHTML(idClase[2]),true);
			
		});	
	
		$("#productos-venta").on('change','*[id*=cantidad_producto_]',function(e){
			var claseProducto = $(this).attr("id");
			var idClase = claseProducto.split("_");
			obtenerProducto(obtenerSKUHTML(idClase[2]),true);
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
			
                            alert('entro')
                            cargarFormasPago();
			
			}
			
			
			if(productosExisten()){
				
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
		
		$("#tipo_venta").change(function(){
			
			tipop = $(this).val();
			
			if(tipop!=0){
                                cambiarNotaCredito();
			}else{
                                cambiarVenta();
			}
			
		});
		
		$("#cancelar").click(function(e){
			 
			e.preventDefault();
			$("#productos-venta tbody").html('<tr class="noVenta"><td colspan="7" align="center">No existe venta en curso</td></tr>');
			$("#descripcion").val('');
			$("#subTotal").html("$0");
			$("#subtotal_a_pagar").val(0);
			$(".total").html("$0");
			$("#total_a_pagar").val(0);
                        $("#descuentos_aplicados_2").html('$0');
                        $("#descuentos_aplicados").val(0);
			$("#s").html('<div class="col-md-12"><button type="button" class="list-group-item">Por favor seleccione una categoría</button></div>');
			
			//Limpiar Descuentos
			//quitarDescuento();
			
			//Limpiar Cliente
			$("#rut_cliente_seleccionado").val('');
			$("#tipo_venta").val('0').trigger('chosen:updated');
			
			$("#cliente-sel").html('No hay cliente seleccionado');
			
			$("#cantidad-productos").html("0");
                        
                        //Reestablecer popup venta finalizar
                        cambiarVenta();
			
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
		$('#myModal').on('hidden.bs.modal', function (e) {
			popfinalizar = 0;
			$("#scanInput").focus();
			resetearValorTotal();
		});
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
		});			
		$("#frmMontoIncial").validate({
		  submitHandler:function(form) {
			ingresarMontoInicial();
		  }
		});	
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
		
		
		//Clientes
		$("#sel_cliente").click(function(e){
			e.preventDefault();
			$('#modal_listar_cliente').modal();

		});
		
		$("#form-cliente-sel").validate({
		  submitHandler:function(form) {
			seleccionarCliente();	  
		  }
		});		
		$("input[name='rut']").Rut({
			on_error: function(){ 
			alert('Rut incorrecto');
			$("#rut").val('');
			},
			format_on: 'keyup'
		});
		$("#form-cliente").validate({
		  submitHandler:function(form) {
			guardarCliente();	  
		  }
		});
		$("#add_cliente").click(function(){
			
			$('#modal_add_cliente').modal();
			
		});
		//Fin Clientes


		//Productos
		$(".categoria-link").click(function(e){
			
			
			e.preventDefault();
			var id = $(this).attr("id");
			
			$.ajax({
				url:'/venta/categoria/'+id+'/',
				method: 'POST',
				async: true,
				dataType: "json",
				beforeSend: function(){
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
							productos += '<div class="col-md-3"><a href="'+item.imagen+'" class="image-link"><img src="'+item.icono+'" id="'+item.sku+'" class="img-responsive thumbnail pro" title="'+item.descripcion+'" style="cursor:pointer; margin-bottom:0;" data-toggle="tooltip" data-placement="bottom"></a><p class="text-center txt-productos">'+item.nombre_corto+'</p></div>';
						});
					}
					$("#productos").html(productos);
				}
			})
		});		
		
		$( "#productos-2" ).on("change", ".pro", function(e){
			e.preventDefault();
			if($(this).val()!=""){
				obtenerProducto($(this).val(),false);
				$("#select-prod").val('').trigger('chosen:updated');
			}
		});			
		$( "#productos" ).on("click", ".pro", function(e){
			e.preventDefault();
			obtenerProducto($(this).attr("id"));
		});		
	
		$(".chosen-select").chosen();
		$('[data-toggle="tooltip"]').tooltip();	
	});
	
	
	/*************************************************************************ACCIONES GENERALES ************************************/
	
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
	/**********************************************************************FIN ACCIONES GENERALES ************************************/
		

	/******************************************************************************VENTA**********************************************/
	//Obtenemos los datos del producto en base al SKU
	function obtenerProducto(sku,manual = false){
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
				$('#scanInput').val('');
				loadhide();	
				if(response.id!=false){
					
					agregarProductoaVenta(response, manual);
					
				}else{
					mensajeLoad('Producto inexistente o fuera de Stock, verifique', 'error');
					
				}
			}
		});
	}
	function agregarProductoaVenta(datosProducto, manual = false){
		
		//Validamos si el producto es con o sin STOCK
		if(datosProducto.manejostock ==  1){
			if(datosProducto.stock > 0){
				agregarItemTablaConStock(datosProducto, manual);	
			}else{
				mensajeLoad("Este producto no tiene stock, favor ingrese un movimiento o una compra de este producto.","error");
			}
		}else{
				agregarItemTablaSinStock(datosProducto);
		}
		$("#cantidad-productos").html(cantidadProductosVenta());
	}		
	function agregarItemTablaConStock(datosProducto, manual = false){
		
			var cantProdVenta = 0;
			var dif = 0;
		
			var prodExiste = productoExiste(datosProducto.id);
			var valorProducto = (datosProducto.activar_vr == 1)? datosProducto.valor_rebaja:datosProducto.valor;
			var descuento = (datosProducto.activar_vr == 1)? (datosProducto.valor-datosProducto.valor_rebaja) : 0;
			var valorPrimario = datosProducto.valor;
			var html = '';
			
			if(prodExiste){
				
				$("#cantidad_producto_"+datosProducto.id).attr('max',datosProducto.stock);
				cantProdVenta = parseInt($("#cantidad_producto_"+datosProducto.id).val());
				dif = (datosProducto.stock - cantProdVenta); 
				
				
				if(dif > 0){
					var cantidad = ($("#cantidad_producto_"+datosProducto.id).val());
					if(!manual){
						cantidad++;
					}
					$("#cantidad_producto_"+datosProducto.id).val(cantidad);
					actualizarSubtotalProducto(datosProducto.id,(cantidad * valorPrimario));
					actualizarTotalProducto(datosProducto.id,(cantidad * valorProducto));
					actualizarDescuentoProducto(datosProducto.id,(cantidad * descuento));
				}				
				
				
			}else{
				
				html += '<tr class="producto_'+datosProducto.id+'">';
				html += '<td>';
				html += '<input id="cantidad_producto_'+datosProducto.id+'" min="1" max="'+datosProducto.stock+'" onKeyPress="return soloNumeros(event)" class="form-control input-lg text-center input_cantidad" type="number" value="1">';
				html += '</td>';
				html += '<td style="text-align:left !important;">'+datosProducto.sku+'</td>';
				html += '<td style="text-align:left !important;">'+datosProducto.nombre+'</td>';
				html += '<td>$'+formatNumber.new(valorPrimario)+'</td>';
				html += '<td>$'+formatNumber.new(descuento)+'</td>';
				html += '<td>$'+formatNumber.new(valorProducto)+'</td>';
				html += '<td align="center">';
				html += '<a href="javascript:void(0)" title="Eliminar" class="eliminar" onClick="eliminarProductoVenta(this);">';
				html += '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
				html += '</a>';
				html += '<a href="javascript:void(0)" title="Descuento Manual" class="descuentoManual" onClick="descuentoManual(this);">';
				html += '<span class="glyphicon glyphicon glyphicon-usd" aria-hidden="true"></span>';
				html += '</a>';				
				html += '</td>';
				html += '</tr>';
				
			}
			
			if(!productosExisten()){
				$("#productos-venta tbody").html(html);
			}else{
				$("#productos-venta tbody").append(html);
			}			
			actualizarValoresVenta();
	}	
	function actualizarTotalProducto(idProducto,valor){
		$(".producto_"+idProducto).children("td:nth-child(6)").html("$"+formatNumber.new(valor));
	}
	function actualizarDescuentoProducto(idProducto,valor){
		$(".producto_"+idProducto).children("td:nth-child(5)").html("$"+formatNumber.new(valor));
	}	
	function actualizarSubtotalProducto(idProducto,valor){
		$(".producto_"+idProducto).children("td:nth-child(4)").html("$"+formatNumber.new(valor));
	}		
	function agregarItemTablaSinStock(datosProducto){
		
			var cantProdVenta = 0;
		
			var prodExiste = productoExiste(datosProducto.id);
			var valorProducto = (datosProducto.activar_vr == 1)? datosProducto.valor_rebaja:datosProducto.valor;
			var valorPrimario = datosProducto.valor;
			var descuento = (datosProducto.activar_vr == 1)? (datosProducto.valor-datosProducto.valor_rebaja) : 0;
			var html = '';
			
			if(prodExiste){
				var cantidad = ($("#cantidad_producto_"+datosProducto.id).val());
				cantidad++;
				$("#cantidad_producto_"+datosProducto.id).val(cantidad);
				actualizarSubtotalProducto(datosProducto.id,(cantidad * valorPrimario));
				actualizarTotalProducto(datosProducto.id,(cantidad * valorProducto));
				actualizarDescuentoProducto(datosProducto.id,(cantidad * descuento));
				
			}else{
				
				html += '<tr class="producto_'+datosProducto.id+'">';
				html += '<td>';
				html += '<input id="cantidad_producto_'+datosProducto.id+'" min="1" max="'+datosProducto.stock+'" onKeyPress="return soloNumeros(event)" class="form-control input-lg text-center input_cantidad" type="text" value="1" readonly="readonly" >';
				html += '</td>';
				html += '<td style="text-align:left !important;">'+datosProducto.sku+'</td>';
				html += '<td style="text-align:left !important;">'+datosProducto.nombre+'</td>';
				html += '<td>$'+formatNumber.new(valorPrimario)+'</td>';
				html += '<td>$'+formatNumber.new(descuento)+'</td>';
				html += '<td>$'+formatNumber.new(valorProducto)+'</td>';
				html += '<td align="center">';
				html += '<a href="javascript:void(0)" title="Eliminar" class="eliminar" onClick="eliminarProductoVenta(this);">';
				html += '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
				html += '</a>';
				html += '</td>';
				html += '</tr>';
				
			}
			
			if(!productosExisten()){
				$("#productos-venta tbody").html(html);
			}else{
				$("#productos-venta tbody").append(html);
			}			
			
			actualizarValoresVenta();
	}		
	function productoExiste(idProducto){
		
		if($("#productos-venta tbody tr").hasClass('producto_'+idProducto)){
			return true;
		}else{
			return false;
		}
		
	}
	function productosExisten(){
		if($("#productos-venta tbody tr").hasClass("noVenta")){		
			return false;
		}else{
			return true;
		}
	}
	function obtenerSKUHTML(idProducto){
		return $(".producto_"+idProducto).children("td:nth-child(2)").html();
	}
	function eliminarProductoVenta(input){
		var clase = $(input).parent().parent().attr("class");
		$("."+clase).remove();
		if(!productosExisten()){
			$("#productos-venta tbody").html('<tr class="noVenta"><td colspan="5" align="center">No existe venta en curso</td></tr>');
		}
		actualizarValoresVenta();
		$("#cantidad-productos").html(cantidadProductosVenta());
	}
	function actualizarValoresVenta(){
		var total = 0;
		var subtotal = 0;
		var descuento = 0;
		
		if(productosExisten()){
			
			//Total
			$("#productos-venta tbody").find('tr').each(function(){
				total += parseInt($(this).children("td:nth-child(6)").text().replace('$', '').replace(/\./g, ''));
			});	
			
			//Subtotal
			$("#productos-venta tbody").find('tr').each(function(){
				subtotal += parseInt($(this).children("td:nth-child(4)").text().replace('$', '').replace(/\./g, ''));
			});	

			//Descuentos
			$("#productos-venta tbody").find('tr').each(function(){
				descuento += parseInt($(this).children("td:nth-child(5)").text().replace('$', '').replace(/\./g, ''));
			});				
			
			
			
		}
		
		$("#subTotal").hide().html("$"+formatNumber.new(subtotal)).fadeIn('slow');
		$("#subtotal_a_pagar").val(subtotal);		
		
		$("#descuentos_aplicados_2").hide().html("$"+formatNumber.new(descuento)).fadeIn('slow');
		$("#descuentos_aplicados").val(descuento);
		
		$(".total").hide().html("$"+formatNumber.new(total)).fadeIn('slow');
		$("#total_a_pagar").val(total);
		
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
							
                                                    mensajeLoad("Cierre de caja correcto",'ok');
							$("#caja-lock").show();
							
							$("#cerrar-caja").attr('alt',0);
							$("#cerrar-caja").hide();	

							$("#detalle-caja").html('<p style="margin-bottom:0;">INICIE APERTURA CAJA</p>');
							
						}else{
							mensajeLoad("Error en el cierre de la caja","error");
					
						}
					
				}
			});			
	}
	function resetearValorTotal(){
		$("#total_a_pagar").val($("#Total").html().replace('$', '').replace(/\./g, ''));
		$("#total_a_pagar_2").html('$'+formatNumber.new(parseInt($("#total_a_pagar").val())));
	}
	function ingresarMontoInicial(){
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
	function configurarImprimir(){
		var retorno = 0;
		
			$.ajax({
				url: '/configurar/imprimir-venta/',
				type: 'POST',
				dataType: 'json',
				async:false,
				beforeSend: function()
				{
							loadshow();	
				},				
				success: function (data)
				{
						loadhide();
						
						retorno = data[0].imprimir_boleta;
						
					
				}
			});		
		return retorno;	
	}	
	function modalIngresoMontoInicial(){
		$("#mdalMontoInicial").modal();
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
			
			if(productosExisten()){
				
				
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
	function cantidadProductosVenta(){
		return $("#productos-venta tbody").find('tr').length;
	}
	function cargar_pedido(ped_id){
		var nro = cantidadProductosVenta();
		var i = 0;
		
		$("#productos-venta tbody").find('tr').each(function(){
			var clase = $(this).attr("class").split("_");
			var id = clase[1]; //Id del producto
			var cantidad = $('td:nth-child(1) .input_cantidad',this).val();
			var valor = $(this).children("td:nth-child(6)").text().replace('$', '').replace(/\./g, '');
			var descuento = $(this).children("td:nth-child(5)").text().replace('$', '').replace(/\./g, '');

			$.ajax({
				url:'/venta/almacenarpro/',
				data:{'tipo':tipop,'id':id,'pedido':ped_id,'cantidad':cantidad,'valor':valor,'descuento':descuento},
				method: 'POST',
				async: true,
				dataType: "json",
				success:
				function(response){
					i++;
					if(i == nro){
						$('#myModal').modal('hide');
						mensajeLoad('Se ha Finalizado la venta correctamente','ok');
						$("#cancelar").click();
						if(guardar != 1){
							if(configurarImprimir() == 1){
								imprimir(ped_id,tipop);
							}
						}
					}
				}
			});

		});
	}
        function cambiarNotaCredito()
        {
            $("#venta_title").html("Finalizar Nota de Credito");
            $("#total_pagar_title").html("TOTAL NOTA DE CREDITO");
            $(".section_formas").hide();
            $(".section_monto").hide();
            $(".section_vuelto").hide();
        }
        function cambiarVenta()
        {
            $("#venta_title").html("Finalizar Venta");
            $("#total_pagar_title").html("TOTAL A PAGAR");
            $(".section_formas").show();
            $(".section_monto").show();
            $(".section_vuelto").show();            
        }
        function cargarFormasPago()
        {
            var html="";
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
/******************************************************************************FIN VENTA**********************************************/

/******************************************************************************CLIENTES**********************************************/
	//Clientes Funciones
	function seleccionarCliente(){
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
					
					
					if(productosExisten()){
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
					
					
				}else
                                {
                                 mensajeLoad("Cliente no existe","error");   
				}
				
				
			}
		});	
	}
	function guardarCliente(){
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
					mensajeLoad("Se ha insertado correctamente","ok");
					$("#form-cliente").reset();
					$('#modal_add_cliente').modal('hide');
				}else{
					mensajeLoad(response.msg,"error");
				}
				
				
			}
		});	
	}	
/**************************************************************************FIN CLIENTES**********************************************/	

/**************************************************************************DESCUENTOS**********************************************/	
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
	function guardarDescuento(){
			var d1  = $("option:selected",'#descuentos').attr('alt');
			var d2 = d1.split('-');
			var total_descuento = 0;
			var total = parseInt($("#total_a_pagar").val());
			
			var tipo = d2[0];
			var monto = parseInt(d2[1]);
			var descuento_actual = 0;
			
			
			if(tipo == '%'){
				 total_descuento =  ((total * monto) / 100);
			}else if(tipo == '$'){
				 total_descuento =  monto;
			}else{
				alert("Formato no implementado");
			}
			
			descuento_actual = $("#descuentos_aplicados").val();
			$("#descuentos_aplicados").val(parseInt(descuento_actual) + parseInt(total_descuento));
			$("#descuentos_aplicados_2").html('$'+formatNumber.new(parseInt($("#descuentos_aplicados").val())));			
			
			
			var final_total = (parseInt($("#total_a_pagar").val()) - parseInt(total_descuento));
			
			$("#total_a_pagar").val(final_total);
			$("#Total").html('$'+formatNumber.new(parseInt(final_total)));	
			
			$('#modal_add_descuento').modal('hide');
	}	
	function quitarDescuento(){
		actualizarValoresVenta();
	}
	
/**************************************************************************FIN DESCUENTOS**********************************************/

//function Transversal

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
jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
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
