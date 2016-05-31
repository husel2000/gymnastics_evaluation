<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
	
}elseif($_POST['action'] == "finish") {
	$id_wettkampf = $_POST['id_wettkampf'];
	
	//Wettkampf-Typ ermitteln
	$res = db_select("Select typ,opt_text1,opt_text2,bezeichnung From wettkampf where id_wettkampf = ?",$id_wettkampf);
	$arr_return = Array("typ"=>$res[0][0],"id_wettkampf"=>$id_wettkampf,"bezeichnung"=>$res[0][3],"result"=>Array());
	$arr_result = Array();
	if($res[0][0] == "einzel_bereich") {
		$gold_min = $res[0][1];
		$silver_min = $res[0][2];
		
		$sql = "Select a.id_turner, b.name,b.vorname,b.verein, IFNULL(sum(a.wert_ausgang-a.wert_abzug),0) from wettkampf_geraet_turner a, turner b where " . 
				"a.id_turner = b.id_turner and a.id_wettkampf_geraet IN (" .
					"Select id_wettkampf_geraet From wettkampf_geraet Where id_wettkampf = ?" .
				") group by a.id_turner, b.name, b.vorname order by sum(a.wert_ausgang-a.wert_abzug) desc";
		$res = db_select($sql,$id_wettkampf);
		
		//3 Arrays (Gold,Silber,Bronze)
		$arr_result[0] = Array("bezeichnung"=>"Gold", "turner"=>Array());$arr_result[1] = Array("bezeichnung"=>"Silber","turner"=>Array());$arr_result[2] = Array("bezeichnung"=>"Bronze","turner"=>Array());
		foreach($res As $turner) {
				$new = Array("id_turner"=>$turner[0],"verein"=>$turner[3],"name"=>$turner[1], "vorname"=>$turner[2], "gesamt"=>$turner[4], "geraet" =>Array());
				$sql = "Select b.reihenfolge,IFNULL(a.wert_ausgang-a.wert_abzug,0) from wettkampf_geraet b Right Join wettkampf_geraet_turner a " .
						"ON a.id_wettkampf_geraet = b.id_wettkampf_geraet and a.id_turner = ? " .
						"where b.id_wettkampf = ? order by b.reihenfolge";
				$res2 = db_select($sql, $turner[0], $id_wettkampf);
				foreach($res2 As $geraet) {
					$new['geraet'][] = $geraet[1];
				}
				if($new['gesamt'] >= $gold_min) $arr_result[0]['turner'][] = $new;
				elseif($new['gesamt'] >= $silver_min) $arr_result[1]['turner'][] = $new; 
				else $arr_result[2]['turner'][] = $new;			
		}
		$arr_return['result'] = $arr_result;
		$data = $arr_return;
	}
}
?>