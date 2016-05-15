//04.09.2015 - Ma.Weber

//Macht aus allen Forms, welche die Eigenscahft class= "form_ajax" haben ein "Ajax-Form".
//Im Erfolgsfall wird folgende Javascript-Funktion aufgerufen:
//		form.id + "_submit"
$(function() {
	$(".form_ajax").submit(function(e) {
		loading_show(true);
		var callback = $(this).attr('id') + "_submit";
			$.ajax({
			method: $(this).attr('method'), 
			url: $(this).attr('action'),
			data: $(this).serialize(), // serializes the form's elements.
			success: function(data)
			{
				loading_hide();
				try{
					var result = $.parseJSON(data);
					if(result.error) {
						dialog_create(result.error_text);
					}else {
						try { window[callback](result.data); } //Callback 
						catch(err) { dialog_create("Can not call Callback " + callback + "<br>" + err); }
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
});
