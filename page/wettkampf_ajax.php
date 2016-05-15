<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "search") {
	$filter = $_POST['search_text'];
	
	$sql = "Select id_wettkampf,DATE_FORMAT(datum, '%d.%m.%Y'),bezeichnung from wettkampf where bezeichnung like CONCAT('%',?,'%') order by datum desc";
	$res = db_select($sql,$filter);
	$data = Array();
	foreach($res As $row) {
		$data[] = Array("id_wettkampf"=>$row[0],"datum"=>$row[1],"bezeichnung"=>$row[2]);	
	}
}elseif($_POST['action'] == "create") {
	$datum = $_POST['create_date'];
	$name = $_POST['create_name'];
	$jahrgang_min = $_POST['create_jahrgang_min'];
	$jahrgang_max = $_POST['create_jahrgang_max'];
	$typ = $_POST['create_typ'];
	
	if(empty($typ)) { $error = True; $error_text = "Kein Wettkampf-Typ angegeben"; return; }
	if($jahrgang_max > $jahrgang_min) { $error = True; $error_text = "Der älteste Jahrgang darf nicht größer als der jüngste Jahrgang sein."; return; }
	
	$arr_geraet = $_POST['create_geraet'];
	if(!validateDate($datum)) {
		$error = True; $error_text = "Ungültiges Datum"; return;
	}
	if(empty($name)) {
		$error = True; $error_text = "Name muss angegeben werden!"; return;
	}
	if(!is_array($arr_geraet) || count($arr_geraet) == 0 || (count($arr_geraet) == 1 && $arr_geraet[0] == "")) {
		$error = True; $error_text = "Ein Gerät muss mindestens 1 Gerät beinhalten!"; return;
	}
	
	$id = db_select("INSERT INTO wettkampf (datum,bezeichnung,jahrgang_min,jahrgang_max,typ) VALUES(STR_TO_DATE(?,'%d.%m.%Y'),?,?,?,?)",$datum,$name,$jahrgang_min,$jahrgang_max,$typ);
	if(!is_numeric($id) || empty($id)) {
		$error = True; $error_text = "Fehler beim anlegen des Wettkampfes"; return;
	}
	
	for($i = 0; $i < count($arr_geraet); ++$i){
		if($arr_geraet[$i] != "") db_select("INSERT INTO wettkampf_geraet (id_wettkampf,reihenfolge,bezeichnung) VALUES(?,?,?)",$id,$i,$arr_geraet[$i]);
	}
	
	//Optionale Daten
	if(isset($_POST['create_zusatz1'])) db_select("UPDATE wettkampf SET opt_text1 = ?",$_POST['create_zusatz1']);
	if(isset($_POST['create_zusatz2'])) db_select("UPDATE wettkampf SET opt_text2 = ?",$_POST['create_zusatz2']);
	if(isset($_POST['create_zusatz3'])) db_select("UPDATE wettkampf SET opt_text3 = ?",$_POST['create_zusatz3']);
	if(isset($_POST['create_zusatz4'])) db_select("UPDATE wettkampf SET opt_text4 = ?",$_POST['create_zusatz4']);
	if(isset($_POST['create_zusatz5'])) db_select("UPDATE wettkampf SET opt_text5 = ?",$_POST['create_zusatz5']);
	$data = $id;
}
?>