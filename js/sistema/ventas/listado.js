var $table = $("#table");
var id;
var tipo;
function validarUsuario(){
	var datos = $('#form-pass').serialize();
	var retorno = false;
	$.ajax
	({
		url:'/usuarios/validar-usuario/',
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
			
			if((r.rs) && (!r.error)){
				$('#valida_usuario').modal('hide');
				eliminarVenta(id,tipo);
			}else{
				$('#valida_usuario').modal('hide');
				mensajeLoad('Contraseña incorrecta','error');
			}
		}
	});		
}
function validaUsuario(){
		$("#valida_usuario").modal();
}


function eliminarVenta(id,tipo){
	
	$.ajax
	({
		url:'/ventas/eliminar-venta/',
		data:{id:id,tipo:tipo},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(r)
		{
			loadhide();
			
			if((r.rs) && (!r.error)){
				console.log(r);
				mensajeLoad(r.msg,'exito');
				$("#tr_"+id).remove();
				var count = $("#table > tbody > tr").length;
				if(count == 0){
					var html = "<tr><td colspan='12' style='text-align:center;'>No existen resultados</td></tr>";
					$("#table > tbody").append(html);
				}
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+r.msg+"]",'error');
			}
		}
	});	
	
}
	function actualizarCaja(id_caja){
			
			var retorno;
			
			$.ajax({
				url: '/venta/actualizar-caja/',
				type: 'POST',
				data:{'id_caja':id_caja},
				dataType: 'json',
				beforeSend: function()
				{
					loadshow();	
				},				
				success: function (data)
				{
						loadhide();	
						if(!isNaN(data.resultado)){					
							
							retorno = true;
							
						}else{
							retorno = false;
						}
					
				}
			});
			
			return retorno;
	}
	
function AgregarVentaACaja(id_caja,id_venta){
	var estadocaja = $("#estado_caja").val();
	
	if(estadocaja == 0){
	$.ajax
	({
		url:'/ventas/agregar-venta-caja/',
		data:{id_venta:id_venta, id_caja:id_caja},	
		dataType:"JSON",
		type:"POST",
		beforeSend:function()
		{
			loadshow();
		},
		success:function(json)
		{
			loadhide();
			
			if((json.rs) && (!json.error)){
				
				mensajeLoad("Venta Agregada Correctamente","ok");
				listadoVentasCaja(id_caja);
				actualizarCaja(id_caja);
			}else{
				mensajeLoad("Ha ocurrido un error. descripción error: ["+json.msg+"]",'error');
			}
		}
	});	
	}else{
	
		mensajeLoad("Caja abierta, favor verifique que esté cerrada para eliminar una venta","error");
	
	}
	
}

function listadoVentasCaja(id_caja){
	var html = "";
		$.ajax({
            url: '/ventas/listado-ventas/',
            type: 'post',
			beforeSend: function() {
                $("#listado-ventas").html('<tr><td colspan="4" align="center">Cargando datos...</td></tr>');
            },				
            success: function (data) {
				
				if(data.rs!=""){
					$.each(data.rs, function(key,value){
								html +="<tr id='tr_"+value.id_ped+"'>";
								html += "<td>"+(key+1)+"</td>";
								html += "<td>"+value.id_ped+"</td>";
								html += "<td>"+value.fecha+"</td>";
								html += "<td>$"+formatNumber.new(value.total_ped)+"</td>";
								html += "<td><a href='' id='v_"+value.id_ped+"' class='eliminar-venta' ><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>"; 
								html += "</tr>";
					});
				}else{
					html +="<tr><td colspan='6'>No existen resultados</td></tr>"; 
				}
				
				$("#listado-ventas").html(html);
                
                
            },
	
            data:{ id_caja: id_caja}
        });	
	
	
}

$(function(){
	
	$('#valida_usuario').on('shown.bs.modal', function () {
	  $("#pass").val('');
	  $("#pass").focus();
	  
	});
	
	$('#validar').click(function(){
		$("#form-pass").submit();
	})
	
	$('#validar-venta-caja').click(function(){
		$("#form-addventa").submit();
	})	
	
	$('#form-pass').validate({
		rules : {
			pass : {
				minlength : 5
			}
		},
        submitHandler: function (form) { 
			//$(this).submit();
			validarUsuario()
				
				
			
        }		
	});	
	
	$('#form-addventa').validate({
		rules : {

		},
        submitHandler: function (form) { 
			//$(this).submit();
			AgregarVentaACaja($("#id_caja_modal").val(),$("#id_venta_modal").val());
				
				
			
        }		
	});		
	
	$('#table').DataTable({
		dom: 'Bfrtip',
		stateSave: true,
		buttons: [
									{
										extend: 'excel',
										text: 'Exportar a Excel'
									},
									{
										extend: 'colvis',
										text: 'Columnas'
									}							
									
		]
	} );

	$('#table-caja').DataTable({
		dom: 'Bfrtip',
		stateSave: true,
		buttons: [
					{
						extend: 'excel',
						text: 'Exportar a Excel'
					},
					{
						extend: 'colvis',
						text: 'Columnas'
					}							
									
	]});	
	
	$('#fecha1').datetimepicker({locale: 'es',format:'DD-MM-YYYY'});
	$('#fecha2').datetimepicker({locale: 'es',format:'DD-MM-YYYY'});
	
	$("#table tbody").on('click','.productos',function(e){
		e.preventDefault();
		var html = "";
		var id_venta = $(this).attr("alt");
		
		$.ajax({
            url: '/productos/lista-venta/',
            type: 'post',
			beforeSend: function() {
                $("#listado-venta").html('<tr><td colspan="4" align="center">Cargando datos...</td></tr>');
            },				
            success: function (data) {
				
				if(data!=""){
					$.each(data, function(key,value){
								html +="<tr>";
								html += "<td>"+value.categoria+"</td>";
								html += "<td>"+value.producto+" [SKU:<b>"+value.sku+"</b>]"+"</td>";
								html += "<td>"+value.descripcion+"</td>";
								html += "<td>"+value.cantidad+"</td>";
								html += "<td>"+value.descuento+"</td>";
								html += "<td>$"+formatNumber.new(value.valor)+"</td>";
								html += "</tr>";
					});
				}else{
					html +="<tr><td colspab='6'>No existen resultados</td></tr>"; 
				}
				
				$("#listado-venta").html(html);
                
                
            },
	
            data:{ id: id_venta}
        });
		
		
		$('.modal-prod').modal();
		
	});
	
	$("#table tbody").on('click','.eliminar',function(e){
		
		e.preventDefault();
		var aux = $(this).attr("id");
		var aux1 = aux.split('_');
		id = aux1[0];
		tipo = aux1[1];
		
		var r = confirm("Al borrar esta operación, los productos asociados actualizarán su stock.\ny la venta se eliminará definitivamente del sistema, si tiene dudas consulte al administrador.\n¿Desea elininar esta operación del sistema?");
		if (r == true) {
			validaUsuario();
		} else {
			return false;
		}		
		
	})
	
	$("#listado-ventas").on('click','.eliminar-venta',function(e){
		e.preventDefault();
		var aux = $(this).attr('id').split('_');
		var id_venta  = aux[1];
		var id_caja  = $("#id_caja_modal").val();
		var estadocaja = $("#estado_caja").val();
		
		if(estadocaja == 0){
		$.ajax({
            url: '/ventas/eliminar-venta-caja/',
            type: 'post',
			data:{ id_venta: id_venta ,id_caja: id_caja},
			beforeSend: function() {
                loadshow();
            },				
            success: function (json) {
				loadhide();
				if(json.error!=true){
					mensajeLoad("Venta N°:["+id_venta+"], ha sido desasociada de esta caja","ok");
					listadoVentasCaja(id_caja);
					actualizarCaja(id_caja);
				}else{
					mensajeLoad("Ha ocurrido un error inesperado, error: "+ json.msg ,"error");
				}
				
            }
	
            
        });		
		}else{
			mensajeLoad("Caja abierta, favor verifique que esté cerrada para eliminar una venta","error");
		}
		
	});
	
	$("#table-caja tbody").on('click','.list-ventas',function(e){
		e.preventDefault();
		var html = "";
		var aux = $(this).attr("alt").split('_');
		var id_caja = aux[0];
		var estadocaja = aux[1];
		
		listadoVentasCaja(id_caja);
		
		$("#id_caja_modal").val(id_caja);
		$("#estado_caja").val(estadocaja);
		$('.modal-ventas').modal();
		
	});	
	
})

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