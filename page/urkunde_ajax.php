<?php

$error = False;
$error_text = "";

if(empty($_POST['action'])) {
	$error = True;
	$error_text = "Interner Fehler, keine Action angegeben";
	return;
}elseif($_POST['action'] == "search") {
  $sql = "Select id_urkunde, titel from urkunde";
	$res = db_select($sql);
	$data = Array();
	foreach($res As $row) {
		$data[] = Array('id_urkunde' => $row[0], 'titel' => $row[1]);
	}
}elseif($_POST['action'] == "load") {
  $sql = "Select id_urkunde, titel,code from urkunde where id_urkunde = ?";
  $res = db_select($sql,$_POST['id_urkunde']);
	$data = Array('id_urkunde' => $res[0][0], 'titel' => $res[0][1], 'code' => $res[0][2]);
}elseif($_POST['action'] == "save") {
  $sql = "UPDATE urkunde SET code = ? where id_urkunde = ?";
  $res = db_select($sql,$_POST['code'], $_POST['id_urkunde']);
	$data = $res;
}elseif($_POST['action'] == "create") {
  $sql = "Insert Into urkunde(titel) VALUES(?)";
	$data = db_select($sql,$_POST['urkunde_titel']);
}else {
  $error = True;
	$error_text = "Interner Fehler, unbekannte Aktion";
	return;
}
?>