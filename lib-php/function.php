<?php

function validateDate($date, $format = 'd.m.Y') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

//Benötigt, da Konstrukt: "do_function or throw new Excpetion" nicht erlaubt ist
function exception_throw($text) { throw new Exception($text); }

//Für Datenbank benötigt
function getReferences(array &$arr) {
	$refs = array();
	foreach($arr as $key => $value) $refs[$key] = &$arr[$key];
	return $refs;
}

?>