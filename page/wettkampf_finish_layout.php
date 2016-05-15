<h1><span class="label label-default">Wettkampf Auswertung</span></h1>
<script type="text/javascript">
	function form_wettkampf_result_reload_submit(data) {
		var t = $("#wettkampf_turner_finish_table").DataTable();
		t.clear().draw();
		for(var i = 0; i < data.result.length; i++) {		
			for(var j=0; j < data.result[i].turner.length; j++) {
				var arr_col = new Array(3);
				arr_col[0] = data.result[i].bezeichnung;
				arr_col[1] = data.result[i].turner[j].vorname + " " + data.result[i].turner[j].name;
				arr_col[2] = data.result[i].turner[j].verein
				arr_col[3] = data.result[i].turner[j].gesamt.toFixed(2);	
				for(var k = 0;k < data.result[i].turner[j].geraet.length; k++) {
					arr_col[4+k] = data.result[i].turner[j].geraet[k];
				}	
				t.row.add(arr_col).draw();
			}
		}
		if(data.typ == "einzel_bereich") {
			var gold = data.result[0].turner.length;
			var silber  = data.result[1].turner.length;
			var bronze = data.result[2].turner.length;
			$('#div_info').html("Gold: " + gold + " - Silber: " + silber + " - Bronze: " + bronze);

		}
		css_design_button();		
	}

	$( document ).ready(function() {
		$('#form_wettkampf_result_reload').submit();
	});
</script>
<!-- Turner und Ergebnise des Wettkampfes laden -->
<div style="display:none">
	<form class="form_ajax" id="form_wettkampf_result_reload" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
		<input type="hidden" name="action" value="finish">
		<input type="hidden" name="id_wettkampf" value="<?php echo $_GET['id_wettkampf'];?>">
	</form>
</div>
<div id="div_info"></div>
<table class="table_data" id="wettkampf_turner_finish_table">
	<thead>
		<tr>
			<td>Platzierung</td><td>Turner</td><td>Verein</td><td>Punkte</td>
			<?php 
			$res = db_select("Select id_wettkampf_geraet, bezeichnung from wettkampf_geraet where id_wettkampf = ? order by reihenfolge",$_GET['id_wettkampf']);
			foreach($res As $row) {
				echo "<td>" . $row[1] . "</td>\r\n";
			}
			?>
		</tr>
	</thead>
	<tbody>
		
	</tbody>
</table>