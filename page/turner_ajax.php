<?php

$error = False;
$error_text = "";

function get_turner_by_id($id_turner) {
	$sql = "Select id_turner,name,vorname,verein,DATE_FORMAT(geburtsdatum, '%d.%m.%Y'),pass,DATE_FORMAT(pass_gueltig, '%d.%m.%Y') from turner ".
		"where id_turner = ? ";
	$res = db_select($sql,$id_turner);
	if(count($res) == 1) {
		$row = $res[0];
		return Array("id_turner"=>$row[0],"name"=>$row[1],"vorname"=>$row[2],"verein"=>$row[3],"geburtsdatum"=>$row[4],"pass"=>$row[5],"pass_gueltig"=>$row[6]);
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
	if($name == "geburtsdatum" || $name == "pass_gueltig") {
		if(strlen($value) == 10) {
			$value = substr($value,-4) . "-" . substr($value,3,2) . "-" . substr($value,0,2) ; 
		}
	}
	$sql = "UPDATE turner SET ". $name . " = ? WHERE id_turner = ?";
	$res = db_select($sql,$value,$id);
	$data = $res;	
}elseif($_POST['action'] == "create") {
	$name = trim($_POST['name']);
	$vorname = trim($_POST['vorname']);
	$verein = trim($_POST['verein']);
	$geburtsdatum = trim($_POST['geburtsdatum']);
	$pass = trim($_POST['pass']);
	$pass_gueltig = trim($_POST['pass_gueltig']);
	
	$sql = "INSERT INTO turner(name,vorname,verein,geburtsdatum,pass,pass_gueltig) VALUES(?,?,?,STR_TO_DATE(?,'%d.%m.%Y'),?,STR_TO_DATE(?,'%d.%m.%Y'))";
	$res = db_select($sql,$name,$vorname,$verein,$geburtsdatum,$pass,$pass_gueltig);
	$data = get_turner_by_id($res);
}
?>