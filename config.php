<?php
/*
Rules:
- Constant in Upper Case
- If Link defined, always add "/" if its a folder
*/

define("PATH_ROOT","/home/maweber/bin/php/wettkampf/");
define("PAGE_TITEL","Wettkampf-Verwaltung");

define("URI","/wettkampf/");
define("URIIMAGES", URI . "images/");

//Paths (Filesystem)
define("FOLDERPAGE",PATH_ROOT . "page/");
define("FOLDERLIBPHP",PATH_ROOT . "lib-php/");

//Layout
define("SITEHTMLPREFIX","");
define("SITEHTMLSUFFIX","_layout.php");

define("SITESCRIPTPREFIX","");
define("SITESCRIPTSUFFIX","_script.php");

define("SITEAJAXPREFIX","");
define("SITEAJAXSUFFIX","_ajax.php");

//Database
define("DBHOST","localhost");
define("DBUSER","wettkampfuser");
define("DBPASS","***REMOVED***");
define("DBNAME","wettkampf");

//Include all Classes from lib-php
foreach(scandir(FOLDERLIBPHP) AS $file) {
	if(pathinfo($file, PATHINFO_EXTENSION) == "php") {
		include(FOLDERLIBPHP . $file);
	}
}

?>
