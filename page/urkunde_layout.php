<script>
  function urkunde_content_get() { return tinymce.activeEditor.getContent(); }
  function urkunde_content_is_saved() { return ($('.mce-i-save').closest('div[role="button"]').attr('aria-disabled') != 'false'); }

  
  function button_print_prepare() {
      if(!urkunde_content_is_saved()) {
        dialog_create("Sie müssen zuerst das angepasste Layout speichern");
      }else {
        var placeholder = urkunde_content_get().match(/%%.*?%%/g);
        for(var i = 0; i < placeholder.length; i++) placeholder[i] = placeholder[i].replace(/%%/g, '');
        user_import_xls_start(placeholder,print_start)
      }
  }
  
  function print_start(json_data) {
    form = $('<form>', { method:"POST", target:"_blank", action: "./page/print_a4_template.php" }).append(
		  $('<input />', {type: "hidden", name: "code", value: tinymce.activeEditor.getContent()})
	  ).append(
		  $('<input />', {type: "hidden", name: "data", value: JSON.stringify(json_data)})
	  );
	  form.hide().appendTo("body").submit();
  }

  $(function() {
    form = $('<form />', { method:"POST", action: "<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>", id: "form_urkunde_get"}).append(
		  $('<input />', {type: "hidden", name: "action", value: "search"})
	  )
	  create_ajax_form(form);
	  form.submit();
  });
  
  function form_urkunde_get_submit(data) {
    $('[name="id_urkunde"]').empty();
    $('[name="id_urkunde"]').append($('<option>', { value: "", text: ""}));
    for(var i = 0; i < data.length; i++) {
      $('[name="id_urkunde"]').append($('<option>', { value: data[i]['id_urkunde'], text: data[i]['titel']}));
    }
    dialog_create($('#div_urkunde_start')[0]);
  }
  
  function form_urkunde_load_submit(data) {
    $('.page.edit').html(data['code']);
    $('#form_urkunde_save > input[name="id_urkunde"]').val(data['id_urkunde']);
    dialog_close($('#div_urkunde_start')[0]);
  }
  
  function form_urkunde_create_submit(data) { form_urkunde_load_submit({ "code": "", "id_urkunde": data }); }

  function form_urkunde_save_submit(data) { }
  function button_prepare_save() {
    var code = tinymce.activeEditor.getContent();
    $('#form_urkunde_save > input[name="code"]').val(tinymce.activeEditor.getContent());
    $('#form_urkunde_save').submit();
  }
    
	//Eigener Menüunkt für Platzhalter
	tinymce.PluginManager.add('pluginUrkunde', function(editor) {
  	editor.addMenuItem('PlatzhalterName', {
    	text: 'Platzalter Name',onclick: function() {editor.insertContent("%%name%%");},context: 'insert'
  	});
  	editor.addMenuItem('PlatzhalterPlatzierung', {
    	text: 'Platzhalter Platzierung', onclick: function() {editor.insertContent("%%platz%%");},context: 'insert'
  	});
  	editor.addMenuItem('PlatzhalterWettkampf', {
    	text: 'Platzhalter Wettkampf', onclick: function() {editor.insertContent("%%wettkampf%%");},context: 'insert'
  	});
  	editor.addMenuItem('PlatzhalterVerein', {
    	text: 'Platzhalter Verein',onclick: function() {editor.insertContent("%%verein%%");},context: 'insert'
  	});
  	editor.addMenuItem('PlatzhalterPunkte', {
    	text: 'Platzhalter Punkte',onclick: function() {editor.insertContent("%%punkte%%");},context: 'insert'
  	});
    	editor.addMenuItem('background', {
      text: 'Hintergrund',
        onclick: function() {
          dialog_create($('[name=input_background]')[0],null,false);
          $("input[name='input_background']").on("change", function(){
            dialog_close($('[name=input_background]'))
            var files = !!this.files ? this.files : []; // Get a reference to the fileList
            if ( !files.length || !window.FileReader ) return; // If no files were selected, or no FileReader support, return
            if ( /^image/.test( files[0].type ) ) { // Only proceed if the selected file is an image
              var reader = new FileReader(); // Create a new instance of the FileReader
              reader.readAsDataURL(files[0]); // Read the local file as a DataURL
              // When loaded, set image data as background of page
              reader.onloadend = function(){
                $('div > .page').css("background-repeat","no-repeat");
                $('div > .page').css("background-size", "100% Auto");
                $('div > .page').css("background-image", "url(" + this.result + ")");
              }
            }
          });
        },
        context: 'insert'
    	});
    });
	tinymce.init({
		fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
		selector: 'div.edit',
		inline: true,
		forced_root_block : false,
		plugins: [ 'pluginUrkunde','paste','save' ],
		save_onsavecallback: button_prepare_save,
		toolbar: 'save | background | undo redo | fontsizeselect',
    });
</script>
<style type="text/css">
	.page {
    width: 21cm;
    height: 28.5cm;
    padding: 0cm;
    margin: 2em auto;
    border: 1px black solid;
    border-radius: 5px;
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
</style>
<h1><span class="label label-default">Urkunden Serienbrief - Entwurf</span></h1>
<!-- Load or Create new Certificate -->
<div style="display:none">
  <div id="div_urkunde_start">
    <form id="form_urkunde_load" role="form" class="form-horizontal form_ajax" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
      <div class="form-group">
    		<label class="control-label col-sm-2" for="id_urkunde">Urkunde</label>
    		<div class="col-sm-10">
    		  <select class="form-control" name="id_urkunde"></select>
    		</div>
    	</div>
      <input type="hidden" name="action" value="load">
      <div class="form-group">
      	<div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default" >Laden</button>
        </div>
    	</div>
    </form>
    
    <form id="form_urkunde_create" role="form" class="form-horizontal form_ajax" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
      <div class="form-group">
    		<label class="control-label col-sm-2" for="urkunde_titel">Anlegen:</label>
    		<div class="col-sm-10">
    		  <input type="text" class="form-control" id="urkunde_titel" name="urkunde_titel" placeholder="Titel">
    		</div>
    	</div>
      <input type="hidden" name="action" value="create">
      <div class="form-group">
      	<div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default" >Erstellen</button>
        </div>
    	</div>
    </form>
  </div>
</div>
<!-- Save Certificate -->
<form id="form_urkunde_save" class="form_ajax" action="<?php echo Nav::_link_create_ajax(Nav::_get_akt()) ?>" method="POST">
  <input type="hidden" name="id_urkunde">
  <input type="hidden" name="action" value="save">
  <input type="hidden" name="code">
</form>

<button onclick="button_print_prepare()">Seriendruck starten</button>

<div style="display:none"><input type="file" name="input_background"></div>
<div class="page edit"></div>