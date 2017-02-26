<?php
	//1. Template aus POST auslesen
	//2. Siegerliste aus POST auslesen
	//3. "Übersetuzung für Platzhalter"

?><!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
	<head>
		<style type="text/css">
	body {
    margin: 0;
    padding: 0;
    background-color: #FAFAFA;
}
  .page {
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
  .page p {margin: 0; }

@page {
    size: A4;
    margin: 0;
}
@media print {
    .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
}
		</style>
	</head>
	
	<body><div class="book">
    <?php
      $code_template = $_POST['code'];
      $results = json_decode($_POST['data']);
      
      foreach($results As $result) {
        ?>
         <!-- <page size="A4"> -->
         <div class="page">
        <?php
          $code = $code_template;
          foreach($result As $key => $value) {
            $code=str_replace("%%$key%%",$value,$code);
          }
          echo $code;
        ?>
        <!-- </page> -->
        </div>
    <?php
      }
    ?>
    </div>
	</body>
</html>