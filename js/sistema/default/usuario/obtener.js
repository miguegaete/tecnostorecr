$(function(){
	
	$("#form1").validate({
		rules: {
                    usuario: 
                    {
                        required: true,
                        alphanumeric: true
                    },
                    comision: 
                    {
                        required: false,
                        number: true
                    }                    
                }/*,
        submitHandler: function (form) { // for demo
			//$(this).submit();
        }		*/
	});	
	
})