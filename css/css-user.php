<?php
header("Content-type: text/css");
require_once("../config.php");
?>
.ui-button { font-size: 0.7em; }
.ui-drag { cursor: move }

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

.hide { display:none; }


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

.button {
	background-size: 100%;
	width:5em;
	height:5em;
	margin:0px;
	padding:0px;
	background-repeat: no-repeat;
	background-position: 50% center;
}

.button_edit { background-image:url(<?php echo URIIMAGES . "icon-edit.png"; ?>); }
.button_edit2 { background-image:url(<?php echo URIIMAGES . "icon-edit2.png"; ?>); }
.button_list { background-image:url(<?php echo URIIMAGES . "icon-list.png"; ?>); }
.button_delete { background-image:url(<?php echo URIIMAGES . "icon-delete.png"; ?>); }
.button_result { background-image:url(<?php echo URIIMAGES . "icon-podest.png"; ?>); }
.button_save { background-image:url(<?php echo URIIMAGES . "save.png"; ?>); }

.button_up {
	width:1.5em;
	height:1.5em;
	background-color: Transparent;
	background-image:url(<?php echo URIIMAGES . "arrow-up.png"; ?>);
	border: none;
}

.button_down {
	width:1.5em;
	height:1.5em;
	background-image:url(<?php echo URIIMAGES . "arrow-down.png"; ?>);
	background-color: Transparent;
	border: none;
}

.input_range {
	width:50%;
}

.row_odd {
	background-color:#ffcc99
}

/* DINA 4 Page */
body .print {
    margin: 0;
    padding: 0;
    background-color: #FAFAFA;
}

.print .page {
  width: 21cm;
  height: 28.5cm;
  padding: 0;
  margin: 0.5cm auto;
  border: 1px red solid;
  border-radius: 1px;
  background: white;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
  font-family: "Courier New", Courier, monospace;
  font-size: 14px;
  line-height: 1;
}
.print .page p {margin: 0; }

@page {
  size: A4;
  margin: 0;
}
@media print {
  .print .page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
  
  .noprint {
    display:none;
  }
}