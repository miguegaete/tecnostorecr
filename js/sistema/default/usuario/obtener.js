$(function(){
	
	$("#form1").validate({
		rules: {
            usuario: {
				required: true,
                alphanumeric: true
            }
        }/*,
        submitHandler: function (form) { // for demo
			//$(this).submit();
        }		*/
	});	
	
})