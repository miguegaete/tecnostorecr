$(function(){
	
	$("#form1").validate({
                rules: {
                    usuario: {
                        required: true,
                        minlength: 1
                    },
                    password: {
                        required: true ,
						minlength : 5						
                    }
                },
                messages: {
                    usuario: {
                        required: "Ingresa un usuario."
                    },
                    password: {
						required: "Por favor, ingresa una contraseña.",
						minlength: function (p, element) {

                            return "'Password' tiene que ser igual o mayor a " + p + " caracteres";

                        }
					}
                }
	});
	
});