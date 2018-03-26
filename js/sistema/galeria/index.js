$(document).ready(function(){
	// GALERIA
	// $("#slider").easySlider({});	
	var control = false;
	if($("#slider ul li").length > 1) control = true;
	$("#slider").easySlider({controlsFade: true, controlsShow: control});
	
	// COLORBOX
	$("a[rel='galeria']").colorbox({transition:"elastic",current: "Imagen {current} de {total}"});
	
	$(".multimedia").tabs();
});