<?php

$error = False;
$error_text = "";

function get_turner_by_id($id_turner) {
	$sql = "Select id_turner,name,vorname,geschlecht,verein,DATE_FORMAT(geburtsdatum, '%d.%m.%Y'),pass,DATE_FORMAT(pass_gueltig, '%d.%m.%Y') from turner ".
		"where id_turner = ? ";
	$res = db_select($sql,$id_turner);
	if(count($res) == 1) {
		$row = $res[0];
		if($row[7] == "00.00.0000") $row[7] = "";
		return Array("id_turner"=>$row[0],"name"=>$row[1],"vorname"=>$row[2],"geschlecht"=>$row[3],"verein"=>$row[4],"geburtsdatum"=>$row[5],"pass"=>$row[6],"pass_gueltig"=>$row[7]);
	}
}

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "search") {
	$filter = $_POST['search_text'];
	
	$sql = "Select id_turner from turner ".
		"where Upper(name) like CONCAT('%',?,'%') or Upper(vorname) like CONCAT('%',?,'%') or Upper(verein) like CONCAT('%',?,'%') or Upper(DATE_FORMAT(geburtsdatum, '%d.%m.%Y')) like CONCAT('%',?,'%') ".
		"order by name,vorname desc";
	$res = db_select($sql,strtoupper($filter),strtoupper($filter),strtoupper($filter),strtoupper($filter));
	$data = Array();
	foreach($res As $row) {
		$data[] = get_turner_by_id($row[0]);	
	}
}elseif($_POST['action'] == "edit_value") {	
	$id = $_POST['id'];
	$name = $_POST['name'];
	$value = trim($_POST['value']);
	
	if($name == "geschlecht") {
		if(strtolower($value) != "w" && strtolower($value) != "m") {
			$error = true;
			$error_text = "Für Geschlecht sind nur die Angaben 'm' oder 'w' erlaubt";
			return;
		}
	}
	if($name == "geburtsdatum" || $name == "pass_gueltig") {
		$sql = "UPDATE turner SET ". $name . " = str_to_date(?,'%d.%m.%Y') WHERE id_turner = ?";
	}else {
		$sql = "UPDATE turner SET ". $name . " = ? WHERE id_turner = ?";
	}
	
	$res = db_select($sql,$value,$id);
	$data = $res;	
}elseif($_POST['action'] == "create") {
	$name = trim($_POST['nachname']);
	$vorname = trim($_POST['vorname']);
	$verein = trim($_POST['verein']);
	$geburtsdatum = trim($_POST['geburtsdatum']);
	$pass = trim($_POST['pass']);
	$pass_gueltig = trim($_POST['pass_gueltig']);
	$geschlecht = trim($_POST['geschlecht']);
	
	if($name == "" || $vorname == "" || $verein == "" || $geschlecht == "") {
		$error = true;
		$error_text = "Vorname, Nachname, Verein, Geschlecht und Geburtsdatum sind Pflichtfelder";
		
	}
	
	if($error == false) {
		$sql = "INSERT INTO turner(name,vorname,geschlecht,verein,geburtsdatum,pass,pass_gueltig) VALUES(?,?,?,?,STR_TO_DATE(?,'%d.%m.%Y'),?,STR_TO_DATE(?,'%d.%m.%Y'))";
		$res = db_select($sql,$name,$vorname,$geschlecht,$verein,$geburtsdatum,$pass,$pass_gueltig);
		$data = get_turner_by_id($res);
	}
}
?>