<?php

//30.04.2014 - Ma.Weber - Create
//28.10.2014 - Ma.Weber - db_select auch Parameter double ermöglichen
//08.09.2015 - Ma.Weber - SQL: Null sollte in SQL mit <=> geprüft werden.

/*
Rules:
- Functions only in Lower Case (like all php-Functions)
- For better Readability use _ to split different words in Function-Names
*/

///////////////////////////////////////////////////
/////////////// Database-Functions ////////////////
///////////////////////////////////////////////////

/** 
 * Erzeugt eine Datenbankverbindung
 * @return mysqli Objekt
 */
function db_connect() {
	return mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
}

function db_date_to_timestamp($date) {
	$timestamp = db_select("select unix_timestamp(?);",$date);
	return $timestamp[0][0];
}

function db_timestamp_to_date($timestamp) {
	return date("y-m-d H:i:s");
}

/**
 * Führt die $sql-Anweisung. Dazu wird mysqli verwendet. Bei einem Insert-Statment wist return der Wert von mysqli_insert_id.
 * Ein Select bringt als return-Wert das Ergebnis von mysqli_fetch_all
 * @param string $sql
 * @param string[] $parameter
 * @return string[]
 */
function db_select($sql) {
	$link = db_connect() or exception_throw("DB-Connection Failed". mysqli_connect_error());
	$link->set_charset("utf8");
	//error_log(mysqli_get_charset($link)->charset);
	$stmt = mysqli_prepare($link,$sql) or exception_throw("DB-Prepare Failed". mysqli_error($link) ."<br>$sql");

	$param_type = "";

	$params = func_get_args();
	
	if(sizeof($params) > 1) {
		//Wenn 2. Parameter ein Array, dann diese direkt an SQL-Bind übergeben
		if(is_array($params[1])) $param_value_array = $params[1];
		//... ansonsten die anderen Parameter (ohne $sql) an SQL-Bind übergeben
		else $param_value_array = array_splice($params, 1);

		foreach($param_value_array AS $key => $akt_para) {
			if(is_float($akt_para)) { $param_type .= "d"; }
			elseif(is_numeric($akt_para)) $param_type .= "i";		
			else $param_type .= "s";
		}
		$arr = array_merge (array($stmt, $param_type), $param_value_array);
		if(strlen($param_type) > 0) {
			call_user_func_array('mysqli_stmt_bind_param', getReferences($arr)) or exception_throw("DB-Bind Failed:\r\n<br>Sql:$sql\r\n<br>Param_type:$param_type<br>\r\n". mysqli_error($link));
		}
			
	}
	mysqli_stmt_execute($stmt) or 
		exception_throw("DB-Execute Failed:\r\n<br>Sql:$sql\r\n<br>Param_type:$param_type<br>\r\n". mysqli_error($link));
	$ret = null;

	//Select-Statment
	if(strcasecmp(substr($sql,0,1),"S") == 0) {
		$result = mysqli_stmt_get_result($stmt);
		$ret= mysqli_fetch_all($result);
	}
	//Insert-Statment
	elseif(strcasecmp(substr($sql,0,1),"I") == 0) {
		$ret = mysqli_insert_id($link);
	}
	elseif(strcasecmp(substr($sql,0,1),"U") == 0) {
		$ret = mysqli_stmt_affected_rows($stmt);
	}
	mysqli_stmt_close($stmt);
	mysqli_close($link);

	return $ret;
}
