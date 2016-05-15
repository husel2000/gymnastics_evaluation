<script type="text/javascript">
	function changeWettkampfTyp(ele) {
		$('#zusatz1').hide();
		$('#zusatz2').hide();
		$('#zusatz3').hide();
		$('#zusatz4').hide();
		$('#zusatz5').hide();
		
		if(ele.value == "einzel_bereich") { 
			$('#zusatz1').show();
			$('#zusatz1 > label').html("Gold");
			$('#zusatz1 > div').html($('<input/>', {
				type:'number', name:'create_zusatz1', step:0.5, class:"form-control"
    		}));

			$('#zusatz2').show();
			$('#zusatz2 > label').html("Silber");
			$('#zusatz2 > div').html($('<input/>', {
				type:'number', name:'create_zusatz2', step:0.5, class:"form-control"
    		}));			
		}
	}

	
	function form_wettkampf_create_show() {
		$('#form_wettkampf_create')[0].reset();
		$("input[name=create_geraet\\[\\]").each(function() {form_wettkampf_create_geraet_change(this)});	
		dialog_create($('#form_wettkampf_create')[0])
	}
	function form_wettkampf_create_submit(data) { 
		$('#form_wettkampf_create').parent().find(".popup_button_close").trigger("click"); //Popup-Fenster schließen
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
        		class: 'button_edit ui-button',
        		id:'button_wettkampf_edit_' + data[i].id_wettkampf,
        		onclick: "window.location.href = 'index.php?page=wettkampf_result&id_wettkampf=" + data[i].id_wettkampf + "'"
    		}));
			//"Result-Button" anlegen - CSS-Class button_delete ui-button
			div.append($('<button/>', {
        		text: '',
        		class: 'button_result ui-button',
        		id:'button_wettkampf_edit_' + data[i].id_wettkampf,
        		onclick: "window.location.href = 'index.php?page=wettkampf_finish&id_wettkampf=" + data[i].id_wettkampf + "'"
    		}));
    		
			t.row.add( [data[i].datum,data[i].bezeichnung,div.html()] ).draw();

			$('#button_wettkampf_edit_' + data[i].id_wettkampf).click({id: data[i].id_wettkampf},function(event){
				window.location.href = "index.php?page=wettkampf_result&id_wettkampf=" + event.data.id;
            });
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
				<select class="form-control" name="create_typ" id="sel1" onchange="changeWettkampfTyp(this)">
					<option value="">Auswählen</option>
	    			<option value="einzel">Einzelwettkampf</option>
	    			<option value="einzel_bereich">Einzelwettkampf (Bereich)</option>
	  			</select>
			</div>
		</div>
		<div class="form-group" style="display:none" id="zusatz1">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" id="zusatz2">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" id="zusatz3">
			<label class = "control-label col-sm-2"></label>
			<div class="col-sm-10">
			</div>
		</div>
		<div class="form-group" style="display:none" id="zusatz4">
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