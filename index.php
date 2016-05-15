<?php
/*
 Rules:
 - Functions only in Lower Case (like all php-Functions)
 - For better Readability use _ to split different words in Function-Names
 */

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

///////////////////////////////////////////////////
/////////////// Invisible Operations //////////////
///////////////////////////////////////////////////
session_start();

include("config.php");

//Check what Site is requested and if its available
if(Nav::_page_exists(Nav::_get_akt(), Nav_PageType::SCRIPT))
	include(static::_page_fullname(Nav::_get_akt(),Nav_PageType::SCRIPT));

///////////////////////////////////////////////////
/////////////// Normal Layout ////////////////////
///////////////////////////////////////////////////
//HTML-Header (complete Head-Section)
$title = PAGE_TITEL;
include(FOLDERPAGE . "html_head.php");

//HTML-Content-Body
if(Nav::_page_exists(Nav::_get_akt(), Nav_PageType::HTML))
	include(Nav::_page_fullname(Nav::_get_akt(),Nav_PageType::HTML));

//HTML-Footer (Close Html-Section)
include(FOLDERPAGE . "html_foot.php");
?>