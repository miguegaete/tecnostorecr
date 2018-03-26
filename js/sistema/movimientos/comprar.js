var cantItem = 0;


function validarProducto(id_producto){
	var flag = true;
	
	$('#content-productos tr').each(function(){
		
		if($(this).hasClass('row_'+id_producto)){
			 flag = false;
		}
		
	});
	return flag;
}


function agregarProducto(id_producto){
	var html = '';
	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: '/productos/obtener-producto/',
		data: {'id_producto':id_producto},
		async: false,
		beforeSend: function() {
				loadshow();
		},
		success: function (data) {
			loadhide();
					
			if((data.id) && (data.costo!=null)){
				cantItem++;
				if(validarProducto(data.id)){
					if($('#content-productos tr'))
					html+= '<tr class="row_'+data.id+'">';
					html+= '<td>'+cantItem+'</td>';
					html+= '<td><input type="hidden" id=""  name="" value="'+data.id+'" ><input type="number" id="cant_'+data.id+'" style="width:100px;" name="cant_'+data.id+'" class="form-control cantidad" value="1" ></td>';
					html+= '<td>'+data.nombre+'</td>';
					html+= '<td> $'+ formatNumber.new(data.costo)+'<input type="hidden" value="'+data.costo+'" id="costo_'+data.id+'"><input type="hidden" value="'+data.costo+'" id="txtTotal_'+data.id+'"></td>';
					html+= '<td id="total_'+data.id+'"> $'+ formatNumber.new(data.costo)+'</td>';
					html+= '<td><a href="" class="eliminar" id="eliminar_'+data.id+'">X</a></td>';
					html+= '</tr>';	
					if(cantItem != 1){
						$("#content-productos").append(html);
						establecerTotales();
					}else{
						$("#content-productos").html(html);
						establecerTotales();
					}
				}else{
					mensajeLoad('Este producto ya existe','error');
				}
			}else{
				mensajeLoad('El producto que usted está tratando de agregar a la compra no tiene un <strong>Costo</strong> definido o no existe en la base de datos','error');
			}
		 
		}
	});	
		
}



function establecerTotales()
{
	var total = 0;
	var iva = 0;
	var subtotal = 0;
	var ivav1 = ((parseFloat($("#txtIVAvalor").val())/100) + 1);
	var ivav2 = (parseFloat($("#txtIVAvalor").val()) / 100);
	
	$("#content-productos [id*=txtTotal_]").each(function(){
		total += parseInt($(this).val());
	});
	
	subtotal = Math.round((total / ivav1));
	iva = Math.round((subtotal * ivav2));	
	
	$("#txtsubtotal").val(subtotal);
	$("#lblSubTotal").html('$ '+ formatNumber.new(subtotal));
	
	$("#txtiva").val(iva);
	$("#lblIVA").html('$ '+ formatNumber.new(iva));
	
	$("#txttotal").val(total);
	$("#lblTotal").html('$ '+ formatNumber.new(total));
	
	$("#h1total").html('$ '+ formatNumber.new(total));
	
	(cantItem > 0 ) ? botonesAcciones(false): botonesAcciones(true);
	
	
}

function vaciarCarro()
{

			var html = '<tr><td colspan="6" align="center">No existen registros en la compra</td></tr>';
			$('#content-productos').html(html);	
			establecerTotales();
			cantItem = 0;
			botonesAcciones(true);

			
	
}

function botonesAcciones(inactivos){
	$("#btnGuardarCompra").attr("disabled", inactivos);
	$("#btnVaciarCarro").attr("disabled", inactivos);
}


function productosCompra(){
	
	var datos = {
		productos: []
	};
	
	$('#content-productos > tr').each(function(){
		var aux = $(this).attr('class').split('_');
		var id = aux[1];
		
		datos.productos.push({
			
			"id": id,
			"cantidad" : $("#cant_"+id).val(),
			"precio_costo" : $("#costo_"+id).val(),
			"total": $("#txtTotal_"+id).val()
		})
	});
	return datos;
}



function InsertarAjax(){
	
	
	
	
	var datos = $("#form-comprar").serialize();
	
	
	$.ajax({
		type: 'post',
		dataType: "json",
		data: datos,
		async: false,
		url: '/movimientos/guardar-compra/',
		beforeSend: function() {
				loadshow();
		},
		success: function (data) {
			loadhide();
					
			if(data.rs){
				guardarProductosCompra(data.msg);
				
			}else{
				mensajeLoad(data.msg,'error');
			}
		 
		}
	});
}

function guardarProductosCompra(id){
	var datos  = productosCompra();
	
	$.ajax({
		type: 'post',
		dataType: "json",
		data: datos,
		async: false,
		url: '/movimientos/guardar-productos-compra/'+id+'/',
		beforeSend: function() {
				loadshow();
		},
		success: function (data) {
			loadhide();
					
			if(data.rs){
				mensajeLoad("Compra guardada exitosamente","ok");
				vaciarCarro();
			}else{
				mensajeLoad(data.msg,'error');
			}
		 
		}
	});	
}


$(function(){
	
	$("#btnGuardarCompra").click(function(e){
		e.preventDefault();
		$("#form-comprar").submit();
		
	});
	
	$("#form-comprar").validate({
	  submitHandler:function(form) {
		InsertarAjax();	  
	  }
	});	
	
	
	$("#btnVaciarCarro").click(function(){
		vaciarCarro();
	})
	
	$('#txtFechaCompra').datetimepicker({locale: 'es',format:'DD-MM-YYYY'});
	
	$('.chosen-select').chosen();
	
	$('#content-productos').on('keyup','.cantidad',function(){
		var cantidad = parseInt($(this).val());
		var aux = $(this).attr('id').split('_');
		var id = aux[1];
		var costo = parseInt($('#costo_'+id).val());
		$('#total_'+id).html('$ '+formatNumber.new(cantidad*costo));
		$('#txtTotal_'+id).val((cantidad*costo));
		establecerTotales();
		
	});
	
	$('#content-productos').on('change','.cantidad',function(){
		var cantidad = parseInt($(this).val());
		var aux = $(this).attr('id').split('_');
		var id = aux[1];
		var costo = parseInt($('#costo_'+id).val());
		$('#total_'+id).html('$ '+formatNumber.new(cantidad*costo));
		$('#txtTotal_'+id).val((cantidad*costo));
		establecerTotales();
		
	});	
	
	$('.pro').change(function(){
		agregarProducto($(this).val());	
		
	});
	
	$('#content-productos').on('click','.eliminar',function(event){
		
		event.preventDefault();
		
		var id = $(this).attr('id');
		var aux = id.split('_');
		$('.row_'+aux[1]).fadeOut(300, function() { $(this).remove();establecerTotales(); });
		
		cantItem--;
		if(cantItem==0){
			var html = '<tr><td colspan="6" align="center">No existen registros en la compra</td></tr>';
			$('#content-productos').html(html);
		}
		
		
		
		
	});
	
})