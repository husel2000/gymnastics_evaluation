//04.09.2015 - Ma.Weber

//Macht aus allen Forms, welche die Eigenscahft class= "form_ajax" haben ein "Ajax-Form".
//Im Erfolgsfall wird folgende Javascript-Funktion aufgerufen:
//		form.id + "_submit"

function create_ajax_form(the_form) {
		the_form.submit(function(e) {
		loading_show(true);
		var callback = the_form.attr('id') + "_submit";
			$.ajax({
			method: the_form.attr('method'), 
			url: the_form.attr('action'),
			data: the_form.serialize(), // serializes the form's elements.
			success: function(data)
			{
				loading_hide();
				try{
					var result = $.parseJSON(data);
					if(result.error) {
						dialog_create(result.error_text);
					}else {
						try { window[callback](result.data); } //Callback 
						catch(err) { dialog_create("Can not call Callback " + callback + "<br>" + err + "<br>" + err.stack); }
					}
				}catch(err){
					dialog_create("Die Antwort vom Server ist nicht gültig:<br>" + $("<div>").text(data).html() + "<br>" + err);
				}
			},
			error: function() {
				loading_hide();
				dialog_create("Der AJAX-Request schlug fehlt!");
			}
		});

		e.preventDefault();
	});
}

$(function() {
	$(".form_ajax").each(function() { create_ajax_form($(this)); } )
});
