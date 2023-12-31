<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "get_details") {
	if(!empty($_POST['id_riegenliste'])) {
	  $id_riegenliste = $_POST['id_riegenliste'];
	  $res = db_select("Select riegentext from riegenliste where id_riegenliste = ?",$id_riegenliste);
	  $data = Array("bezeichnung"=>$res[0][0]);
	  $res= db_select("Select distinct w.system from wettkampf w,riegenliste_wettkampf rw where rw.id_riegenliste = ? and rw.id_wettkampf = w.id_wettkampf",$id_riegenliste);
	  if(sizeof($res) > 1 ) { $error = true;$error_text="Unmögliche Zusammenfassung"; } 
	  else $data['system'] = $res[0][0];
	}else {
	  $id_wettkampf = $_POST['id_wettkampf'];
  	$res = db_select("Select datum,bezeichnung,typ,jahrgang_min, jahrgang_max, opt_text1, opt_text2, id_wettkampf,geschlecht,system from wettkampf where id_wettkampf = ?",$id_wettkampf);
	  $data = Array("datum" => $res[0][0], "bezeichnung" => $res[0][1], "typ" => $res[0][2], "jahrgang_min" => $res[0][3], "jahrgang_max" => $res[0][4],"geschlecht" => $res[0][8], "opt_text1" => $res[0][5], "opt_text2" => $res[0][6], "id_wettkampf" => $res[0][7], "system" => $res[0][9]);
	}
}elseif($_POST['action'] == "search") {
	$filter = $_POST['search_text'];
	
	$sql = "Select id_wettkampf,DATE_FORMAT(datum, '%d.%m.%Y'),bezeichnung,geschlecht from wettkampf where bezeichnung like CONCAT('%',?,'%') order by datum desc,bezeichnung desc";
	$res = db_select($sql,$filter);
	$data = Array();
	foreach($res As $row) {
		$bez = $row[2];
		if($row[3] == "m") $bez .= " (männlich)";
		elseif($row[3] == "w") $bez .= " (weiblich)";
		else $bez .= " (gemischt)";
		
		$data[] = Array("id_wettkampf"=>$row[0],"datum"=>$row[1],"bezeichnung"=>$bez);	
	}
}elseif($_POST['action'] == "create" || $_POST['action'] == "edit") {
	$datum = $_POST['create_date'];
	$name = $_POST['create_name'];
	$jahrgang_min = $_POST['create_jahrgang_min'];
	$jahrgang_max = $_POST['create_jahrgang_max'];
	$geschlecht = $_POST['create_geschlecht'];
	$system = $_POST['create_system'];
	$typ = ""; if(!empty($_POST['create_typ'])) $typ = $_POST['create_typ'];
	$id_wettkampf = "";	if(!empty($_POST['id_wettkampf'])) $id_wettkampf = $_POST['id_wettkampf'];
	
	
	if($jahrgang_max > $jahrgang_min) { $error = True; $error_text = "Der älteste Jahrgang darf nicht größer als der jüngste Jahrgang sein."; return; }
	
	$arr_geraet = $_POST['create_geraet'];
	if(!validateDate($datum)) { $error = True; $error_text = "Ungültiges Datum"; return; }
	if(empty($name)) { $error = True; $error_text = "Name muss angegeben werden!"; return; }
	
	//Neu-Anlange
	if(empty($id_wettkampf)) {
		if(empty($typ) && empty($id_wettkampf)) { $error = True; $error_text = "Kein Wettkampf-Typ angegeben"; return; }
		if(!is_array($arr_geraet) || count($arr_geraet) == 0 || (count($arr_geraet) == 1 && $arr_geraet[0] == "")) { $error = True; $error_text = "Ein Gerät muss mindestens 1 Gerät beinhalten!"; return; }
		
		$id = db_select("INSERT INTO wettkampf (datum,bezeichnung,jahrgang_min,jahrgang_max,geschlecht,typ,system) VALUES(STR_TO_DATE(?,'%d.%m.%Y'),?,?,?,?,?,?)",$datum,$name,$jahrgang_min,$jahrgang_max,$geschlecht,$typ,$system);
		if(!is_numeric($id) || empty($id)) { $error = True; $error_text = "Fehler beim anlegen des Wettkampfes"; return; }
		
		for($i = 0; $i < count($arr_geraet); ++$i){
			if($arr_geraet[$i] != "") db_select("INSERT INTO wettkampf_geraet (id_wettkampf,reihenfolge,bezeichnung) VALUES(?,?,?)",$id,$i,$arr_geraet[$i]);
		}
	}else {
		db_select("UPDATE wettkampf SET datum = STR_TO_DATE(?,'%d.%m.%Y'), bezeichnung = ?, jahrgang_min = ?, jahrgang_max = ?,geschlecht = ?, system = ? WHERE id_wettkampf = ?",$datum,$name,$jahrgang_min, $jahrgang_max,$geschlecht,$system, $id);
	}
	
	//Optionale Daten
	if(isset($_POST['create_zusatz1'])) db_select("UPDATE wettkampf SET opt_text1 = ? Where id_wettkampf = ?",$_POST['create_zusatz1'],$id);
	if(isset($_POST['create_zusatz2'])) db_select("UPDATE wettkampf SET opt_text2 = ?  Where id_wettkampf = ?",$_POST['create_zusatz2'],$id);
	if(isset($_POST['create_zusatz3'])) db_select("UPDATE wettkampf SET opt_text3 = ? Where id_wettkampf = ?",$_POST['create_zusatz3'],$id);
	if(isset($_POST['create_zusatz4'])) db_select("UPDATE wettkampf SET opt_text4 = ? Where id_wettkampf = ?",$_POST['create_zusatz4'],$id);
	if(isset($_POST['create_zusatz5'])) db_select("UPDATE wettkampf SET opt_text5 = ? Where id_wettkampf = ?",$_POST['create_zusatz5'],$id);
	$data = $id;
}
?>