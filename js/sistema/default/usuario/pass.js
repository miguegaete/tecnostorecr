

$(function(){

	jQuery('#form-pass').validate({
		rules : {
			password : {
				minlength : 5
			},
			password_2 : {
				minlength : 5,
				equalTo : "#password"
			}
		}
	});



	$("#pass-content").click('click','#guardar',function(){
		$("#form-pass").submit();
	})
	
	
	
	
});