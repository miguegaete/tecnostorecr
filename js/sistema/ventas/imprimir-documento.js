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
function imprimir(ped_id,tipo){

	var productos;
	var pedido;
	
	
	//Llamada datosde los productos del pedido
	$.ajax({
		url:'/ventas/imprimir/'+ped_id+'/'+1+'/',
		async: true,
		dataType: "json",
		success:
		function(response){
			if(tipo==2){
				enviarImpresoraNota(response,true);
			}else{
				enviarImpresora(response,true); 
			}
		}
	});			
}
		
function enviarImpresora(response,valor,tipo) {
	
	var f = new Date();
	var fecha = response[0].fecha;
	var hora = response[0].hora;
	var mesa = (response[0].mesa!=0)?response[0].mesa:'Entrega';
	var total = response[0].total;
	var subtotal = response[0].subtotal;
	var descuento = response[0].descuento;
	var sizef = (valor)?2:2;
	var numeroped = response[0].id;
	var vendedor = response[0].usuario;
	
	var nombre_empresa= response[1].nombre;
	var fono_empresa= response[1].fono;
	var dir_empresa= response[1].direccion;
	
	var descripcion = (response[0].descripcion!='nulo')?response[0].descripcion:'Sin descripci&oacute;n';

    // Preserve formatting for white spaces, etc.
    var colA = '<table border=0 cellpadding=1 cellspacing=0 width=100%><tr><td align=center><font size=3 ><b>'+nombre_empresa+'</b></font></td></tr>';
        colA = colA +'<tr><td align=center><font align=center size=1 ><b>Tel. '+fono_empresa+'</b></font></td></tr>';
        colA = colA +'<tr><td align=center><font align=center size=1 ><b>Dir. '+dir_empresa+'</b></font></td></tr>';
		colA = colA +'<tr>';
		colA = colA +'<td align=center><hr width=100%></td>';
		colA = colA +'</tr>';          
		colA = colA +'</table>';
		colA = colA +'<table border=0 cellpadding=1 cellspacing=0 width=100% >';
		colA = colA +'<tr>';
		colA = colA +'<td align=left ><font size=1 ><strong>Fecha:</b></strong></td>';
		colA = colA +'<td align=left ><font size=1 >'+fecha+'</font></td>';
		colA = colA +'<td align=left ><font size=1 ><strong>Hora:</strong></font></td>';
		colA = colA +'<td align=left ><font size=1 >'+hora+'</font></td>';
		colA = colA +'</tr>';  	
		colA = colA +'<tr>';             
		colA = colA +'<td align=left ><font size=1 ><strong>Venta:</strong></font></td>';
		colA = colA +'<td align=left ><font size=1 >'+numeroped+'</font></td>';	
		colA = colA +'<td align=left ><font size=1 ><strong>Vendedor:</strong></font></td>';
		colA = colA +'<td align=left ><font size=1 >'+vendedor+'</font></td>';	
		colA = colA +'</tr>';   
		colA = colA +'<tr>';
		colA = colA +'<td align=center colspan=4><hr width=100%></td>';
		colA = colA +'</tr>';  		
		colA = colA +'</table>';    
		   

	colA = colA + '<table border=0 cellpadding=1 cellspacing=0 align=center width=100% >';
	colA = colA +'<tr>';
	colA = colA +'<td><font size=1>CAN.</font></td>';
	if(valor){
	colA = colA +'<td><font size=1>VAL.</font></td>';
	}
	colA = colA +'<td><font size=1>DESC.</font></td>';
	if(valor){
	colA = colA +'<td><font size=1>TOTAL</font></td>';	
	}	
	for(i=2;i<response.length;i++){
		var precio = (valor)?'$'+format(response[i].precio)+'&nbsp;':'';
		colA = colA +'</tr>';	
		colA = colA +'<tr>';
		colA = colA +'<td valign=top ><font size='+sizef+'>'+response[i].cantidad+'&nbsp;x&nbsp;</font>&nbsp;</td>';
		colA = colA +'<td valign=top ><font size='+sizef+'>'+precio+'</font></td>';
		colA = colA +'<td valign=top ><font size='+sizef+'>'+response[i].categoria+'.'+response[i].nombre+'</font></td>';
		if(valor){
		colA = colA +'<td valign=top ><font size='+sizef+'>$'+format(response[i].valor)+'</font></td>';	
		}
		colA = colA +'</tr>';		
	}
		colA = colA +'<tr>';
		colA = colA +'<td align=center colspan=4><hr width=100%></td>';
		colA = colA +'</tr>';  	
	if(valor){
		colA = colA +'<tr>';	
		colA = colA +'<td colspan=4 >&nbsp;</td>';	
		colA = colA +'</tr>';			
		colA = colA +'<tr>';	
		colA = colA +'<td align=right colspan=3 ><font size=2><strong>SUBTOTAL&nbsp;</strong></font></td>';	
		colA = colA +'<td align="right" ><font size=2><strong>$'+format(subtotal)+'</strong></font></td>';	
		colA = colA +'</tr>';
		colA = colA +'<tr>';	
		colA = colA +'<td align=right colspan=3 ><font size=2><strong>DESC.&nbsp;</strong></font></td>';	
		colA = colA +'<td align="right" ><font size=2><strong>$'+format(descuento)+'</strong></font></td>';	
		colA = colA +'</tr>';		
		colA = colA +'<tr>';	
		colA = colA +'<td align=right colspan=3 ><font size=2><strong>TOTAL&nbsp;</strong></font></td>';	
		colA = colA +'<td align="right"><font size=2><strong>$'+format(total)+'</strong></font></td>';	
		colA = colA +'</tr>';
		
	} 	
		colA = colA +'</table>';
		
	
    //qz.setCopies(3);
    //qz.setCopies(parseInt(1));

    // Append our image (only one image can be appended per print)
    
         
        var boleta = '<table border=0 cellpadding=0  cellspacing=0 width="200" align=center>' +				
                '<tr>' +
                    '<td>' + colA + '</td>' +
                '</tr>' +
                '</table>';
				
		$(".impreso").html(boleta);

		window.print();
}				

function enviarImpresoraNota(response,valor) {
	
	var f = new Date();
	var fecha = response[0].fecha;
	var hora = response[0].hora;
	var mesa = (response[0].mesa!=0)?response[0].mesa:'Entrega';
	var total = response[0].total;
	var subtotal = response[0].subtotal;
	var titulo = "NOTA DE CRÉDITO";
	var numeroped = response[0].id;
	var nombreempresa = response[1].nombre;
	var telefono = response[1].fono;
	var direccion = response[1].direccion;
	var rut = response[1].rut;
	var ciudad = 'CORONEL';
	var actividad = 'VENTA DE ARTÍCULOS ELECTRÓNICOS';
	
	var descripcion = (response[0].descripcion!='nulo')?response[0].descripcion:'Sin descripci&oacute;n';

	// Preserve formatting for white spaces, etc.
	
	var colA = '<p class="title-print">'+nombreempresa+', '+ciudad+'</p>';
	if(valor){
		
		colA = colA +'<table id="head-1"><tbody>';
		colA = colA +'<tr><td>'+direccion+'</td></tr>';
		colA = colA +'<tr><td>RUT: '+rut+'</td></tr>';
		colA = colA +'<tr><td>'+actividad+'</td></tr>';
		colA = colA +'<tr><td>FONO: '+telefono+'</td></tr>';
		colA = colA +'</tbody></table>';
		//Table fin Head 1
		
		


	}
		//colA = colA +'<p class="font-11">VENDEDOR(A): '+response[0].cajero+' <p>';
		
		
		colA = colA +'<table id="head-3"><tbody>';
		colA = colA +'<tr>';                     
		if(valor){
		colA = colA +'<td align="left"><b>NOTA DE CRÉDITO<b></td>';
		colA = colA +'<td align="right">N°_: '+numeroped+'</td>';	
		}
		colA = colA +'</tr>';     		
		colA = colA +'</tbody></table>';  
		//Fin table head 2
		   

		colA = colA + '<table id="content"><tbody>';
		
	for(i=2;i<response.length;i++){
		var precio = (valor)?format(response[i].precio)+'&nbsp;':'';
		
			colA = colA +'<tr><td colspan="4">'+response[i].nombre+'</td></tr>';
			colA = colA +'<tr>';
			colA = colA +'<td align="center">'+response[i].cantidad+'</td>';
			colA = colA +'<td align="center">X</td>';
			colA = colA +'<td>'+precio+'</td>';
			colA = colA +'<td align="right">'+format(response[i].valor)+'</td>';	
			colA = colA +'</tr>';		

	} 	
		
		colA = colA +'<tr>';	
		colA = colA +'<td colspan="3" align="right"><p class="title-print">SUBTOTAL</p></td>';	
		colA = colA +'<td align="right">'+format(subtotal)+'</td>';	
		colA = colA +'</tr>';
		/*colA = colA +'<tr>';	
		colA = colA +'<td colspan="3" align="right"><p class="title-print">IVA(19%)</p></td>';	
		colA = colA +'<td align="right">'+format(0)+'</td>';	
		colA = colA +'</tr>';					
		colA = colA +'<tr>';*/	
		colA = colA +'<td colspan="3" align="right"><p class="title-print">TOTAL A PAGAR</p></td>';	
		colA = colA +'<td align="right">'+format(total)+'</td>';	
		colA = colA +'</tr>';					
		colA = colA +'</tbody></table>'
	
		colA = colA +'<table id="head-2"><tbody>';
		colA = colA +'<tr>';
		colA = colA +'<td align="left">Fecha: '+fecha+'</td>';
		colA = colA +'<td align="right">Hora: '+hora+'</td>';
		colA = colA +'</tr>'; 		
		colA = colA +'</tbody></table>';				
	
		var boleta = '<table id="final" align="center">' +				
				'<tr>' +
					'<td>' + colA + '</td>' +
				'</tr>' +
				'</table>';
				
		$(".impreso").html(boleta);

		window.print();
}
				

$(function(){
	
	$(".imp-doc").click(function(e){
		e.preventDefault();
		var aux = $(this).attr('id');
		var aux1 = aux.split("_");
		var codigo = aux1[0];
		var tipo = aux1[1];
		
		imprimir(codigo,tipo);
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
jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}