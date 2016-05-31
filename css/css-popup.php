<?php
header("Content-type: text/css");
//04.09.2015 - Ma.Weber - in .popup width to min-width
?>
.popup {
	min-width: 500px; 
	padding:1em;
	padding-right:3em;
	border-radius: 10px;
	box-shadow: 0px 0px 25px 5px #999;
	background-color: #FFF;
	color:#000;
	margin: auto;
	display: inline-block;
}

.popup_button_close:hover {
	background-color:black;
}

.popup_button {
	border-radius: 7px;
        box-shadow: none;
        padding: 0px 6px 2px;
        background-color: #EBC37D;
        box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.3);
        color: #FFF;
        display: inline-block;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
}
.popup_button:hover {
        background-color:black;
}

.popup_button_close {
	border-radius: 7px;
	box-shadow: none;
	padding: 0px 6px 2px;
	position: absolute;
	right: -7px;
	top: -7px;
	background-color: #EBC37D;
	box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.3);
	color: #FFF;
	display: inline-block;
	padding: 5px 10px;
	text-align: center;
	text-decoration: none;
	cursor: pointer;
}
