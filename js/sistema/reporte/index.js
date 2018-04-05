var elementos = new Array();
var titlechart ='';
var table;


function loadshow(){
	$("#load").show();
}
function loadhide(){
	$("#load").hide();
}
$(function(){
	
	$('#table-load-reporte').on('keyup','.comision',function(){
		
		var aux = $(this).attr('id').split('_');
		var id_usuario = aux[1];
		var porc = $(this).val();
		var total_ventas_b = $('#total_ventas_b_'+id_usuario).val();
		$('#total_final_'+id_usuario).html('$'+formatNumber.new(parseInt(((porc*total_ventas_b)/100))));
		
		
	});

	$(".chosen-select").chosen();
	$("#anio").change(function(){
		
		var valor = $(this).val();
		if(valor!=""){
			ventasPorMes(valor);	
		}else{
			mensajeLoad('Debe seleccionar un año correcto','error');
		}
	});
	
	$("#mes_user").change(function(){
		
		var mes = $(this).val();
		if(mes!=0){
			
			$("#anio_user").attr("disabled",false);
		}else{
			$("#anio_user").attr("disabled",true);
		}
		
	});
	
	/*$("#anio_user").change(function(){
		
		var anio = $(this).val();
		var mes = $("#mes_user").val();
		
		if(anio!=""){
			ventasPorUsuario(mes,anio);	
		}else{
			mensajeLoad('Debe seleccionar un año correcto','error');
		}
	});	*/
	$('#form-reporte').validate({
            submitHandler: function (form) 
            { 

                ventasPorUsuarioValidar();
            }		
	});   
	
	$("#select-reporte").change(function(){
		cerrarTodo();
		$("#anio").attr("disabled",true);
		if($(this).val()==1)
			productosMasVendidos();
		if($(this).val()==2)
			categoriasMasVendidas();
		if($(this).val()==3)
			mostrarControlesVentasporUsuario();	
		if($(this).val()==4)
			mostrarControlesVentasMensuales();		
		
	});
	
// Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      
	$('#table-load-reporte').on('click','.chart_1',function(e){
		e.preventDefault();
		google.charts.setOnLoadCallback(drawChart);	
		$('#modal_grafico').modal();
		
	})
});
function ventasPorUsuarioValidar(){
		
    var anio = $('#anio_user').val();
    var mes = $("#mes_user").val();
    
    ventasPorUsuario(mes,anio);	

}
function productosMasVendidos(){

        /*var datos = {

            'fechai':fechai,
            'fechaf':fechaf
        };*/

        var html = "";
		var total=0;
		titlechart = 'Productos mas vendidos';
		
        $.ajax({
            type: 'post',
            dataType: "json",
            url: '/reportes/productos-mas-vendidos/',
            beforeSend: function() {
					loadshow();
            },
            success: function (data) {
						loadhide();
						html+="<thead>";
                        html+="<tr>";
                        html+= "<th>SKU</th>";
                        html+= "<th>Categoria</th>";
                        html+= "<th>Producto</th>";
                        html+= "<th>Descripción</th>"; 
                        html+= "<th style='text-align:center;'>Cantidad (<a href='' class='chart_1'>Gráfico</a>)</th>"; 
						html+="</tr>";						
						html+="</thead>";
						
						
                if(data!=""){
                    $.each(data, function(key,value){
						elementos[key] = [value.producto,parseInt(value.cantidad)];
						
                        html+="<tbody>";
                        html+="<tr>";
                        html+= "<td>"+value.sku+"</td>";
                        html+= "<td>"+value.categoria +"</td>";
                        html+= "<td>"+value.producto +"</td>";
                        html+= "<td>"+value.descripcion+"</td>"; 
                        html+= "<td align=center><strong>"+value.cantidad+"<strong></td>";
						html+="</tr>";
						html+="</tbody>";
						total+=parseInt(value.cantidad);
					});
                    html += '<tr><td colspan="4">Total</td><td align="center"><strong>'+total+'</srong></td></tr>';
                }else{

                    html +="<tr><td colspab='6'>No existen Productos</td></tr>"; 

                }
                    $("#table-load-reporte").html(html);					
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });

}	

function categoriasMasVendidas(){

        /*var datos = {

            'fechai':fechai,
            'fechaf':fechaf
        };*/

        var html = "";
		var total=0;
		titlechart = 'Categorias mas vendidas';
        $.ajax({
            type: 'post',
            dataType: "json",
            url: '/reportes/categorias-mas-vendidas/',
            beforeSend: function() {
					loadshow();
            },
            success: function (data) {
						loadhide();
						html+="<thead>";
                        html+="<tr>";
                        html+= "<th>Código</th>";
                        html+= "<th>Categoria</th>"; 
                        html+= "<th style='text-align:center;'>Cantidad (<a href='' class='chart_1'>Gráfico</a>)</th>";
						html+="</tr>";						
						html+="</thead>";
						
						
                if(data!=""){
                    $.each(data, function(key,value){
						elementos[key] = [value.categoria,parseInt(value.cantidad)];
                        html+="<tbody>";
                        html+="<tr>";
                        html+= "<td>"+value.codigo+"</td>";
                        html+= "<td>"+value.categoria +"</td>";
                        html+= "<td align=center><strong>"+value.cantidad+"<strong></td>";
						html+="</tr>";
						html+="</tbody>";
						total+=parseInt(value.cantidad);
					});
                    html += '<tr><td colspan="2">Total</td><td align="center"><strong>'+total+'</srong></td></tr>';
                }else{

                    html +="<tr><td colspab='3'>No existen Resultados</td></tr>"; 

                }

                    $("#table-load-reporte").html(html);
						
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });

}	
function ventasPorUsuario(mes,anio){

        /*var datos = {

            'fechai':fechai,
            'fechaf':fechaf
        };*/

        var html = "";
		var total=0;
		var total_dinero=0;
        $.ajax({
            type: 'post',
            dataType: "json",
			data:{'mes':mes,'anio':anio},
            url: '/reportes/ventas-por-usuario/',
            beforeSend: function() {
					loadshow();
            },
            success: function (data) {
						loadhide();
						html+="<thead>";
                        html+="<tr>";
                        html+= "<th>Vendedor</th>"; 
                        html+= "<th style='text-align:center;'>Cantidad</th>";
                        html+= "<th style='text-align:center;'>Total Venta</th>";
                        html+= "<th style='text-align:center;'>% Comisión</th>";
                        html+= "<th style='text-align:center;'>Total Comision</th>";
						html+="</tr>";						
						html+="</thead>";
						
						
                if(data!=""){
                    $.each(data, function(key,value){

                        html+="<tbody>";
                        html+="<tr>";
                        html+= "<td>"+value.usuario +"</td>";
                        html+= "<td align=center><strong>"+value.cantidad+"<strong></td>";
                        html+= "<td align=center><strong>"+value.total_ventas+"<strong></td>";
                        html+= "<td align=center width='50px'><input type='text' class='comision form-control' id='comision_"+value.id_usuario+"' class='input-sm form-control'  >";
                        html+= "<input type='hidden' id='total_ventas_b_"+value.id_usuario+"' class='form-control' value='"+value.total_ventas_b+"' >";
						html+= "</td>";
						html+= "<td align=center><label id='total_final_"+value.id_usuario+"'></label></td>";
						html+="</tr>";
						html+="</tbody>";
						total_dinero+=parseInt(value.total_ventas_b);
						total+=parseInt(value.cantidad);
					});
                    html += '<tr>';
					html += '<td align="left">Total Ventas</td>';
					html += '<td align="center"><strong>'+total+'</srong></td>'
					html += '<td align="center"><strong>'+'$'+formatNumber.new(parseInt(total_dinero))+'</srong></td>'
					html += '<td align="center"></td>'
					html += '<td align="center"></td>'
					html += '</tr>';
                }else{

                    html +="<tr><td colspab='2'>No existen Resultados</td></tr>"; 

                }

                    $("#table-load-reporte").html(html);
					
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });

}	
function cerrarTodo(){
	
	$("div[id^='content_'").hide();
        $("button[id^='buscar_'").hide();
	$("select").attr("disabled",true);
        //$('#form-reporte')[0].reset();
	
	
}

function mostrarControlesVentasMensuales(){
	$("#content_anio").show();
	$("#anio").attr("disabled",false);	
	
}
function mostrarControlesVentasporUsuario(){
	$("#content_anio_user").show();	
	$("#content_mes_user").show();	
        $("#buscar_usuario_venta_por_mes").show();
	$("#mes_user").attr("disabled",false);	
	
}
function ventasPorMes(anio){
		
        /*var datos = {

            'fechai':fechai,
            'fechaf':fechaf
        };*/

        var html = "";
		var total;
		var dinero=0;
		var producto=0;
		var desc = 0;

		$.ajax({
            type: 'post',
            dataType: "json",
            url: '/reportes/ventas-por-mes/',
			data: {'anio':anio},
            beforeSend: function() {
					loadshow();
            },
            success: function (data) {
						loadhide();
						html+="<thead>";
                        html+="<tr>";
                        html+= "<th>Mes / Año</th>";  
                        html+= "<th style='text-align:center;'>Cantidad</th>";
                        html+= "<th style='text-align:center;'>Total Débito</th>";
                        html+= "<th style='text-align:center;'>Total Crédito</th>";
                        html+= "<th style='text-align:center;'>Total Efectivo</th>";
                        html+= "<th style='text-align:center;' class='danger'>Total Notas de Crédito</th>";
                        html+= "<th style='text-align:center;'>Total</th>";
						html+="</tr>";						
						html+="</thead>";
						
						
                if(data!=""){
                    $.each(data, function(key,value){

                        html+="<tbody>";
                        html+="<tr>";
                        html+= "<td>"+ value.mes_numero +" / "+value.ano +"</td>";
                        html+= "<td align=center>"+value.cantidad+"</td>";
                        html+= "<td align=right>"+value.total_debito+"</td>";
                        html+= "<td align=right>"+value.total_credito+"</td>";
                        html+= "<td align=right>"+value.total_efectivo+"</td>";
                        html+= "<td align=right class='danger' >"+value.total_nota_credito+"</td>";
                        html+= "<td align=right >"+value.total_final+"</td>";
						html+="</tr>";
						html+="</tbody>";
						total = value.total_total;
						producto+=parseInt(value.productos_cantidad);
						//dinero=(value.total_dinero_int);
						//desc=(value.total_descuento_int);
						
					});
                    html += '<tr><td align="right" colspan="6">Total</td><td align="right"><strong>'+total+'</srong></td></tr>';
                }else{

                    html +="<tr><td colspab='2'>No existen Resultados</td></tr>"; 

                }

                    $("#table-load-reporte").html(html);						
					
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });

}	




      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows(elementos);

        // Set chart options
        var options = {'title':titlechart,
                       'width':800,
                       'height':550};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart (document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	  
