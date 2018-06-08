<h1><span class="label label-default">Wettkampf Auswertung</span></h1>
<script type="text/javascript">
	function form_wettkampf_result_reload_submit(data) {
	  var gold = 0; var silber = 0; var bronze = 0;
		var t = $("#wettkampf_turner_finish_table").DataTable();
		t.clear().draw();
		for(var k = 0; k < data.length; k++) {
  		for(var i = 0; i < data[k].result.length; i++) {
  			for(var j=0; j < data[k].result[i].turner.length; j++) {
  				var arr_col = new Array(3);
  				arr_col[0] = data[k].result[i].bezeichnung;
  				arr_col[1] = data[k].result[i].turner[j].vorname + " " + data[k].result[i].turner[j].name;
  				arr_col[2] = data[k].result[i].turner[j].verein
  				arr_col[3] = data[k].result[i].turner[j].gesamt.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2});	
  				arr_col[4] = data[k].bezeichnung;
  				for(var l = 0;l < data[k].result[i].turner[j].geraet.length; l++) {
  				  console.log(data[k].result[i].turner[j].geraet[l])
  					arr_col[5+l] = data[k].result[i].turner[j].geraet[l].toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2});
  				}
  				
  				t.row.add(arr_col).draw();
  			}
  		}
  		if(data[k].typ == "einzel_bereich") {
  			gold += data[k].result[0].turner.length;
  			silber += data[k].result[1].turner.length;
  			bronze += data[k].result[2].turner.length;
  		}
		}
	  $('#div_info').html("Gold: " + gold + " - Silber: " + silber + " - Bronze: " + bronze);
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
			<td>Platzierung</td><td>Turner</td><td>Verein</td><td>Punkte</td><td>Wettkampf</td>
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