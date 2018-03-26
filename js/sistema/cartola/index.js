var descripcion = "";
/*function cargarJson($fechai,fechaf){
    var html = "";
    var total= "";
    $.getJSON("/json/salidas/salidas.json",function(obj){

        if(obj!=""){
            $.each(obj, function(key,value){
                        html +="<tr>";
                        html += "<td>"+value.descripcion+"</td>";
                        html += "<td>"+value.fecha+"</td>";
                        html += "<td>"+value.tiempo+"</td>";
                        html += "<td>"+value.valor+"</td>";
                        html += '<td><a href="/salidas/eliminar/'+value.id+'/" title="Eliminar" class="eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr>';
                        total = value.total;
            });
            html += '<tr><td colspan="3">Total</td><td>'+total+'</td><td></td></tr>';
        }else{
            html +="<tr><td colspab='6'>No existen resultados</td></tr>"; 
        }
        $("#my-table tbody").html(html);

    });

}*/

/*function InsertarAjax(){

        var datos = $('#form1').serialize();

        $.ajax({
            url: '/salidas/guardar-ajax/',
            type: 'post',
            success: function (data) {

                $("#mensaje-ajax").html(data);

                //Cargamos datos desde archivo .json
                //cargarJson();

                //Limpiar form
                $("#form1").each(function(){
                    this.reset();
                });
                
            },
            data: datos
        });

}*/

function CargarAjax(fecha){
	$("#exportar").attr('href', '/cartola/exportarpdf/'+$("#fecha").val()+'/');
	var datos = {
		'fecha':fecha
	};

	var html = "";
	var total = "";

	$.ajax({
		data: datos,
		type: 'post',
		dataType: "json",
		async: false,
		url: '/cartola/listar-ajax/',
		beforeSend: function() {
			$("#my-table tbody").html('<tr><td colspan="5" align="center">Cargando datos...</td></tr>');
		},
		success: function (data) {
			var total = 0;
			if(data!=""){
				$.each(data, function(key,value){
					html +="<tr>";
					html += "<td>#"+value.idie+"</td>";
					html += "<td>"+value.fecha+"</td>";
					var tipo = (value.tipo == 1) ? "Ingreso" : "Egreso";
					total = (value.tipo == 1) ? total+parseInt(value.valor2) : total-parseInt(value.valor2);
					html += "<td>"+tipo+"</td>";
					cargarProductos(value.idie,value.tipo);
					//alert(descripcion);
					html += "<td>"+descripcion+"</td>";
					html += "<td>$"+value.valor+"</td>";
					html += "</tr>";
				});
				html += '<tr><td class="text-right" colspan="4"><h3>Total</h3></td><td><h3>$'+format(total)+'</h3></td></tr>';
			}else{
				html +="<tr><td colspan='5' align='center'>No existen resultados</td></tr>"; 
			}
			$("#my-table tbody").html(html);
		 
		},
		error: function(result){
			//alert("No existen resultados");
		}
		

	});
}

function cargarProductos(idie,tipo){
	descripcion = "";
	if(tipo == 1){
		$.ajax({
			url:'/cartola/ingreso/'+idie+'/',
			//async: true,
			dataType: "json",
			async: false,
			success:
			function(response){
				descripcion += "<ul style='padding-left:0px;'>";
				$.each(response, function(i, item){
					descripcion += "<li>"+item.nombre+" x "+item.cantidad+" $"+format(item.valor)+"</li>";
					//productos += '<tr class="'+item.tipo+'_'+item.idpro+'" id="'+item.id+'"><td>'+item.cantidad+'</td><td>'+item.nombre+'</td><td>$'+item.precio+'</td><td>$'+item.valor+'</td><td align="center"><a href="#" title="Eliminar" class="eliminar" onClick="eliminar(this);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr>';
				});
				descripcion += "</ul>";
			}
		})
	}
	else{
		$.ajax({
			url:'/cartola/egreso/'+idie+'/',
			//async: true,
			dataType: "json",
			async: false,
			success:
			function(response){
				descripcion = response.descripcion;
			}
		})
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

$(document).ready(function(){

    $("#my-table").on('click','.eliminar',function(e){
        e.preventDefault();
        var link = $(this).attr("href");

        var txt;
        var r = confirm("¿Está seguro que desea eliminar esta Salida?");
        if (r == true) {
            window.location = link;
        } else {
            return false;
        }
    });
});

$(function(){

    //Cargamos datos JSON
    CargarAjax();

    /*$("#form1").on('submit',function(e){	
    	e.preventDefault();
        InsertarAjax();
    });*/

    $("#filtrar").click(function(e){
        e.preventDefault();
        var fecha =  $("#fecha").val();
        CargarAjax(fecha);		
    });

    $("#refresh").click(function(){
        CargarAjax();
    });

	$('#fecha').datetimepicker({locale: 'es',format:'DD-MM-YYYY'});

/*	$("#search-table").keyup(function(){
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
	});*/

})

$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

(function($) {
	$.fn.prettynumber = function(options) {
		var opts = $.extend({}, $.fn.prettynumber.defaults, options);
		return this.each(function() {
			$this = $(this);
			var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
			var str = $this.html();
			$this.html($this.html().toString().replace(new RegExp("(^\\d{"+($this.html().toString().length%3||-1)+"})(?=\\d{3})"),"$1"+o.delimiter).replace(/(\d{3})(?=\d)/g,"$1"+o.delimiter));
		});
	};
	$.fn.prettynumber.defaults = {
		delimiter       : '.'   
	};
})(jQuery);