$(function(){

$('.image-link').magnificPopup({type:'image'});
	
$(".eliminar").click(function(e){
	e.preventDefault();
	var link = $(this).attr("href");

	var txt;
	var r = confirm("¿Está seguro que desea eliminar este agregado?");
	if (r == true) {
	    window.location = link;
	} else {
	    return false;
	}

});


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
		}
	});
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});