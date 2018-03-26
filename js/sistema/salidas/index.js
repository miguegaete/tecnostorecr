
function cargarJson($fechai,fechaf){
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

}
function InsertarAjax(){

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

}
function CargarAjax(fechai,fechaf){

        var datos = {

            'fechai':fechai,
            'fechaf':fechaf
        };

        var html = "";
        var total = "";

        $.ajax({
            data: datos,
            type: 'post',
            dataType: "json",
            url: '/salidas/listar-ajax/',
            beforeSend: function() {
                $("#my-table tbody").html('<tr><td colspan="6" align="center">Cargando datos...</td></tr>');
            },
            success: function (data) {

                if(data!=""){
                    $.each(data, function(key,value){

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
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });

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
    cargarJson();

    $("#form1").on('submit',function(e){	
    		e.preventDefault();
            InsertarAjax();
    });

    $("#filtrar").click(function(e){
        e.preventDefault();
        var fecha1 =  $("#fecha1").val();
        var fecha2 =  $("#fecha2").val();
        CargarAjax(fecha1,fecha2);
    });

    $("#refresh").click(function(){

        cargarJson();

    });

$('#fecha1,#fecha2').datetimepicker({locale: 'es',format:'DD-MM-YYYY HH:SS'});



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
        }});

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