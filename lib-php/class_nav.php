<?php
/*
Rules:
- Functions only in Lower Case (like all php-Functions)
- For better Readability use _ to split different words in Function-Names
*/
abstract class Nav_PageType { const HTML = 1; const SCRIPT = 2; const AJAX = 4; }

class Nav {
	public static function _get_akt() {
		if(empty($_GET['page'])) return "wettkampf";
		return $_GET['page'];
	}
	
	public static function _link_create($nav_name) {
			return "?page=" . $nav_name;
	}
	
	public static function _link_create_ajax($nav_name) {
			return "index_ajax.php?page=" . $nav_name;
	}
	
	public static function _page_fullname($nav_name,$page_type) {
		if($page_type == Nav_PageType::HTML) $page_akt = FOLDERPAGE . SITEHTMLPREFIX . $nav_name . SITEHTMLSUFFIX;
		if($page_type == Nav_PageType::SCRIPT) $page_akt = FOLDERPAGE . SITESCRIPTPREFIX . $nav_name .SITESCRIPTSUFFIX;
		if($page_type == Nav_PageType::AJAX) $page_akt = FOLDERPAGE . SITEAJAXPREFIX . $nav_name .SITEAJAXSUFFIX;
		return $page_akt;
	}
	
	public static function _page_exists($nav_name,$page_type) {
		$nav_name_full = static::_page_fullname($nav_name, $page_type);
		$nav_name_full = pathinfo($nav_name_full, PATHINFO_FILENAME);
		foreach(scandir(FOLDERPAGE) AS $file)
			if(pathinfo($file, PATHINFO_FILENAME) == $nav_name_full) return true;
		return false;
	}
}
?>