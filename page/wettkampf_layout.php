<script type="text/javascript">
	function form_wettkampf_edit_finish_submit(data) {
		dialog_close($('#form_wettkampf_edit_finish')[0]);
		$('#form_wettkampf_search').submit()
	}
	function form_wettkampf_edit_submit(data) {
		var form_dom = $('#form_wettkampf_create').clone(false,false);
		form_dom.attr('id','form_wettkampf_edit_finish')
		//Form entsprechend ausfüllen
		form_dom.find('button[type="submit"]').text("Speichern");
		form_dom.find('input[name="action"]').val("edit");
		form_dom.find('input[name="create_date"]').val(date_format_mysql_to_ger(data.datum));
		form_dom.find('input[name="create_name"]').val(data.bezeichnung);
		form_dom.find('input[name="create_jahrgang_min"]').val(data.jahrgang_min);
		form_dom.find('input[name="create_jahrgang_max"]').val(data.jahrgang_max);
		form_dom.find('select[name="create_geschlecht"]').val(data.geschlecht);
		form_dom.find('select[name="create_typ"]').val(data.typ);
		form_dom.find('select[name="create_typ"]').attr("disabled",true);
		form_dom.find('#form_wettkampf_create_geraet_parent').closest('div[class="form-group"]').hide()
		form_dom.append($("<input />", { type: "hidden", name: "id_wettkampf", value: data.id_wettkampf }))
		create_ajax_form(form_dom);
		dialog_create(form_dom[0])
		changeWettkampfTyp(form_dom.find('select[name="create_typ"]')[0])
		form_dom.find('input[name="create_zusatz1"]').val(data.opt_text1);
		form_dom.find('input[name="create_zusatz2"]').val(data.opt_text2);
	}
	function wettkampf_edit(id_wettkampf) {
		
		form = $('<form />', { method:"POST", action: "<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>", id: "form_wettkampf_edit"}).append(
				$('<input />', {type: "hidden", name: "action", value: "get_details"})
			).append(
				$('<input />', {type: "hidden", name: "id_wettkampf", value: id_wettkampf})
			)
			create_ajax_form(form);
			form.submit();
	}
	function form_special_result_collect_area_submit(data) {
		//Ergebnisse in ein Array zusammen
		result_all[anz_curr++] = data;
		if(anz_curr != anz_target) return //Continue if all results are ready
	
		//Auswertung über alle Wettkämpfe
		anz_target = -1;
		result_view = [];
		for (var key in result_all) { //Jede Wettkampf
			for (var i in result_all[key]['result']) { //Jede Platzierung
				var bezeichnung = result_all[key]['result'][i]['bezeichnung'];
				var arr_turner = result_all[key]['result'][i]['turner'];
				if (!$.isArray(result_view[bezeichnung]))  result_view[bezeichnung] = [];
				
				result_view[bezeichnung].concat(arr_turner);
				result_view[bezeichnung] = $.merge(result_view[bezeichnung],arr_turner);
			}
		}
		//Ausgabe erzeugen
		var text = ""
		for (var key in result_view) {
			text +=key + " - " + result_view[key].length + "<br>"
		}
		dialog_create($('<div />', {html: text})[0]);
	}
	
	function function_special_result_collect_area() {
		if($('#id_select_wettkampf').val() == null) {
			dialog_create("Mindestens 1 Wettkampf auswählen");
			return;
		}
		
		//Ergebnisse für einzelnen Wettkämpfe anfragen
		result_all = [];
		anz_target = $('#id_select_wettkampf').val().length;
		anz_curr = 0;
		for(var i = 0; i < $('#id_select_wettkampf').val().length ; i++){
			form = $('<form />', { method:"POST", action: "<?php echo Nav::_link_create_ajax("wettkampf_finish") ?>", id: "form_special_result_collect_area"}).append(
				$('<input />', {type: "hidden", name: "action", value: "finish"})
			).append(
				$('<input />', {type: "hidden", name: "id_wettkampf", value: $('#id_select_wettkampf').val()[i]})	
			)
			create_ajax_form(form);
			form.submit();
		}
		
	}
	
	function function_special_select_wettkampf(callback) {
		form = $('<form />', { id: "form_select_wettkampf", submit: function(event) { event.preventDefault(); return false } , class: "form-horizontal", role: "form"}).append(
				$('<div />', { class: "form-group" }).append(
					$('<label />', { class: "control-label col-sm-2", for: "id_input_special", text:"Wettkämpfe"})
				).append(
				$('<div />', { class: "col-sm-10"}).append(
					$('<select />', { multiple: 'multiple', id: "id_select_wettkampf", class:"form-control" })
				)
			)
		).append(
			$('<div />', { class: "form-group"}).append(
				$('<div />', {class: "col-sm-offset-2 col-sm-10"}).append (
					$('<button />', { class: "btn btn-default", text:"Auswertung durchführen",
					click: callback
					})	
				)
			)
		);
		//Wettkämpfe aus aktueller Tabelle auslesen
		$('#wettkampf_search_table > tbody > tr').each(function( index, element ) {
			if($(element).find('.dataTables_empty').length > 0) {
				dialog_create("Die Suche nach Wettkämpfen mit eingestelltem Filter gab kein Ergebnis! Bitte Filter anpassen");
				return;
			} else if(index == 0) dialog_create(form[0]);
			
			var wettkampf_text = $(element).find('td:eq(0)').text() + " - " + $(element).find('td:eq(1)').text()
			var wettkampf_id =  $(element).find("button[id^='button_wettkampf_edit_']").attr('id')
			wettkampf_id = wettkampf_id.substr(wettkampf_id.lastIndexOf("_")+1);
			$('#id_select_wettkampf').append($('<option />', { text:wettkampf_text, value: wettkampf_id}))
		});
	}
	function function_special_work() { 
		dialog_close("form_special_select",null,true)
		if($('#id_select_special').val() == "result_collect_area") {
			function_special_select_wettkampf(function_special_result_collect_area)
		}
	}
	
	function function_special_show() {
		form = $('<form />', { id: "form_special_select", submit: function(event) { event.preventDefault(); return false } , class: "form-horizontal", role: "form"}).append(
			$('<div />', { class: "form-group" }).append(
				$('<label />', { class: "control-label col-sm-2", for: "id_input_special", text:"Funktion"})
			).append(
				$('<div />', { class: "col-sm-10"}).append(
					$('<select />', { id: "id_select_special", class:"form-control" }).append(
						$('<option />', { text:"Sammelauswertung Bereich", value: "result_collect_area"})
				)	)
			).append(
				$('<div />', { class: "form-group"}).append(
					$('<div />', {class: "col-sm-offset-2 col-sm-10"}).append (
						$('<button />', { class: "btn btn-default", text:"Ausführen",
						click: function_special_work
						})	
					)
				)
			)
		);
		
		dialog_create(form[0]);
	}
	function changeWettkampfTyp(ele) {
		for(i = 1; i <=5; i++) $('.zusatz' + i).hide();
		if(ele.value == "einzel_bereich") { 
			$(ele).closest('form').find('div[name="zusatz1"] > label').html("Gold");
			$(ele).closest('form').find('div[name="zusatz1"] > div').html($('<input/>', {
				type:'number', name:'create_zusatz1', step:0.5, class:"form-control"
    		})); $(ele).closest('form').find('div[name="zusatz1"]').show();

			$(ele).closest('form').find('div[name="zusatz2"] > label').html("Silber");
			$(ele).closest('form').find('div[name="zusatz2"] > div').html($('<input/>', {
				type:'number', name:'create_zusatz2', step:0.5, class:"form-control"
    		})); $(ele).closest('form').find('div[name="zusatz2"]').show();		
		}
	}
	
	function form_wettkampf_create_show() {
		$('#form_wettkampf_create')[0].reset();
		$("input[name=create_geraet\\[\\]").each(function() {form_wettkampf_create_geraet_change(this)});	
		dialog_create($('#form_wettkampf_create')[0],null,false)
	}
	function form_wettkampf_create_submit(data) { 
		dialog_close('#form_wettkampf_create')
		dialog_create("Der Wettkampf wurde erfolgreich angelegt");
		$('#form_wettkampf_search').submit();
	}

	function form_wettkampf_create_geraet_change(ele) {
		if(ele.value.length == 0) {
			if(ele.id == "") ele.parentElement.removeChild(ele);
		}else if(ele.value.length == 1){
			found = false;
			$("input[name=create_geraet\\[\\]").each(function() { if(this.value == "") found = true; });
	
		    if(!found) {
				var clone = document.getElementById("text_wettkampf_geraet_first").cloneNode(true);
				clone.value = "";
				clone.id = "";
				ele.parentElement.appendChild(clone);
		    }
		}
	}

	function form_wettkampf_search_submit(data) {
		var t = $("#wettkampf_search_table").DataTable();
		t.clear().draw();
		for(var i = 0; i < data.length; i++) {
			var div = $('<div />');
			//"Edit-Button" anlegen - CSS-Class button_delete ui-button
			div.append($('<button/>', {
        		text: '',
        		class: 'button_edit2 ui-button',
        		id:'button_wettkampf_edit_' + data[i].id_wettkampf,
        		onclick: "wettkampf_edit(" + data[i].id_wettkampf + ")"
    		}));
			//"Edit-Button" anlegen - CSS-Class button_delete ui-button
			div.append($('<button/>', {
        		text: '',
        		class: 'button_edit ui-button',
        		id:'button_wettkampf_result_' + data[i].id_wettkampf,
        		onclick: "window.location.href = 'index.php?page=wettkampf_result&id_wettkampf=" + data[i].id_wettkampf + "'"
    		}));
			//"Result-Button" anlegen - CSS-Class button_delete ui-button
			div.append($('<button/>', {
        		text: '',
        		class: 'button_result ui-button',
        		id:'button_wettkampf_finish_' + data[i].id_wettkampf,
        		onclick: "window.location.href = 'index.php?page=wettkampf_finish&id_wettkampf=" + data[i].id_wettkampf + "'"
    		}));
    		
			t.row.add( [data[i].datum,data[i].bezeichnung,div.html()] ).draw();
		}
		css_design_button();
	}

	$( document ).ready(function() { $('#form_wettkampf_search').submit(); });
</script>
<!-- Wettkampf Create -->
<div style="display:none">
	<form style="max-height:300px" class="form-horizontal form_ajax" id="form_wettkampf_create" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="create">
		<div class="form-group">
			<label class = "control-label col-sm-2" for="create_date">Datum</label>
			<div class="col-sm-10">
				<input type="date" id="create_date" class="form-control input_date" name="create_date" placeholder="DD.MM.YYYY">
			</div>
		</div>
		<div class="form-group">
			<label class = "control-label col-sm-2" for="create_name">Name</label>
			<div class="col-sm-10">
				<input type="date" class="form-control" id="create_name" name="create_name" placeholder="Wettkampfnr. & Beschreibung">
			</div>
		</div>
		<div class="form-group" >
			<label class = "control-label col-sm-2">Jahrgang (jüngester)</label>
			<div class="col-sm-10">
				<input type="number" min="1900" value="2002" class="form-control" name="create_jahrgang_min">
			</div>
		</div>
		<div class="form-group" >
			<label class = "control-label col-sm-2">Jahrgang (ältester)</label>
			<div class="col-sm-10">
				<input type="number" min="1900" value="2001" class="form-control" name="create_jahrgang_max">
			</div>
		</div>	
		<div class="form-group" >
			<label class = "control-label col-sm-2">Typ</label>
			<div class="col-sm-10">
				<select class="form-control" name="create_geschlecht">
					<option value="">Gemischt</option>
	    			<option value="m">männlich</option>
	    			<option value="w">weiblich</option>
	  			</select>
			</div>
		</div>
		<div class="form-group" >
			<label class = "control-label col-sm-2">Typ</label>
			<div class="col-sm-10">
				<select class="form-control" name="create_typ" id="sel1" onchange="changeWettkampfTyp(this)">
					<option value="">Auswählen</option>
	    			<option value="einzel">Einzelwettkampf</option>
	    			<option value="einzel_bereich">Einzelwettkampf (Bereich)</option>
	  			</select>
			</div>
		</div>
		<div class="form-group" style="display:none" name="zusatz1">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" name="zusatz2">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" name="zusatz3">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" name="zusatz4">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" id="zusatz5">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		
		<div class="form-group" >
			<label class = "control-label col-sm-2" for="create_geraet">Geräte</label>
			<div class="col-sm-10" id="form_wettkampf_create_geraet_parent">
				<input type="text" id="text_wettkampf_geraet_first" class="form-control" oninput="form_wettkampf_create_geraet_change(this)" name="create_geraet[]" placeholder="Gerätenamen" >
			</div>
		</div>
		<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
		      	<button type="submit" class="btn btn-default">Anlegen</button>
		    </div>
  		</div>
	</form>
</div>
<h1><span class="label label-default">Wettkampf</span></h1>
<button class="btn-default" onclick="form_wettkampf_create_show()">Neuen Wettkampf anlegen</button>
<button class="btn-default" onclick="function_special_show()">Sonderfunktionen</button>
<form style="display:none;" class="form-horizontal form_ajax" id="form_wettkampf_search" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="search">
	<div class="form-group" >
		<div class="col-sm-offset-2 col-sm-10">
			<input type="text" class="form-control" oninput="$('#form_wettkampf_search').submit();" name="search_text" placeholder="Suche" >
		</div>
	</div>
</form>

<table class="table_data" id="wettkampf_search_table">
	<thead>
		<tr>
			<td>Datum</td><td>Bezeichnung</td><td>Aktion</td>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>