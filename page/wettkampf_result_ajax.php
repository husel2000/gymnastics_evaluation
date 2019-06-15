<?php
//27.05.2016 - Ma.Weber - Alter mit prüfen!
//12.06.2016 - Ma.Weber - Auswahl der Turner über Alter begrenzen
//						Aus Riegenliste löschen
//28.06.2016 - Ma.Weber - Umgestellt, auch für "Riegenlisten" möglich
//					Plausi für Abzug, Ausgang

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
	//Auch aus Riegenlise löschen
	$sql = "Delete From riegenliste_liste where id_wettkampf = ? and id_turner = ?";
	db_select($sql,$id_wettkampf,$id_turner);
}elseif($_POST['action'] == "turner_list") {
	$id_wettkampf = $_POST['id_wettkampf'];
	$sql = "Select id_turner,name,vorname,verein from turner t1, wettkampf w1 where ".
				"year(t1.geburtsdatum) between w1.jahrgang_max and w1.jahrgang_min and (t1.geschlecht = w1.geschlecht or w1.geschlecht is null) and ".
				"w1.id_wettkampf = ? and t1.id_turner NOT IN (".
				"Select distinct id_turner From wettkampf_geraet_turner Where id_wettkampf_geraet IN (".
				"Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = w1.id_wettkampf ".
				")) ORDER BY name,vorname";
	$res = db_select($sql,$id_wettkampf);
	$data = Array();
	foreach($res As $turner) {
		$data[] = Array("id_turner"=>$turner[0],"name"=>$turner[1],"vorname"=>$turner[2],"verein"=>$turner[3]);
	}
}elseif($_POST['action'] == "turner_geraet_save") {
	$id = $_POST['id_wettkampf_geraet_turner'];
	$abzug = floatval($_POST['wert_abzug']);
	$ausgang = floatval($_POST['wert_ausgang']);
	if($abzug < 0 || $abzug > 20) {
		$error = true; $error_text = "Der Abzug muss zwischen 0 und 20 liegen"; return;
	}
	if($ausgang < 0 || $ausgang > 20) {
		$error = true; $error_text = "Der Ausgang muss zwischen 0 und 20 liegen"; return;
	}
	if($abzug > $ausgang) {
		$error = true; $error_text = "Der Abzug kann nicht größer als der Ausgang sein"; return;
	} 
	$sql = "UPDATE wettkampf_geraet_turner SET wert_ausgang = ?, wert_abzug = ? where id_wettkampf_geraet_turner = ?";
	
	if(db_select($sql,$ausgang,$abzug,$id) == 1) 
		$data = Array("id" => $id, "gesamt"=> $ausgang - $abzug);
	else { $error = true; $error_text = "Fehler beim Eintraen in die Datenbank!"; }
}elseif($_POST['action'] == "turner_add") {
	$id_wettkampf = $_POST['id_wettkampf'];
	//Alters-Grenze & Geschlecht für Wettkampf suchen und alle prüfen!
	$res = db_select("Select jahrgang_min, jahrgang_max, geschlecht From wettkampf where id_wettkampf = ?",$id_wettkampf);
	$jahrgang_min = $res[0][0];
	$jahrgang_max = $res[0][1];
	$geschlecht = $res[0][2];
	foreach($_POST['turner'] As $id_turner) {
		$res = db_select("Select YEAR(geburtsdatum),name,vorname,geschlecht from turner WHERE id_turner = ?",$id_turner);
		if($res[0][0] < $jahrgang_max || $res[0][0] > $jahrgang_min || ($geschlecht != $res[0][3])) {
			$error = true;
			$error_text .= "Für Wettkampf Geschlecht $geschlect, Jahrgang " . $jahrgang_min . " - " . $jahrgang_max . " ist Turnerin " . $res[0][2] . " " . $res[0][1] . " (" . $res[0][3] ." - " .$res[0][0] . ") nicht zugelassen!";
			return;
		}
	}
	
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
	$id_riegenliste = $_POST['id_riegenliste'];
	$id_wettkaempfe = Array();
	$sql_turner = "Select t1.id_turner,name,vorname,verein, r1.riege_no, r1.reihenfolge From turner t1 ".
			"LEFT JOIN riegenliste_liste r1 ON(r1.id_riegenliste = ? and r1.id_wettkampf = ? and r1.id_turner = t1.id_turner) " .
			"Where t1.id_turner IN (" .
			"Select distinct id_turner From wettkampf_geraet_turner Where id_wettkampf_geraet IN (".
			"Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?".
			"))"; //04.06.2019 - r1.id_riegenliste = ? hinzugefügt
	$sql_turner_geraet = "Select id_wettkampf_geraet_turner,id_wettkampf_geraet,wert_ausgang,wert_abzug From wettkampf_geraet_turner where id_turner = ? and id_wettkampf_geraet in ".
			"(Select id_wettkampf_geraet From wettkampf_geraet where id_wettkampf = ?)";
	//28.06.2016 - Ma.Weber
	if(!empty($id_riegenliste)) {
		//Alle Wettkämpfe...
		$wettkaempfe = db_select("Select id_wettkampf from riegenliste_wettkampf where id_riegenliste = ?",$id_riegenliste);
		foreach($wettkaempfe As $akt_id_wettkampf) {
			$id_wettkaempfe[] = $akt_id_wettkampf[0];
		}
		//... Wettkampf-System vergleichen
		foreach($id_wettkaempfe As $wett) {
			$tmp = db_select("Select system,bezeichnung from wettkampf where id_wettkampf = ?",$wett);
			if(empty($system)) {
				$bez_1 = $tmp[0][1];
				$system = $tmp[0][0];
			}else {
				if($system != $tmp[0][0]) {
					$error = true;
					$error_text = "Für diese Riege können keine Ergebnisse zusammen erfasst werden.";
					$error_text .= $bez_1 . " - " . $tmp[0][1];
					return;
				}
			}
		}
		//... Geräte vergleichen
		$geraet = Array();
		foreach($id_wettkaempfe As $wett) {
			$akt = db_select("Select bezeichnung From wettkampf_geraet where id_wettkampf = ? Order by reihenfolge",$wett);
			$new = Array();
			for($i = 0; $i < sizeof($akt);$i++) $new[] = $akt[$i][0];
			$geraet[$wett] = $new;
		}
		
		$key_master = "";
		foreach($geraet As $key => $arr) {
			if(empty($key_master)) $key_master = $key;
			if($geraet[$key] !== $geraet[$key_master]) {
				$error = true; $error_text = "Für diese Riege können keine Ergebnisse zusammen erfasst werden.\r\n" . json_encode($geraet[$key]). " - " . json_encode($geraet[$key_master]);
				$wettkampf_bez = db_select("Select bezeichnung from wettkampf where id_wettkampf = ?",$key)[0][0];
				$wettkampf_bez2= db_select("Select bezeichnung from wettkampf where id_wettkampf = ?",$key_master)[0][0];
				$error_text .= " $wettkampf_bez - $wettkampf_bez2";
				return;
			}
		}
	}elseif(!empty($id_wettkampf)) {
		$id_wettkaempfe[] = $id_wettkampf;
	}
	
	$data = Array();
	foreach($id_wettkaempfe As $id_wettkampf) { 
		$turner = db_select($sql_turner,$id_riegenliste,$id_wettkampf,$id_wettkampf);
		foreach($turner As $row) {
			$turner = Array();
			$turner['id_turner'] = $row[0];
			$turner['name'] = $row[1];
			$turner['vorname'] = $row[2];
			$turner['verein'] = $row[3];
			$turner['riege_no'] = str_pad($row[4], 2, '0', STR_PAD_LEFT);
			$turner['riege_reihenfolge'] = str_pad($row[5], 2, '0', STR_PAD_LEFT);
			$turner['geraet'] = Array();
			$sql = 
			$res2 = db_select($sql_turner_geraet,$row[0],$id_wettkampf);
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
}
?>