$(function(){
	/** CALENDARIO DE ACTIVIDADES **/
	// Fechas
	var fechas=$.trim($('#fechaActividad').val()).split('-');
	var specialDays=Array();
	$.getJSON("/actividades/ajax/",{
			ano: fechas[0],
			mes: fechas[1]
		}, 
		function(j){
			if(j!=null){
				for (var i = 0; i < j.length; i++) {
					specialDays.push([j[i].id]);
				}	     
				$('.ui-datepicker-calendar').find('a').each(function(){
					dia=$(this).html();
					for(var c in specialDays){
						if(specialDays[c]==dia){
								$(this).addClass("ui-state-highlight ui-state-active");													
						}
					}
				});
			}
		}
	);
	
	// Calendario
	$("#datepicker").datepicker({
		firstDay: 1,
		gotoCurrent:true,
		showCurrentAtPos: 0,
		dateFormat: 'yy/mm/dd',
		defaultDate: new Date(fechas[0],fechas[1]-1, fechas[2]),
				
		onChangeMonthYear: function(year,month){
			var specialDays=Array();
			$.getJSON("/actividades/ajax/",{
				ano: year,
				mes: month
				}, 
				function(j){
					if(j!=null){
						for (var i = 0; i < j.length; i++) {
							specialDays.push([j[i].id]);
						}		     
						$('.ui-datepicker-calendar').find('a').each(function(){	
							dia=$(this).html();
							for(var c in specialDays){
								if(specialDays[c]==dia){
										$(this).addClass("calendario-actividades");													
								}
								
							}
						});
					}
				}
			);
		},
		onSelect: function(value, date){
			location.href='/actividades/'+value+'/';
		}
	});
});