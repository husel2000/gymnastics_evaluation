<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../config.php");


//Werte für Kampfrichter auslesen
$kari_anz=3;
if(!empty($_GET['anz_kari'])) $kari_anz=$_GET['anz_kari'];
$kari_name = Array(); for($i=0; $i < $kari_anz; $i++) $kari_name[$i] = "Kari ". ($i+1);
if(isset($_GET['kari_name']) && is_array($_GET['kari_name'])) 
  foreach($_GET['kari_name'] As $key => $value) $kari_name[$key] = $value;

//Welche Geräte drucken
$geraete=Array("Boden");
if(isset($_GET['geraete']) && is_array($_GET['geraete'])) $geraete = $_GET['geraete'];

?><!DOCTYPE html>
<html>
	<head>
	  <title></title>
  <link href="../css/css-user.php"  rel="stylesheet"> <!-- JQuery UI -->
  <style>
    body { font-size:12px; }
    h1, h2 { padding:0; margin:0; }
    h1 { font-size:16px; }
    h2 { font-size:14px; }

    .table_wertung { table-layout: fixed; width: 19cm;  }
    .table_wertung, .table_wertung td, .table_wertung th { border-width:2px; border-style:solid; border-color:black; }
    .table_wertung th { background-color: #ccc; padding:0px; margin:0px;}
    .th_bewertung { width:5cm; }
    .th_kari { width:2cm; }
    
    .kari { height:2em; }
    
    /*page-break-after: always;*/
    
    .settings { 
      position:fixed;
      bottom:4em;
      right:4em;
      background-color:white;
      padding-right: 3em;
      border-radius: 10px;
      box-shadow: 0px 0px 25px 5px #999;
      background-color: #FFF;
      color: #000;
      padding:3em;
    }
    
    @media print {
      .table_wertung {page-break-after: always;}
    }
  </style>
  
  <script src="../lib-js/jquery-1.12.0.min.js" type="text/javascript"></script> <!-- JQuery -->
  <script type="text/javascript">
    function geraet_show_change(obj) {
      geraet_refresh();
    }
    
    function geraet_refresh() {
      $('.geraet_tmp').remove();
      
      $(".input_geraet").each(function( index ) {
        if($(this).is(':checked')) {
          var myNew = $("#template").clone().show();
          myNew.addClass("geraet_tmp")
          myNew.attr('id',"id_geraet_" + $(this).val())
          myNew.find(".geraet_name").html($(this).val());
          myNew.find(".tr_kari").addClass("tr_kari_page");
          $('#template').before(myNew);
          //$("body").append(myNew);
        }else {
          $("#id_geraet_" + $(this).val()).remove();
        }
      });
      
      pagebreak_refresh();
    }
    
    
    function kari_remove(obj) {
      $(obj).closest("div").remove();
      kari_refresh();
    }
    function kari_add(obj) {
      var add = $("<div />");
      add.append($("<input />", { onchange: "kari_refresh(this)", type: "text", name: "input_kari", value: "Kari " + ($("[name='input_kari']").length+1) }));
      add.append($("<button />", { onclick: "kari_remove(this)"}).html("-"));
      $(add).insertBefore($(obj).parent());
      kari_refresh();
    }
    
    function kari_refresh() {
      $('.kari').remove();
      $('.tr_kari_temp').remove();
      //Hinzufügen
      $("[name='input_kari']").each(function( index ) {
        var row;
        if(index == 0) row = $(".tr_kari"); else row = $('<tr />', { class: "tr_kari_temp"});
        
        row.append($('<td />', {class: "kari"}).html($( this ).val()));
        row.append($('<td />', {class: "kari"}).html("&nbsp;")); //D-Note
        row.append($('<td />', {class: "kari"}).html("&nbsp;")); //E-Note
        row.append($('<td />', {class: "kari"}).html("&nbsp;")); //Ergebnis
        
        if(row.hasClass("tr_kari_temp")) {
          $(".tr_kari").each(function( index ) {
            var insertAfter = $(".tr_kari").get(index);
            while($(insertAfter).next().hasClass("tr_kari_temp")) insertAfter = $(insertAfter).next();
            $(insertAfter).after(row.clone()[0]);
          });
        }
      });
      $('.td_kari_rowspan').attr('rowspan', $("[name='input_kari']").length);
      
      pagebreak_refresh();
    }
    
    function pagebreak_refresh() {
      $(".pagebreak").css("page-break-after","auto");
      
      var anz = Math.round(26 / $("[name='input_kari']").length)
      console.log(anz);
      
      $(".table_wertung").each(function() {
        var ele = $(this).find(".tr_kari_page").each(function(index_page) {
          if((index_page+1) % anz != 0) return
          
          var ele = $(this);
          for(var i=1; i < $("[name='input_kari']").length; i++)
            ele = ele.next();
          ele.css("page-break-after","always");
          ele.addClass("pagebreak");
          
        });
        
      });
    }
    
    $(function() {
      kari_refresh();
      geraet_refresh();
    })
  </script>
	</head>
	
	<body class="print">
	  <div class="noprint settings">
	    <div>
	      <strong>Geräte</strong>
	      <?php
	      //Alle Geräte der Riegenliste laden
	      $id_riegenliste = $_GET['id_riegenliste'];
	      
	      $sql = "Select distinct Bezeichnung from wettkampf_geraet where id_wettkampf In (Select id_wettkampf From riegenliste_wettkampf where id_riegenliste = ?)";
	      $res = db_select($sql,$id_riegenliste);
	      for($i = 0; $i < sizeof($res); $i++) {
          echo '<div><input type="checkbox" class="input_geraet" value="' . $res[$i][0] . '" onchange="geraet_show_change(this)">' . $res[$i][0] . '</div>';
	      } ?>
      </div>
      <div>
        <strong>Kampfrichter</strong>
        <?php
          foreach($kari_name As $akt_kari) { ?>
            <div><input type="text" name="input_kari" onchange="kari_refresh(this)" value="<?php echo $akt_kari; ?>"><button onclick="kari_remove(this)">-</button></div>
<?php     } ?>
        
        <div><button onclick="kari_add(this)">+</button></div>
      </div>
    </div>
	  
	  <!-- Template für Riege und Gerät anlegen -->
	  <span id="template" style="display:none">
    <?php
      $id_riegenliste = $_GET['id_riegenliste'];
      $res = db_select("Select riegentext from riegenliste where id_riegenliste = ?",$id_riegenliste);
      //Jede Riege suchen
      $riegen_no = db_select("Select distinct riege_no from riegenliste_liste where id_riegenliste = ? ORDER BY riege_no",$id_riegenliste);
      for($i = 0; $i < sizeof($riegen_no); $i++) {
        $riege_no = $riegen_no[$i][0];
        ?>
        <table class="table_wertung">
          <thead><tr><th colspan="5"><?php echo "Kampfrichter - " . $res[0][0] .' - Riege ' . $riege_no ?> <span class="geraet_name"></span></td></tr></thead>
          <tbody>
          <tr>
            <th class="">Name (Verein)</th><th class="th_kari">Kari</th><th class="">D-Note</th><th class="">E-Note</th><th class="">Ergebnis</th>
          </tr>
<?php   //Jede Person in der Riege
        $turner = db_select("Select t.id_turner, w.id_wettkampf, t.name, t.vorname, t.verein, w.bezeichnung, w.system, r.reihenfolge from riegenliste_liste r, turner t, wettkampf w where r.id_riegenliste = ? and r.riege_no = ? " .
          "and r.id_turner = t.id_turner ".
          "and r.id_wettkampf = w.id_wettkampf ORDER BY reihenfolge",$id_riegenliste,$riege_no);
        foreach($turner As $key => $akt_turner) {
          
          if(empty($last_key) || $turner[$last_key][5] != $akt_turner[5] ||  $turner[$last_key][6] != $akt_turner[6]) {
            echo '<tr><th colspan=5>' . $akt_turner[5] . " - " . $akt_turner[6] ."</th></tr>";
          } else {
            echo '<tr><th colspan=5></th></tr>';
          }

          $last_key = $key;
          ?>
            <tr class="tr_kari">
              <td class="td_kari_rowspan"><?php echo $akt_turner[7] .' '. $akt_turner[2] .', '. $akt_turner[3]; ?> (<?php echo $akt_turner[4]; ?>)</td>
              <!--
              <td>Kari 1</td>
              <td></td> D-Note
              <td>&nbsp;</td> E-Note
              <td>&nbsp;</td> Ergebnis
              -->
            </tr>
<?php
        }
        unset($last_key);
        echo "</tbody></table>";
      }
    ?>
    </span>
	</body>
</html>