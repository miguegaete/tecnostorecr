var elementos = new Array();




	  
	  
function productosMasVendidos(){
		
        $.ajax({
            type: 'post',
            dataType: "json",
            url: '/reportes/productos-mas-vendidos/',
            beforeSend: function() {
					//loadshow();
            },
            success: function (data) {
						//loadhide();
						if(data!=""){
							
							$.each(data, function(key,value){
								elementos[key] = [value.producto,parseInt(value.cantidad)];
							});
							
							/*for(i=0;i<data.length;i++){
								
								elementos[i] = [data.producto, parseInt(data.cantidad)];
								
							}*/
							
							
						}else{

						}				
             
            },
            error: function(result){
                //alert("No existen resultados");
            }
            

        });
		
		
}	  


	  
  






