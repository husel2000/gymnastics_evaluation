<?php
/*
 Rules:
 - Functions only in Lower Case (like all php-Functions)
 - For better Readability use _ to split different words in Function-Names
 */

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

///////////////////////////////////////////////////
/////////////// Invisible Operations //////////////
///////////////////////////////////////////////////
session_start();

include("config.php");

//The pages, which get included, have to overwrite these Variables...
$error = True;
$error_text = "No Action done ...";
$data = ""; //Result - can be Object, Array or Variable. Send back as json

//Check what Site is requested and if its available
if(Nav::_page_exists(Nav::_get_akt(), Nav_PageType::AJAX))
			include(Nav::_page_fullname(Nav::_get_akt(),Nav_PageType::AJAX));

die(json_encode(Array("error" => $error, "error_text" => $error_text, "data" => $data)));
?>