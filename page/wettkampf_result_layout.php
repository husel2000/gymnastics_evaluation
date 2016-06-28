<?php
	//30.05.2016 - Ma.Weber - Wenn "Bereichs-Wettkampf", dann Grenzen anzeigen und veränderbar
	//28.06.2016 - Ma.Weber - Umgestellt, auch für "Riegenlisten" möglich
	
	$id_wettkampf = "";
	if(!empty($_GET['id_wettkampf']))$id_wettkampf = $_GET['id_wettkampf'];
	$id_riegenliste = "";
	if(!empty($_GET['id_riegenliste']))$id_riegenliste = $_GET['id_riegenliste'];
?>

<h1><span class="label label-default">
<?php 
	$is_riegenliste = false;
	if(!empty($id_wettkampf)) {
		$res = db_select("Select bezeichnung,geschlecht from wettkampf where id_wettkampf = ?",$id_wettkampf);
		echo "Wettkampf " . $res[0][0];
		if($res[0][1] == "m") echo " (männlich)"; elseif($res[0][1] == "w") echo " (weiblich)"; else echo " (gemischt)";
	}elseif(!empty($id_riegenliste)) {
		$res = db_select("Select riegentext from riegenliste where id_riegenliste = ?",$id_riegenliste);
		echo "Riegenliste " . $res[0][0];
		$is_riegenliste = true;
	}
?>

</span></h1>
<script type="text/javascript">	
	$(document).ready( function () {
		$("#wettkampf_turner_result_table").DataTable( {
			paging: false,
			"columnDefs": [ { "width": "10em", "targets": 0 } ]
		});
	});

	function turner_delete(turner_id) { 
		dialog_create_yesno("Turner wirklich aus Wettkampf entfernen?","Löschen","Abbrechen",function callback(res) {
			if(res) {
				$("#form_wettkampf_turner_delete > [name='id_turner']").val(turner_id);
				$('#form_wettkampf_turner_delete').submit();
				$('#form_wettkampf_turner_delete')[0].reset();
			}
		});
	}

	function form_wettkampf_turner_delete_submit(data) {
		var id_wettkampf_geraet_turner = data[0];
		var ele = $('#id_wert_ausgang_' + data[0]).closest('tr');
		var t = $("#wettkampf_turner_result_table").DataTable();
		t.row( ele ).remove().draw();
	}
	
	function form_wettkampf_result_add_turner_submit(data) {
		dialog_close('#div_form_wettkampf_result_add_turner')
		$('#form_wettkampf_result_add_turner')[0].reset();
		$('#form_wettkampf_result_reload').submit();
	}

	function wettkampf_result_edit(ele) {
		var id = ele.id.substr(ele.id.lastIndexOf('_')+1);
		
		var wert_ausgang = $('#id_wert_ausgang_'+id).val();
		var wert_abzug = $('#id_wert_abzug_'+id).val();
		var wert_gesamt =  $('#id_wert_gesamt_'+id).val();

		if(ele.id.indexOf("ausgang")>= 0) {		$('#id_wert_gesamt_'+id).val($('#id_wert_ausgang_'+id).val() - $('#id_wert_abzug_'+id).val()); }
		else if(ele.id.indexOf("abzug")>= 0) {	$('#id_wert_gesamt_'+id).val($('#id_wert_ausgang_'+id).val() - $('#id_wert_abzug_'+id).val()); }
		else if(ele.id.indexOf("gesamt")>= 0) {$('#id_wert_abzug_'+id).val($('#id_wert_ausgang_'+id).val() - $('#id_wert_gesamt_'+id).val());	}

		//Absenden der Daten
		$("#form_wettkampf_result_save > [name='id_wettkampf_geraet_turner']").val(id);
		$("#form_wettkampf_result_save > [name='wert_ausgang']").val($('#id_wert_ausgang_'+id).val());
		$("#form_wettkampf_result_save > [name='wert_abzug']").val($('#id_wert_abzug_'+id).val());

		$('#form_wettkampf_result_save').submit();
	}

	function form_wettkampf_result_save_submit(data) { }

	function form_wettkampf_result_reload_submit(data) {
		var t = $("#wettkampf_turner_result_table").DataTable();
		t.clear().draw();
		
		for(var i = 0; i < data.length; i++) {		
			var arr_col = new Array(data[i].geraet.length + 1);
			arr_col[0] = data[i].name + ", " + data[i].vorname + "<div>" + data[i].verein + "</div>";
			arr_col[1] = "<span style='display:none'>Riege</span>" + data[i].riege_no + "-"+data[i].riege_reihenfolge
			
			for(var j = 0; j < data[i].geraet.length; j++) {
				var div = $('<div />');
				div.append($('<input/>', {
					type: 'number',	step: '0.05',
					id: "id_wert_ausgang_" + data[i].geraet[j].id_wettkampf_geraet_turner,
		    		class: 'form-control input_number_result',
		    		name: 'wert_ausgang',
		    		onchange: 'wettkampf_result_edit(this)',
		    		value: data[i].geraet[j].wert_ausgang
				}));
				div.append($('<input/>', {
					type: 'number', step: '0.05',
					id: "id_wert_abzug_" + data[i].geraet[j].id_wettkampf_geraet_turner,
		    		class: 'form-control input_number_result',
		    		name: 'wert_abzug',
		    		onchange: 'wettkampf_result_edit(this)',
		    		value: data[i].geraet[j].wert_abzug
				}));
				div.append($('<input/>', {
					type: 'number',	disabled: true, step: '0.05',					
					id: "id_wert_gesamt_" + data[i].geraet[j].id_wettkampf_geraet_turner,
		    		class: 'form-control input_number_result',
		    		name: 'wert_gesamt input_number_result',
		    		onchange: 'wettkampf_result_edit(this)',
		    		value: data[i].geraet[j].wert_ausgang - data[i].geraet[j].wert_abzug
				}));

				arr_col[j+2] = div.html()
			}
			//"Delete-Button" anlegen - CSS-Class button_delete ui-button
			var div = $('<div />').append($('<button/>', {
	    		text: '',
	    		class: 'button_delete ui-button',
	    		onclick: "turner_delete(" + data[i].id_turner + ")"
			}));
			arr_col[data[i].geraet.length+2]  = div.html(); 		
			t.row.add(arr_col).draw();
		}
		css_design_button();		
	}

	function form_turner_list_submit(data) {
		$('#list_turner_add').html("");
		for(var i = 0; i < data.length; i++) {
			var li = $('<div class="checkbox"><label><input type="checkbox" name="turner[]" value="' + data[i].id_turner + '">' + data[i].name + ', ' + data[i].vorname + '</label></div>');
            
			$('#list_turner_add').append(li)
		}
		 dialog_create($('#div_form_wettkampf_result_add_turner')[0],null,false)
	}

	function user_import_xls_finish(json) {
		//Durch jeden Eintrag aus der XLS laufen und verarbeiten
		json.forEach(function(s) {
			//Prüfen Eintrag us XLS mit aktueller Person passt
			var found = false;
			var vorname = "";
			var nachname = "";
			if(s.vorname != "") vorname = s.vorname;
			if(s.nachname != "") nachname = s.nachname;
			
			$('#list_turner_add > div > label').each(function() {
				var name_list_soundex = soundex($(this).text());
				var name_xls_soundex = soundex(nachname + ", " + vorname);
				if(name_xls_soundex == name_list_soundex) {
					$(this).children("input[type='checkbox']").prop('checked',true);
					found = true;
				}
			});
			if(!found) dialog_create(nachname + ", " + vorname + " ist nocht nicht als Turner angelegt!");
		});

		dialog_close('#turner_xls_columns')
		dialog_close('#div_turner_xls_import')
	}
	
	$( document ).ready(function() {
		$('#form_wettkampf_result_reload').submit();
	});
</script>


<!-- Turner und Ergebnise des Wettkampfes laden -->
<div style="display:none">
	<form class="form_ajax" id="form_wettkampf_result_reload" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="turner_get">
		<input type="hidden" name="id_wettkampf" value="<?php echo $id_wettkampf;?>">
		<input type="hidden" name="id_riegenliste" value="<?php echo $id_riegenliste;?>">
	</form>
</div>
<!-- Turner löschen -->
<div style="display:none">
	<form class="form_ajax" id="form_wettkampf_turner_delete" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="turner_delete">
		<input type="hidden" name="id_turner" value="">
		<input type="hidden" name="id_wettkampf" value="<?php echo $id_wettkampf;?>">
	</form>
</div>
<!-- Mögliche Turner für Wettkampf laden -->
<div style="display:none">
	<form class="form_ajax" id="form_turner_list" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="turner_list">
		<input type="hidden" name="id_wettkampf" value="<?php echo $id_wettkampf;?>">
	</form>
</div>
<!-- Ergebnis senden -->
<div style="display:none">
	<form class="form_ajax" id="form_wettkampf_result_save" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="turner_geraet_save">
		<input type="hidden" name="id_wettkampf_geraet_turner" value="">
		<input type="hidden" name="wert_ausgang" value="">
		<input type="hidden" name="wert_abzug" value="">
	</form>
</div>
<!-- Turner hinzufügen -->
<div style="display:none">
	<div id="div_form_wettkampf_result_add_turner">
		<form class="form-horizontal form_ajax" id="form_wettkampf_result_add_turner" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
			<input type="hidden" name="action" value="turner_add">
			<input type="hidden" name="id_wettkampf" value="<?php echo $id_wettkampf?>">
			<input type="text" class="form-control" id="turner_search" placeholder="Suche" onkeyup="content_filter('list_turner_add',this.value)">
			<div id="list_turner_add" style="width:100%;height:300px;overflow-y: scroll;"></div>
	      	<button type="submit" class="btn btn-default">Hinzufügen</button>
	      	<button type="button" class="btn btn-default" onclick="user_import_xls_start(['nachname','vorname'],user_import_xls_finish)">Import</button>
		</form>	
	</div>
</div>

<?php //28.06.2016 - Ma.Weber
	if(!$is_riegenliste) { ?>
<button type="button" class="btn btn-default" onclick="(function(){$('#form_turner_list').submit() })()">Turner hinzufügen</button>
<?php } ?>
<table class="row-border" id="wettkampf_turner_result_table">
	<thead>
		<tr>
			<td>Turner</td><td>Riege</td>
			<?php 
			if(!$is_riegenliste) {
				$res = db_select("Select bezeichnung from wettkampf_geraet where id_wettkampf = ? order by reihenfolge",$id_wettkampf);
				foreach($res As $row) {
					echo "<td>" . $row[0] . " (Ausgang - Abzug - Ergebnis)</td>\r\n";
				}
			}elseif($is_riegenliste) {
				//Alle Wettkämpfe...
				$res = db_select("Select id_wettkampf from riegenliste_wettkampf where id_riegenliste = ?",$id_riegenliste);
				$geraete = db_select("Select bezeichnung From wettkampf_geraet where id_wettkampf = ? Order by reihenfolge",$res[0][0]);
				foreach($geraete As $geraet) {
					echo "<td>" . $geraet[0] . " (Ausgang - Abzug - Ergebnis)</td>\r\n";
				}
			}
			?>
			<td>Aktion</td>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>