<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "create") {
	$name = $_POST['create_text'];
	$arr_id_wettkampf = Array();
	if(isset($_POST['create_id_wettkampf'])) $arr_id_wettkampf = $_POST['create_id_wettkampf'];
	if(empty($name)) {
		$error = True;
		$error_text = "Name für Riegenliste fehlt";
		return;
	}
	if(sizeof($arr_id_wettkampf) < 1) {
		$error = True;
		$error_text = "Eine Riegenliste muss aus mindestens einem Wettkampf bestehen";
		return;
	}
	//Riegenliste anlegen
	$sql = "Insert Into riegenliste(riegentext) VALUES(?)";
	$id_riegenliste = db_select($sql,$name);
	//Riegenliste Wettkampf
	$sql = "Insert Into riegenliste_wettkampf(id_riegenliste,id_wettkampf) VALUES(?,?)";
	foreach($arr_id_wettkampf As $id_wettkampf) {
		db_select($sql,$id_riegenliste,$id_wettkampf);
	}
	$data = $id_riegenliste;
}elseif($_POST['action'] == "search") {
	$filter = $_POST['search_text'];
	
	$sql = "Select id_riegenliste,riegentext from riegenliste where riegentext like CONCAT('%',?,'%') order by id_riegenliste DESC";
	$res = db_select($sql,$filter);
	$data = Array();
	foreach($res As $row) {
		$data[] = Array("id_riegenliste"=>$row[0],"bezeichnung"=>$row[1]);
	}
}elseif($_POST['action'] == "riege_delete") {
	$sql = "Delete FROM riegenliste_liste where id_riegenliste =?";
	$res = db_select($sql,$_POST['id_riegenliste']);
	$sql = "Delete FROM riegenliste_wettkampf where id_riegenliste =?";
	$res = db_select($sql,$_POST['id_riegenliste']);
	$sql = "Delete FROM riegenliste where id_riegenliste =?";
	$res = db_select($sql,$_POST['id_riegenliste']);
	$data = $_POST['id_riegenliste'];
}
?>