<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "turner_delete") {
	$id_wettkampf = $_POST['id_wettkampf'];
	$id_turner = $_POST['id_turner'];
	$sql = "Select id_wettkampf_geraet_turner from wettkampf_geraet_turner where ".
		"id_turner = ? and id_wettkampf_geraet IN (".
		"Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?".
		")";
	$res = db_select($sql,$id_turner,$id_wettkampf);
	$data = Array();
	foreach($res As $row) {
		$sql = "Delete From wettkampf_geraet_turner where id_wettkampf_geraet_turner = ?";
		db_select($sql,$row[0]);
		$data[] = $row[0];
	}
}elseif($_POST['action'] == "turner_list") {
	$id_wettkampf = $_POST['id_wettkampf'];
	$sql = "Select id_turner,name,vorname,verein from turner where id_turner NOT IN (".
				"Select distinct id_turner From wettkampf_geraet_turner Where id_wettkampf_geraet IN (".
				"Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?".
				"))";
	$res = db_select($sql,$id_wettkampf);
	$data = Array();
	foreach($res As $turner) {
		$data[] = Array("id_turner"=>$turner[0],"name"=>$turner[1],"vorname"=>$turner[2],"verein"=>$turner[3]);
	}
}elseif($_POST['action'] == "turner_geraet_save") {
	$id = $_POST['id_wettkampf_geraet_turner'];
	$abzug = floatval($_POST['wert_abzug']);
	$ausgang = floatval($_POST['wert_ausgang']);
	$sql = "UPDATE wettkampf_geraet_turner SET wert_ausgang = ?, wert_abzug = ? where id_wettkampf_geraet_turner = ?";
	db_select($sql,$ausgang,$abzug,$id);
}elseif($_POST['action'] == "turner_add") {
	$id_wettkampf = $_POST['id_wettkampf'];
	//Geräte des Wettkampfes suchen
	$sql = "Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?";
	$res = db_select($sql,$id_wettkampf);
	foreach($res As $row) { 
		foreach($_POST['turner'] As $id_turner) {
			$check = db_select("Select id_wettkampf_geraet_turner From wettkampf_geraet_turner Where id_turner = ? " .
				"and id_wettkampf_geraet = ?",$id_turner,$row[0]);
			if(count($check) == 0) {
				$sql = "INSERT INTO wettkampf_geraet_turner(id_wettkampf_geraet,id_turner) VALUES(?,?)";
				db_select($sql,$row[0],$id_turner);
			}
		}
	}
}elseif($_POST['action'] == "turner_get") {
	$id_wettkampf = $_POST['id_wettkampf'];
	//Alle Turner in dem Wettkampf finden
	$sql = "Select id_turner,name,vorname,verein From turner Where id_turner IN (" .
			"Select distinct id_turner From wettkampf_geraet_turner Where id_wettkampf_geraet IN (".
			"Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?".
			"))";
	$res = db_select($sql,$id_wettkampf);
	$data = Array();
	foreach($res As $row) {
		$turner = Array();
		$turner['id_turner'] = $row[0];
		$turner['name'] = $row[1];
		$turner['vorname'] = $row[2];
		$turner['verein'] = $row[3];
		$turner['geraet'] = Array();
		$sql = "Select id_wettkampf_geraet_turner,id_wettkampf_geraet,wert_ausgang,wert_abzug From wettkampf_geraet_turner " .
				"Where id_turner = ?";
		$res2 = db_select($sql,$row[0]);
		foreach ($res2 As $row) {
			$geraet = Array();
			$geraet['id_wettkampf_geraet_turner'] = $row[0];
			$geraet['id_wettkampf_geraet'] = $row[1];
			$geraet['wert_ausgang'] = $row[2];
			$geraet['wert_abzug'] = $row[3];
			$turner['geraet'][] = $geraet;
		}
		$data[] = $turner;
	}
}
?>