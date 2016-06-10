function dataTable_edit_onKeyUp(e,ele) { if (e.which == 13) dataTable_edit_inline_off($(ele).parent()); }

function dataTable_edit_inline_off(ele) {
	//Prüfen, ob schon Edit-Modus;
	if(!$(ele).hasClass("dataTable_edit_active")) return;
	$(ele).removeClass("dataTable_edit_active");
	//Alter Wert
	var oldValue = $(ele).children().prop("defaultValue");
	//Neuen, eingegebenen Wert
	var newValue = $(ele).children()[0].value;
	$(ele).html(newValue);
	if(newValue == oldValue) return;
	
	//Feld-Name ermitteln
	var table = $(ele).closest('table');
	var table_head_row = $('thead',table).children()[0];
	var fieldIndex = $(ele).index();
	var fieldName = $(table_head_row).children()[fieldIndex].getAttribute('data-form-fieldname');
	//ID des Datensatzes ermitteln	
	var id = $(ele).parent().find("td").eq(0).html();
	//Form ermitteln, das abgesendet werden soll	
	var formid = $(table)[0].getAttribute('data-formid');
	
	$("#" + formid + " > [name='id']").val(id);
	$("#" + formid + " > [name='name']").val(fieldName);
	$("#" + formid + " > [name='value']").val(newValue);
	$("#" + formid).submit();
}

function dataTable_edit_inline_on(ele) {
	//Prüfen, ob schon im Edit-Modus;
	if($(ele).hasClass("dataTable_edit_active")) return;
	$(ele).addClass("dataTable_edit_active");
	//Aktuellen Wert merken
    var value = $(ele).html();
    //Input-Feld holen
	var input = $('<input/>',{
	    type: 'text',
	    value: value,
	    class: 'form-control',
	    onkeyup: 'dataTable_edit_onKeyUp(event,this)'
    });
    $(ele).html(input);
}

function dataTable_edit_init() {
	// On-Click TD, make Input Textbox
	$(".table_data_edit").DataTable( {
			paging: false,
			dom: 'Bfrtip',
			buttons: [ 'csv', 'excel' ],
			"aaSorting": [],
			"createdRow": function ( row, data, index ) {
				$('td',row).eq(0).hide();
				
				$('td',row).click(function (e) { dataTable_edit_inline_on(this)} );
			}
		});
	$('.table_data_edit tbody td').click(function (e) { dataTable_edit_inline_on(this)} );
	$('.table_data_edit thead td:first').hide()
}

$(document).ready( function () {
	dataTable_edit_init()
});