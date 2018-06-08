<?php header("Content-Type: text/html; charset=UTF-8"); ?><!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<script src="./lib-js/jquery-1.12.0.min.js" type="text/javascript"></script> <!-- JQuery -->
		<script src="./lib-js/tinymce/tinymce.min.js" type="text/javascript"></script> <!-- TinyMCE -->
		
		<script src="./lib-js/jquery-ui.js" type="text/javascript"></script> <!-- JQuery UI -->
		<link href="./css/jquery.ui.css"  rel="stylesheet"> <!-- JQuery UI -->
		
		<script src="./lib-js/jquery.bpopup.min.js" type="text/javascript"></script> <!-- JQuery Popup -->
		
		<script src="./lib-js/jquery.dataTables.min.js" type="text/javascript"></script> <!-- JQuery DataTables -->
		<script src="./lib-js/dataTable-buttons/dataTables.buttons.min.js" type="text/javascript"></script> <!-- JQuery DataTables Buttons -->
		<script src="./lib-js/dataTable-buttons/buttons.html5.min.js" type="text/javascript"></script> <!-- JQuery DataTables Buttons -->
		<link href="./css/jquery.dataTables.css" rel="stylesheet"> <!-- JQuery DataTables -->
		<link href="./css/dataTable-Buttons/buttons.dataTables.css" rel="stylesheet"> <!-- JQuery DataTables Buttons -->
		
		<link href="./css/jquery.datetimepicker.css" rel="stylesheet"> <!-- JQuery DateTimePicker -->
		<script src="./lib-js/jquery.datetimepicker.js" type="text/javascript"></script> <!-- JQuery DateTimePicker -->
		
		<link href="./css/css-popup.php" rel="stylesheet"> <!-- Own: Popup -->
		<link href="./css/css-loading.css" rel="stylesheet"> <!-- Own: Loading -->
		
		<script src="./lib-js/bootstrap.min.js" type="text/javascript"></script> <!-- Bootstrap -->
		<link href="./css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap -->
		
		<script src="./lib-js/own-ajax.js" type="text/javascript"></script> <!-- Own: Ajax -->
		<script src="./lib-js/own-js.js" type="text/javascript"></script> <!-- Own: Javascript -->
		<script src="./lib-js/own-js-dataTable-edit.js" type="text/javascript"></script> <!-- Own: Javascript DataTable Edit-->
		
		<script src="./lib-js/js-xlsx-master/dist/xlsx.core.min.js" type="text/javascript"></script>
		
		<link href="./css/css-user.php" rel="stylesheet"> <!-- Own: CSS -->
		
	</head>
	<body>
	
	<!--  Navigation -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
					aria-expanded="false">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><?php echo $title ?></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="<?php if(Nav::_get_akt() == "wettkampf") echo "active";?>"><a href="<?php echo Nav::_link_create("wettkampf"); ?>">Wettkampf</a></li>
					<li class="<?php if(Nav::_get_akt() == "turner") echo "active";?>"><a href="<?php echo Nav::_link_create("turner"); ?>">Turner</a></li>
					<li class="<?php if(Nav::_get_akt() == "riegenliste") echo "active";?>"><a href="<?php echo Nav::_link_create("riegenliste"); ?>">Riegenliste</a></li>
          <li class="<?php if(Nav::_get_akt() == "urkunde") echo "active";?>"><a href="<?php echo Nav::_link_create("urkunde"); ?>">Urkunde</a></li>				
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container-fluid -->
	</nav>
	<div class="container" style="width:100%">
	