<script type="text/javascript">
	function turner_is_double(vorname,nachname) {
		is_double = false;
		
		soundex_nachname = soundex(nachname);
		soundex_vorname = soundex(vorname);
		$("tbody > tr").each(function(i,ele){ 
			if(soundex($(ele).find("td:eq(1)").text()) === soundex_nachname) {
				if(soundex($(ele).find("td:eq(2)").text()) === soundex_vorname) {	
					is_double = true;	
				}
			}
			//Save, if Something with Soundex is Wrong
			/*if($(ele).html().toUpperCase().indexOf("" +vorname.toUpperCase() + "") != -1) {
				if($(ele).html().toUpperCase().indexOf("" + nachname.toUpperCase() + "") != -1) {
					is_double = true;
				}
			}*/
		});
		return is_double;
	}

	function turner_create_precheck() {
		if(turner_is_double($('#create_vorname').val(),$('#create_nachname').val()))
			dialog_create($('#create_vorname').val() + " " + $('#create_nachname').val() + " ist bereits angelegt");
		else
			$('#form_turner_create').submit();
	}
	
	function form_turner_create_show() {
		$('#form_turner_create')[0].reset();	
		dialog_create($('#form_turner_create')[0])
	}

	function form_turner_create_submit(data) {
		turner_table_list_add(data);
		dialog_close("#form_turner_create")
	}

	function turner_table_list_add(dataRow) {
		var t = $("#turner_table_list").DataTable();
		var div = $('<div />');
		t.row.add( [	dataRow.id_turner,
		    			dataRow.name,
		    			dataRow.vorname,
		    			dataRow.verein,
		    			dataRow.geburtsdatum,
		    			dataRow.pass,
		    			dataRow.pass_gueltig, div.html()] ).draw();
		css_design_button();
	}

	function form_turner_search_submit(data) {
		var t = $("#turner_table_list").DataTable();
		t.clear().draw();
		for(var i = 0; i < data.length; i++) {
			turner_table_list_add(data[i]);			
		}
		
	}

	function form_turner_edit_value_submit(data) {
		if(data != true) {
			dialog_create("Ups! Es ging etwas schief. Bitte Seite neu laden!");
		}
	}

	function user_import_xls_finish(json_data) {
		json_data.forEach(function(ele) {
			$('#form_turner_create')[0].reset();
			$('#create_nachname').val(ele.nachname);
			$('#create_vorname').val(ele.vorname);
			$('#create_verein').val(ele.verein);
			if(typeof(ele.geburtsdatu) != "undefined") {
				if(ele.geburtsdatum.match(/..\/..\/..../)) {
					ele.geburtsdatum = ele.geburtsdatum.replace(/\//g,".");
				} 
			}
			$('#create_geburtsdatum').val(ele.geburtsdatum);
			$('#create_pass').val(ele.pass);
			$('#create_pass_gueltig').val(ele.pass_gueltig);
			turner_create_precheck();
		});
	}

	$( document ).ready(function() { 
		$('#form_turner_search').submit();
	}); 		
</script>
<!-- Turner suchen -->
<form style="display:none" class="form_ajax" id="form_turner_search" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="search">
	<input type="text" oninput="$('#form_wettkampf_search').submit();" name="search_text">
</form>
<!-- Turner editieren (über Zelle) -->
<form style="display:none" class="form_ajax" id="form_turner_edit_value" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="edit_value">
	<input type="hidden" name="id">
	<input type="hidden" name="name">
	<input type="hidden" name="value">
</form>
<!-- Turner anlegen -->
<div style="display:none">
	<form class="form-horizontal form_ajax" id="form_turner_create" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="create">
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_nachname">Nachname</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="create_nachname" name="name" placeholder="Nachname">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_vorname">Vorname</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="create_vorname" name="vorname" placeholder="Vorname">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_verein">Verein</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="create_verein" name="verein" placeholder="verein">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_geburtsdatum">Geburtsdatum</label>
			<div class="col-sm-10">
				<input type="date" class="form-control input_date" id="create_geburtsdatum" name="geburtsdatum" placeholder="dd.mm.yyyy">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_pass">Pass</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="create_pass" name="pass" placeholder="abc-1234">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="create_pass_gueltig">Gültig</label>
			<div class="col-sm-10">
				<input type="date" class="form-control input_date" id="create_pass_gueltig" name="pass_gueltig" placeholder="dd.mm.yyyy">
			</div>
		</div>
		<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
		      	<button type="button" class="btn btn-default" onclick="turner_create_precheck()">Anlegen</button>
		    </div>
  		</div>
	</form>
</div>
<!-- Start -->
<h1><span class="label label-default">Turner</span></h1>
<button type="button" class="btn btn-default" onclick="form_turner_create_show()">Neuen Turner anlegen</button>
<button type="button" class="btn btn-default" onclick="user_import_xls_start(['nachname','vorname','verein','geburtsdatum','pass','pass_gueltig'],user_import_xls_finish)">Import</button>
<table data-formid="form_turner_edit_value" class="table_data_edit" id="turner_table_list">
	<thead>
		<tr>
			<td>ID</td>
			<td data-form-fieldname="name">Name</td>
			<td data-form-fieldname="vorname">Vorname</td>
			<td data-form-fieldname="verein">Verein</td>
			<td data-form-fieldname="geburtsdatum">Jahrgang</td>
			<td data-form-fieldname="pass">Pass Nr.</td>
			<td data-form-fieldname="pass_gueltig">Pass Jahr</td>
			<td>Aktion</td>
		</tr>
	</thead>
	<tbody>
		<!--  <tr data-form-id="tt"><td headers="name">Name</td><td headers="vorname">Vorname</td><td>Verein</td><td>Jahrgang</td><td>Pass Nr.</td><td>Pass Jahr<td>Aktion</td></tr> -->
	</tbody>
</table>

