<?php
	//14.06.2016 - Ma.Weber - Riege löschen
?><script type="text/javascript">
function button_riegenliste_print_ask(id_riegenliste) {
  dialog_create_select("Aktion",{ "kari": "Kampfrichter Listen" },function(res) { 
    if(res == "kari") {
      var form = $('<form />', { target:"_blank", method:"GET", action: "./page/print_a4_riegenliste_kari.php"})
			.append($('<input />', {type: "hidden", name: "id_riegenliste", value: id_riegenliste }))
			.hide().appendTo('body').submit();
    }
  });
}

function form_riegenliste_create_show() {
	$('#form_riegenliste_create')[0].reset();	
	dialog_create($('#form_riegenliste_create')[0])
}

function riegenliste_delete(id_riegenliste) { 
	dialog_create_yesno("Riege wirklich löschen?","Löschen","Abbrechen",function callback(res) {
		if(res) {
			var	form = $('<form />', { method:"POST", action: "<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>", id: "form_riegenliste_delete"}).append(
				$('<input />', {type: "hidden", name: "action", value: "riege_delete"})
			).append(
				$('<input />', {type: "hidden", name: "id_riegenliste", value: id_riegenliste})
			)
			create_ajax_form(form);
			form.submit();
		}
	});
}

function form_riegenliste_delete_submit(data) {
	var id_riegenliste = data;
	var ele = $('#button_riegenliste_delete_' + id_riegenliste).closest('tr');
	var t = $("#riegenliste_search_table").DataTable();
	t.row( ele ).remove().draw();
}

function form_riegenliste_create_submit(data) {
}

function form_riegenliste_search_submit(data) {
	var t = $("#riegenliste_search_table").DataTable();
	t.clear().draw();
	for(var i = 0; i < data.length; i++) {
		var div = $('<div />');
		//"Edit-Button" anlegen - CSS-Class button_delete ui-button
		div.append($('<button/>', {
    		text: '',
    		class: 'button button_edit2 ui-button',
    		id:'button_riegenliste_edit_' + data[i].id_riegenliste,
    		onclick: "window.location.href = 'index.php?page=riegenliste_edit&id_riegenliste=" + data[i].id_riegenliste + "'"
		}));
		//"Edit-Button" anlegen - CSS-Class button_delete ui-button
		div.append($('<button/>', {
    		text: '',
    		class: 'button button_edit ui-button',
    		onclick: "window.location.href = 'index.php?page=wettkampf_result&id_riegenliste=" + data[i].id_riegenliste + "'"
		}));
		//"Listen-Button" anlegen - CSS-Class button_delete ui-button
		div.append($('<button/>', {
    		text: '',
    		class: 'button button_list ui-button',
    		onclick: "button_riegenliste_print_ask(" + data[i].id_riegenliste + ")"
		}));
		//"Delete-Button" anlegen - CSS-Class button_delete ui-button
		div.append($('<button/>', {
    		text: '',
    		class: 'button button_delete ui-button',
    		id:'button_riegenliste_delete_' + data[i].id_riegenliste,
    		onclick: "riegenliste_delete(" +  data[i].id_riegenliste + ")"
		}));
		t.row.add( [data[i].bezeichnung,div.html()] ).draw();
	}
	css_design_button();
}

$( document ).ready(function() { $('#form_riegenliste_search').submit(); });

</script>
<!-- Riegenliste Suchen -->
<form style="display:none;" class="form-horizontal form_ajax" id="form_riegenliste_search" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="search">
	<div class="form-group" >
		<div class="col-sm-offset-2 col-sm-10">
			<input type="text" class="form-control" oninput="$('#form_riegenliste_search').submit();" name="search_text" placeholder="Suche" >
		</div>
	</div>
</form>
<!-- Riegenliste Create -->
<div style="display:none">
	<form class="form-horizontal form_ajax" id="form_riegenliste_create" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="create">
		<div class="form-group">
			<label class = "control-label col-sm-2" for="create_text">Text</label>
			<div class="col-sm-10">
				<input type="text" id="create_text" class="form-control" name="create_text" placeholder="Text">
			</div>
		</div>
		<div class="form-group" >
			<label class = "control-label col-sm-2">Wettkämpfe</label>
			<div class="col-sm-10">
				<select multiple class="form-control" name="create_id_wettkampf[]" id="sel1">
<?php $res = db_select("Select id_wettkampf,date_format(datum,'%d.%m.%Y'), bezeichnung from wettkampf order by datum desc");
	foreach($res As $wettkampf) {
		echo "<option value=\"". $wettkampf[0] ."\">" . $wettkampf[1] . " - " . $wettkampf[2] ."</option>";
	}
?>	  			</select>
			</div>
		</div>
		<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
		      	<button type="submit" class="btn btn-default">Anlegen</button>
		    </div>
  		</div>
	</form>
</div>
<h1><span class="label label-default">Riegenlisten</span></h1>
<button class="btn-default" onclick="form_riegenliste_create_show()">Neue Riegenliste anlegen</button>
<form style="display:none;" class="form-horizontal form_ajax" id="form_wettkampf_search" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="search">
	<div class="form-group" >
		<div class="col-sm-offset-2 col-sm-10">
			<input type="text" class="form-control" oninput="$('#form_wettkampf_search').submit();" name="search_text" placeholder="Suche" >
		</div>
	</div>
</form>

<table class="table_data" id="riegenliste_search_table">
	<thead>
		<tr>
			<td>Bezeichnung</td><td>Aktion</td>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>