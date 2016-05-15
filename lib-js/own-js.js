function user_import_xls_start(arr_columns,callback) {
	xls_callback = callback;
	user_import_xls_select_file();
	xls_arr_columns = arr_columns;
}

function user_import_xls_select_file() {
	var d = $("<input/>", { type:"file", class:"form-control xls_import", onchange:"user_import_xls_import_file(this)"});
	dialog_create(d[0]);
}

function user_import_xls_import_file(file) {
	var reader = new FileReader();
	var name = file.files[0].name;
	reader.onload = function(e) {
		var data = e.target.result;
		/* if binary string, read with type 'binary' */
		var workbook = XLSX.read(data, {type: 'binary'});
		/* DO SOMETHING WITH workbook HERE */
		xls_obj_json = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]]);
		user_import_xls_ask_column();
	};
	reader.readAsBinaryString(file.files[0]);
}

function user_import_xls_ask_column() {
	var d = $('<div/>', { name:"user_import_xls" ,class: "form-horizontal xls_import" });
	xls_arr_columns.forEach(function(entry) {
		group = $('<div/>', { class: "form-group" });
		group.append($('<label/>', { class:"control-label col-sm-2", html: entry}));
		
		select = $('<select/>',{ name: entry, size:1 });
		select.append($('<option>', {value: "", text: "-" }))
		for(var k in xls_obj_json[0]) {
			select.append($('<option>', {value: k, text: k }))
		}
		col = $('<div/>',{ class: "col-sm-10"});
		col.append(select);
		group.append(col);
		d.append(group);
	});
	
	d.append($('<button/>', { text:"Import", class:"btn btn-default", onclick:"user_import_xls_return_json(document.getElementsByName('user_import_xls')[0])"}));
	dialog_create(d[0]);
}

function user_import_xls_return_json(div) {
	var xls_json_translate = {}; //XLS-Column --> Target-Column
	$(div).find("select").each(function() {
		if(this.value != "" && this.value != "-") {
			xls_json_translate[this.value] = this.name;
		}
	});
	
	var xls_json_result = new Array;
	
	xls_obj_json.forEach(function(entry) {
		tmp = {};
		for(var key in xls_json_translate) {
			tmp[xls_json_translate[key]] = entry[key];
		}
		xls_json_result.push(tmp);
	});
	
	//Aufräumen...
	$('.xls_import').each(function() {
		$(this).parent().find(".popup_button_close").trigger("click"); //Popup-Fenster schließen
	});
	
	xls_callback(xls_json_result);
}

var soundex = function (s) {
	//https://gist.github.com/shawndumas/1262659
     var a = s.toLowerCase().split(''),
         f = a.shift(),
         r = '',
         codes = {
             a: '', e: '', i: '', o: '', u: '',
             b: 1, f: 1, p: 1, v: 1,
             c: 2, g: 2, j: 2, k: 2, q: 2, s: 2, x: 2, z: 2,
             d: 3, t: 3,
             l: 4,
             m: 5, n: 5,
             r: 6
         };
     r = f + a.map(function (v, i, a) { return codes[v] }).filter(function (v, i, a) {return ((i === 0) ? v !== codes[f] : v !== a[i - 1]);}).join(''); 
     return (r + '000').slice(0, 4).toUpperCase();
};

//CSS-Klasse f�r Buttons etc zuweisen
function css_design_button() {
	$(":button").addClass(const_css_button);
	$("input:submit").addClass(const_css_button);
}

function css_design() {
	//Grafische Aufbereitung - Einheitliches Aussehen "erzwingen" �ber Klassen und JavaScript-Konstanten
	const_css_button = "btn btn-default";
	css_design_button();
}

function loading_show(force) {
	var arr = document.getElementsByClassName("loading");
	if(arr.length > 0) return;
	var div = document.createElement('div');
	div.className = "loading";
	if(typeof force != 'undefined' && force) div.className = div.className + " loading-force loading-middle";
	else div.className = div.className + " loading-topcorner";
	document.body.appendChild(div);
}

function loading_hide() {
	var arr = document.getElementsByClassName("loading");

	for(var i = 0; i < arr.length; i++) {
		var element = arr[i];
		element.parentNode.removeChild(element);
	}
}

function sort_child_elements(id,child_objects) {
	var mylist = $('#' + id);
	var listitems = mylist.children(child_objects).get();
	listitems.sort(function(a, b) {
	   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
	})
	$.each(listitems, function(idx, itm) { mylist.append(itm); });
}

////////// Diverse Funktionen /////////
///////////////////////////////////////
function get_next_dom(element,dom_name) {
	var all = $("*"); // All DOM elements
	if(!is_null(element)) {
		var h1idx = all.index(element);
		all.splice(0, h1idx); // Get rid of everything before and until your `h1`
	}
	var img = all.filter(dom_name).first(); // Your first dom_name
	return img.get(0);
}

function trigger_callback(element,callback_name) {
	if ("createEvent" in document) { //Firefox
		var evt = document.createEvent("HTMLEvents");
		if(callback_name.toLowerCase() == "onchange".toLowerCase()) evt.initEvent("change", false, true);
		element.dispatchEvent(evt);
		return;
	}
	else { //Internet-Explorer
		element.fireEvent("onchange");
	}
}

function range_onchange(element) {
	if(element.getAttribute("type") == "range") element.nextSibling.value = element.value;
	else element.previousSibling.value = element.value;
}

function is_null(element) { return !(element != null && element !== undefined); }

//Blendet innerhalb des Elements element_id anhand des Werts Elemente ein/aus
function content_filter(element_id,wert) {
	childs = document.getElementById(element_id).childNodes;
	for(i=0;i<childs.length;i++) {
		if(typeof childs[i].innerHTML === "undefined") continue;

		if(childs[i].innerHTML.toUpperCase().indexOf(wert.toUpperCase()) < 0) childs[i].style.display="none";
		else childs[i].style.display = "block";
	}
}

//Prüfen ob der Browser das Input-Type date unterstützt
function checkDateInput() {
	return false; //20.10.2015 - Ma.Weber - Erstmal erzwingen, alle Browser kein Support
	/*var input = document.createElement('input');
	input.setAttribute('type','date');

	var notADateValue = 'not-a-date';
	input.setAttribute('value', notADateValue);

	return !(input.value === notADateValue); */
}

//Alle Inputs von der Klasse input_date mit Datumsauswahl ausstatten, falls es der Browser nicht kann
function create_date_picker() {
	//Browser Supports Input-Type "date"
	if(checkDateInput()) return;
	
	var allInputs = $(".input_date");
	for(i=0;i<allInputs.length;i++) {
		$(allInputs[i]).datepicker({
			dateFormat:'dd.mm.yy'
		});
	}
}

//Alle Tablellen von der Klasse table_data in eine Daten-Tabelle umwandeln
function create_data_table() {
	var allTable = $(".table_data");
	for(i=0;i<allTable.length;i++) {
		$(allTable[i]).DataTable( {
			paging: false,
			dom: 'Bfrtip',
			buttons: [ 'csv', 'excel' ],
            "aaSorting": []
		});
	}
}

//Alle Input von der Klasse input_time mit Uhrzeitauswahl ausstatten
function create_time_picker() {
	$('.input_time').datetimepicker({
		  datepicker:false,
		  format:'H:i'
		});
}

//Funktionen die ausgeführt werden sollen, wenn eine Seite vollständig geladen ist
$(document).ready( function () {
	create_data_table();
	create_date_picker();
	create_time_picker();
	
	css_design();
});

function dialog_create_yesno(html_text,text_yes,text_no,callback) {
	var divBackground = document.createElement('div');
	 divBackground.className = "popup";

	var buttonY = document.createElement('div');
	buttonY.innerHTML = text_yes;
	buttonY.className = "popup_button";
	buttonY.onclick = function() { callback(true); $(divBackground).bPopup().close(); }
	
	var buttonN = document.createElement('div');
	buttonN.innerHTML = text_no;
	buttonN.className = "popup_button";
	buttonN.onclick = function() { callback(false); $(divBackground).bPopup().close(); }

	var p = document.createElement('p');
	p.innerHTML = html_text;
	divBackground.appendChild(p);
	divBackground.appendChild(buttonN);
	divBackground.appendChild(buttonY);
	document.body.appendChild(divBackground);

	$(divBackground).bPopup( {zIndex:99999,positionStyle: 'fixed'});
	
}

function findHighestZIndex(elem) {
	var elems = document.getElementsByTagName(elem);
	var highest = 0;
	for (var i = 0; i < elems.length; i++) {
		var zindex=document.defaultView.getComputedStyle(elems[i],null).getPropertyValue("z-index");
		if ((zindex > highest) && (zindex != 'auto')){
			highest = zindex;
		}
	}
	return highest;
}

function dialog_create(obj) {
	var divBackground = document.createElement('div');
	divBackground.className = "popup";
	
	var buttonClose = document.createElement('div');
	buttonClose.innerHTML = "X";
	buttonClose.className = "popup_button_close";
	buttonClose.onclick = function() { $(divBackground).bPopup().close(); }

	var p;
	if(isDOMElement(obj)) { 
		p = obj
	} else {
		p = document.createElement('p');
		p.innerHTML = obj;
	}
	
	divBackground.appendChild(buttonClose);
	divBackground.appendChild(p);
	document.body.appendChild(divBackground);
	
	var zIndex = findHighestZIndex("popup");
	
	$(divBackground).bPopup( {
		zIndex:(zIndex+1),
		positionStyle: 'fixed'
	});
}

//Überprüft ob es sich bei dem übergebene Objekt um ein DOM-Element handelt... http://stackoverflow.com/questions/384286/javascript-isdom-how-do-you-check-if-a-javascript-object-is-a-dom-object
function isDOMElement(obj) {
  try {
    //Using W3 DOM2 (works for FF, Opera and Chrom)
    return obj instanceof HTMLElement;
  }
  catch(e){
    //Browsers not supporting W3 DOM2 don't have HTMLElement and
    //an exception is thrown and we end up here. Testing some
    //properties that all elements have. (works on IE7)
    return (typeof obj==="object") &&
      (obj.nodeType===1) && (typeof obj.style === "object") &&
      (typeof obj.ownerDocument ==="object");
  }
}

//Sucht innerhalb eines Elements mit gegebener ID nach DOM-Elementen vom Typ node_type.
//text_prefix wird dabei aus der ID der Child-Elemente entfernt
function get_child_ids_in_id(id,text_prefix,node_type) {
	if (typeof node_type === 'undefined') { node_type = 'DIV'; }

	var arr_child = Array();
	var td_yes_child = document.getElementById(id).childNodes;
	for(i=0; i < td_yes_child.length; i++) {
		if(td_yes_child[i].nodeName == node_type) {
			arr_child.push(td_yes_child[i].id.replace(text_prefix,""));
		}
	}
	return arr_child;
}

//löscht eine Tabellenzeile anhand eines Strings
function removeRowByCellValue(table,cellValue) {
    var cells = table.getElementsByTagName("TD");
    for(var x = 0; x < cells.length; x++) {

        // check if cell has a childNode, prevent errors
        if(!cells[x].firstChild) {
            continue;
        }
        if(cells[x].firstChild.nodeValue == cellValue) {
            var row = cells[x].parentNode;
            row.parentNode.removeChild(row);

            break;
        }
    }
}

//gibt das aktuelle Datum als SQL Datetime aus
function getSQLDateTime(){
	var formatedMysqlString = (new Date ((new Date((new Date(new Date())).toISOString() )).getTime() - ((new Date()).getTimezoneOffset()*60000))).toISOString().slice(0, 19).replace('T', ' ');
	return formatedMysqlString;
}
