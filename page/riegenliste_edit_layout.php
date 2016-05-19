<?php
	$res = db_select("Select riegentext From riegenliste where id_riegenliste = ?",$_GET['id_riegenliste']);
	$riegentext = $res[0][0];
?>
<script type="text/javascript">
function form_riegenliste_plausi_submit(data) {
	if(data['missing'] == 0) {
		dialog_create("Keine Fehler gefunden");
	}else {
		if(data['missing'] == 1) {
			dialog_create("Es wurde " + data['missing'] + " fehlender Datensatz hinzugefügt");
		}else {
			dialog_create("Es wurden " + data['missing'] + " fehlende Datensätze hinzugefügt");
		}
		$('#form_riegenliste_get').submit();
	}
}
function form_riegenliste_initial_submit(data) {
	dialog_create(data);
	$('#form_riegenliste_get').submit();
}

function table_riegenliste_riege_switch(ele,typ) {
	id_riegenlsite_liste = $(ele).closest('tr').attr('id');
	riege_akt = parseInt($(ele).closest('tr').children('td:first').children('span').html());
	riege_nxt = riege_akt;
	if(typ == "-") riege_nxt = riege_nxt - 1;
	if(typ == "+") riege_nxt = riege_nxt + 1;
	
		//Form anpassen
	$("#form_riege_switch > input[name='id_riegenliste_liste']").val(id_riegenlsite_liste);
	$("#form_riege_switch > input[name='riege']").val(riege_nxt);
	$("#form_riege_switch").submit();
}

function table_riegenliste_pos_switch(ele,typ) {
	$("#form_pos_switch")[0].reset();	
	$("#form_riege_switch")[0].reset();
	id_riegenliste_liste = $(ele).closest('tr').attr('id');
	pos_akt = parseInt($(ele).closest('tr').children('td').eq(1).children('span').html());
	pos_nxt = pos_akt;
	if(typ == "-") pos_nxt = pos_nxt - 1;
	if(typ == "+") pos_nxt = pos_nxt + 1;
	
	//Form anpassen
	$("#form_pos_switch > input[name='id_riegenliste_liste']").val(id_riegenliste_liste);
	$("#form_pos_switch > input[name='pos']").val(pos_nxt);
	$("#form_pos_switch").submit();
}
function table_riegenliste_riege_switch(ele,typ) {
	$("#form_pos_switch")[0].reset();	
	$("#form_riege_switch")[0].reset();
	id_riegenliste_liste = $(ele).closest('tr').attr('id');
	riege_akt = parseInt($(ele).closest('tr').children('td').eq(0).children('span').html());
	riege_nxt = riege_akt;
	if(typ == "-") riege_nxt = riege_nxt - 1;
	if(typ == "+") riege_nxt = riege_nxt + 1;
	
	//Form anpassen
	$("#form_riege_switch > input[name='id_riegenliste_liste']").val(id_riegenliste_liste);
	$("#form_riege_switch > input[name='riege']").val(riege_nxt);
	
	$("#form_riege_switch").submit();
}

function form_pos_switch_submit() {
	$('#form_riegenliste_get').submit();
}

function form_riege_switch_submit() {
	$('#form_riegenliste_get').submit();
}

function form_riegenliste_get_submit(data) {
	var t = $("#table_riegenliste").DataTable();
	t.clear().draw();
	for (var riege_liste in data){
	//for(var riege_liste = 0; riege_liste < data.length; riege_liste++) {
		Object.keys(data[riege_liste]).forEach(function (key) {
			var divRiege = $('<div />');
			divRiege.append($('<button/>', {
        		class: 'button_up',
        		onclick: "table_riegenliste_riege_switch(this,'-')"
    		}));
    		divRiege.append($('<span />', { text: riege_liste}));
    		divRiege.append($('<button/>', {
        		class: 'button_down',
        		onclick: "table_riegenliste_riege_switch(this,'+')"
    		}));
    		
    		var divPos = $('<div />');
			divPos.append($('<button/>', {
        		class: 'button_up',
        		onclick: "table_riegenliste_pos_switch(this,'-')"
    		}));
    		divPos.append($('<span />', { text: key}));
    		divPos.append($('<button/>', {
        		class: 'button_down',
        		onclick: "table_riegenliste_pos_switch(this,'+')"
    		}));
			
			var row = t.row.add( [
				divRiege.html(),
				divPos.html(),
				data[riege_liste][key]['wettkampf_bezeichnung'],data[riege_liste][key]['verein']
				,data[riege_liste][key]['name'] + ", " + data[riege_liste][key]['vorname']
				] ).draw().node();
			row.id = data[riege_liste][key]['id_riegenliste_liste'];
		});
	}
	
	//Highlight bei Änderung
	if($("#form_pos_switch > input[name='id_riegenliste_liste']").val() != "") {
		tr = $('#' + $("#form_pos_switch > input[name='id_riegenliste_liste']").val())
	}else if($("#form_riege_switch > input[name='id_riegenliste_liste']").val() != "") {
		tr = $('#' + $("#form_riege_switch > input[name='id_riegenliste_liste']").val())
	}
	
	if(typeof(tr) != "undefined") {
		classes = tr.attr("class");
		tr.removeClass("odd even");
		tr.effect("highlight", {}, 1500,function(){ tr.addClass(classes);  }) ;
	}
}

function form_riegenliste_initial_show() {
	$('#form_riegenliste_initial')[0].reset();
	dialog_create($('#form_riegenliste_initial')[0])
}
$( document ).ready(function() { $('#form_riegenliste_get').submit(); });

</script>
<!-- Riegenliste Plausi -->
<form style="display:none;" class="form-horizontal form_ajax" id="form_riegenliste_plausi" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="plausi">
	<input type="hidden" name="id_riegenliste" value="<?php echo $_GET['id_riegenliste']; ?>">
</form>
<!-- Riegenliste laden -->
<form style="display:none;" class="form-horizontal form_ajax" id="form_riegenliste_get" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="get">
	<input type="hidden" name="id_riegenliste" value="<?php echo $_GET['id_riegenliste']; ?>">
</form>
<!-- Riegenliste Riege wechseln -->
<form style="display:none;" class="form-horizontal form_ajax" id="form_riege_switch" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="riege_switch">
	<input type="hidden" name="id_riegenliste_liste" value="">
	<input type="hidden" name="riege" value="">
</form>
<!-- Riegenliste Position wechseln -->
<form style="display:none;" class="form-horizontal form_ajax" id="form_pos_switch" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
	<input type="hidden" name="action" value="pos_switch">
	<input type="hidden" name="id_riegenliste_liste" value="">
	<input type="hidden" name="pos" value="">
</form>
<!-- Riegenliste initalisieren -->
<div style="display:none">
	<form class="form-horizontal form_ajax" id="form_riegenliste_initial" role="form" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="initial">
		<input type="hidden" name="id_riegenliste" value="<?php echo $_GET['id_riegenliste']; ?>">
		<div class="form-group" >
			<label class = "control-label col-sm-2" for="">Anzahl Riegen</label>
			<div class="col-sm-10">
				<input type="number" class="form-control" name="anzahl_riegen" placeholder="" >
			</div>
		</div>
		<button type="submit" class="btn btn-default">Initiale Verteilung durchführen</button>
	</form>
</div>
<h1><span class="label label-default">Riegenliste <?php echo $riegentext; ?> bearbeiten</span></h1>
<button class="btn-default" onclick="form_riegenliste_initial_show()">Initiale Verteilung</button>
<button class="btn-default" onclick="$('#form_riegenliste_plausi').submit()">Plausibilisieren</button>
<table class="table_data" id="table_riegenliste">
	<thead>
		<tr>
			<td>Riege</td><td>Position</td><td>Wettkampf</td><td>Verein</td><td>Name, Vorname</td>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>