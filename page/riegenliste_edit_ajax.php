<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "plausi") {
	$id_riegenliste = $_POST['id_riegenliste'];
	$res = db_select("Select distinct a.id_turner,b.id_wettkampf from wettkampf_geraet_turner a, wettkampf_geraet b Where
		a.id_wettkampf_geraet = b.id_wettkampf_geraet and b.id_wettkampf IN (Select id_wettkampf From riegenliste_wettkampf Where id_riegenliste = ?)
		and a.id_turner NOT IN (Select id_turner FROM riegenliste_liste Where id_riegenliste = ?)",$id_riegenliste,$id_riegenliste);
	$data = Array("missing" => sizeof($res));
	//Fehlenen Turner in neue Riege stecken
	$res2 = db_select("Select max(riege_no)+1 From riegenliste_liste where id_riegenliste = ?",$id_riegenliste);
	$riege_no = $res2[0][0];
	for($i = 0; $i < sizeof($res); $i++) {
		db_select("INSERT INTO riegenliste_liste(id_riegenliste,riege_no,reihenfolge,id_turner,id_wettkampf) VALUES(?,?,?,?,?)",$id_riegenliste,$riege_no,$i+1,$res[0][0],$res[0][1]);
	}
	
}elseif($_POST['action'] == "get") {
	$id_riegenliste = $_POST['id_riegenliste'];

	$arr_res = db_select("Select a.id_riegenliste_liste, a.riege_no, a.reihenfolge, b.name, b.vorname, b.verein, date_format(c.datum,'%d.%m.%Y'), c.bezeichnung
		FROM riegenliste_liste a, turner b, wettkampf c
		WHERE a.id_turner = b.id_turner and a.id_wettkampf = c.id_wettkampf And a.id_riegenliste = ?
		ORDER BY a.riege_no, a.reihenfolge", $_POST['id_riegenliste']);
	//Prüfen ob überhaupt Daten vorhanden sind
	if(sizeof($arr_res) == 0) {
		$error = True;
		$error_text = "Zuerst Initiale Einteilung vornehmen";
		return;
	}
	
	$data = Array(); //Riegen_No
	foreach($arr_res As $pos) {
		if(!isset($data[$pos[1]])) $data[$pos[1]] = Array();
		 $data[$pos[1]][$pos[2]] = Array("id_riegenliste_liste" => $pos[0],"wettkampf_datum" => $pos[6],"wettkampf_bezeichnung" => $pos[7], "name" => $pos[3],"vorname" => $pos[4],"verein" => $pos[5]);
	}
}elseif($_POST['action'] == "initial") {
	$id_riegenliste = $_POST['id_riegenliste'];
	$anz_riegen = $_POST['anzahl_riegen'];
	if($anz_riegen < 1) {
		$error = True;
		$error_text = "Riegenlisten können nur für min. 1 Riegen erstellt werden!";
		return;
	}
	db_select("Delete From riegenliste_liste Where id_riegenliste = ?", $id_riegenliste);
	
	//Alle Turner für die Riegenlisten suchen
	$arr_res = db_select("Select distinct a.id_wettkampf, b.id_turner
		FROM riegenliste_wettkampf c, wettkampf a, turner b, wettkampf_geraet_turner d, wettkampf_geraet e
		WHERE c.id_wettkampf = a.id_wettkampf
		and	  e.id_wettkampf = a.id_wettkampf
		and   e.id_wettkampf_geraet = d.id_wettkampf_geraet
		and   b.id_turner = d.id_turner 
		and   c.id_riegenliste = ? ORDER BY a.id_wettkampf, b.verein, b.name, b.vorname",$id_riegenliste);
	$arr_all = Array();
	foreach($arr_res As $akt) {
		$arr_all[] = Array("id_wettkampf"=>$akt[0], "id_turner"=>$akt[1]);
	}
	//Riegenliste zusammenstellen
	$arr_riegen_listen=Array();
	$anz_pro_riege = ceil(sizeof($arr_res)/$anz_riegen);
	for($i = 0; $i < sizeof($arr_all); $i++) {
		$riegenliste = ceil(($i+1)/$anz_pro_riege);
		//Datensatz einfügen
		$new_id = db_select("INSERT INTO riegenliste_liste(id_riegenliste,riege_no,reihenfolge,id_wettkampf,id_turner)
			VALUES(?,?,0,?,?)",$id_riegenliste, $riegenliste,$arr_all[$i]["id_wettkampf"],$arr_all[$i]["id_turner"]);
		//Reihenfolge anpassen
		$new_no = db_select("Select max(reihenfolge)+1 from riegenliste_liste where id_riegenliste = ? and riege_no = ?",$id_riegenliste, $riegenliste);
		db_select("Update riegenliste_liste set reihenfolge = ? where id_riegenliste_liste = ?",$new_no[0][0], $new_id);
	}
	$error = False;
	$error_text = "";
	$data = "Initiale Verteilung für " . sizeof($arr_all) . " Turner vorgenommen";
}elseif($_POST['action'] == "pos_switch") {
	$id_riegenliste_liste = $_POST['id_riegenliste_liste'];
	$pos = $_POST['pos'];
	if(empty($id_riegenliste_liste) ) {	$error = True; $error_text = "id_riegenliste_liste Fehlt - Interner Fehler"; return; }
	if(!is_numeric($pos) || empty($pos) || $pos == 0) {	$error = True; $error_text = "Neue Position $pos ungültig"; return; }
	
	db_select("UPDATE riegenliste_liste a, riegenliste_liste b SET 
		a.reihenfolge = b.reihenfolge
		Where a.id_riegenliste = b.id_riegenliste and a.riege_no = b.riege_no and 
		a.reihenfolge = ? and b.id_riegenliste_liste = ?",$pos,$id_riegenliste_liste);
	db_select("UPDATE riegenliste_liste SET reihenfolge = ? WHERE id_riegenliste_liste = ?",$pos,$id_riegenliste_liste);
}elseif($_POST['action'] == "riege_switch") {
	$id_riegenliste_liste = $_POST['id_riegenliste_liste'];
	$riege = $_POST['riege'];
	if(empty($id_riegenliste_liste) ) {	$error = True; $error_text = "id_riegenliste_liste Fehlt - Interner Fehler"; return; }
	if(!is_numeric($riege) || empty($riege)) {	$error = True; $error_text = "Neue Riege $riege ungültig"; return; }
	//Alte Riege - Nummerierung anpassen
	$res = db_select("Select riege_no,reihenfolge from riegenliste_liste where id_riegenliste_liste = ?",$id_riegenliste_liste);
	db_select("Update riegenliste_liste Set reihenfolge = reihenfolge-1 Where riege_no = ? and reihenfolge > ?",$res[0][0],$res[0][1]);
	//Datensatz korrigieren
	$res = db_select("Select max(reihenfolge)+1 from riegenliste_liste where riege_no = ?",$riege);
	$reihenfolge = $res[0][0]; if($reihenfolge == "") $reihenfolge = 1;
	db_select("UPDATE riegenliste_liste SET 
		reihenfolge = ?, riege_no = ? 
		Where id_riegenliste_liste = ?",$reihenfolge,$riege,$id_riegenliste_liste);
}else {
	$error = True;
	$error_text = "Unbekannte Funktion " . $_POST['action'];	
}
?>