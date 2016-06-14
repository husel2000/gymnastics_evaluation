<?php
header("Content-type: text/css");
require_once("../config.php");
?>
.ui-button {
	font-size: 0.7em;
}
.ui-drag {
	cursor: move
}

.input_number_result {
	margin:0px;
	display:inline;
	width:6em;
}

.border {
	box-shadow: 0px 0px 25px 5px #999;
	border-radius: 7px;
	border-style:solid;
	border-color:white;
	border-width:4px;
}

.display_none {
	display:none;
}
.form_inline {
	display:inline;
	margin:0px;
	padding:0px;
}
.form_inline input{
	width: 100%;
}
.form_inline select{
	width: 100%;
	border-radius: 2px;
}

.button_edit {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "icon-edit.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
}

.button_edit2 {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "icon-edit2.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
}

.button_up {
	background-size: 100%;
	width:1.5em;
	height:1.5em;
	margin:0px;
	padding:0px;
	background-color: Transparent;
	background-image:url(<?php echo URIIMAGES . "arrow-up.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
	border: none;
}

.button_down {
	background-size: 100%;
	width:1.5em;
	height:1.5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "arrow-down.png"; ?>);
	background-color: Transparent;
	background-repeat: no-repeat;
	background-position: 50% center;
	border: none;
}

.button_delete {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "icon-delete.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
}
.button_result {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "icon-podest.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
}

.button_save {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-image:url(<?php echo URIIMAGES . "save.png"; ?>);
	background-repeat: no-repeat;
	background-position: 50% center;
}


.input_range {
	width:50%;
}

.row_odd {
	background-color:#ffcc99
}
