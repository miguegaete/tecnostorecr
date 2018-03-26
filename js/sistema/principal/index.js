var prod = new Array();



function loadshow(){
	$("#load").show();
}
function loadhide(){
	$("#load").hide();
}				  

function mensajeLoad(mensaje,tipo){
	var alerta = (tipo=='error')?'danger':'success';
	var icono = (tipo=='error')?'remove':'ok';
	
	$("#mensaje-load").html('<div class="alert alert-'+alerta+' alert-dismissible" id="alert-esp" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-'+icono+'" aria-hidden="true"></span><strong> '+mensaje+'</strong></div>');	
	$("#mensaje-load").fadeIn(500).delay(5000).fadeOut(500);
}	
function limpiar(form){
	$('#'+form)[0].reset();
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


$(function(){
	// Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});
	  
	   cantidadCategoriasPorProductos();
	 $("#load_charts").click(function(e){
		
		  e.preventDefault();
		  google.charts.setOnLoadCallback(drawChartCat)
		  $('#modal_grafico_resumen').modal();
	  });
});


function drawChartCat() {

// Create the data table.
var data = new google.visualization.DataTable();
data.addColumn('string', 'Topping');
data.addColumn('number', 'Slices');
data.addRows(prod);

// Set chart options
var options = {'title':'Primeras 5 categorías con más productos.',pieHole: 0.4,width:500,height:300};

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('chart_div_cat_p_prod'));
chart.draw(data, options);
}

function cantidadCategoriasPorProductos(){
		
	$.ajax({
		type: 'post',
		dataType: "json",
		url: '/reportes/cantidad-productos-por-categoria/',
		beforeSend: function() {
				loadshow();
		},
		success: function (data) {
					loadhide();
			if(data!=""){
				$.each(data, function(key,value){
					prod[key] = [value.categoria,parseInt(value.cantidad)];
				});
			}
		},
		error: function(result){
			loadhide();
		}
	});		
		
}	