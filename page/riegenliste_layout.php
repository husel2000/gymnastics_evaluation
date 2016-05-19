<script type="text/javascript">
function form_riegenliste_create_show() {
	$('#form_riegenliste_create')[0].reset();	
	dialog_create($('#form_riegenliste_create')[0])
}

function form_riegenliste_search_submit(data) {
		var t = $("#riegenliste_search_table").DataTable();
		t.clear().draw();
		for(var i = 0; i < data.length; i++) {
			var div = $('<div />');
			//"Edit-Button" anlegen - CSS-Class button_delete ui-button
			div.append($('<button/>', {
        		text: '',
        		class: 'button_edit ui-button',
        		id:'button_riegenliste_edit_' + data[i].id_riegenliste,
        		onclick: "window.location.href = 'index.php?page=riegenliste_edit&id_riegenliste=" + data[i].id_riegenliste + "'"
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
					<option value="">Auswählen</option>
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