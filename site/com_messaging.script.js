//el = Element
//ins = tag
function insert(el,ins) {
	if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"]"+ el.value.substring(el.selectionStart,el.selectionEnd) +"\[\/" + ins + "]"+ el.value.substring(el.selectionEnd,el.value.length); 
    }else if (document.selection){
		var range = document.selection.createRange(); 
		var stored_range = range.duplicate(); 
		stored_range.moveToElementText(el); 
		stored_range.setEndPoint( 'EndToEnd', range ); 
		el.selectionStart = stored_range.text.length - range.text.length; 
		el.selectionEnd = el.selectionStart + range.text.length;
		el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"]"+ el.value.substring(el.selectionStart,el.selectionEnd) + "\[\/" + ins + "]" + el.value.substring(el.selectionEnd,el.value.length) ; 
    }
}
//el = Element
//ins = tag
//op = option
function insertOp(el,ins,op) {
	if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ el.value.substring(el.selectionStart,el.selectionEnd) +"\[\/" + ins + "]"+ el.value.substring(el.selectionEnd,el.value.length); 
    }else if (document.selection){
		var range = document.selection.createRange(); 
		var stored_range = range.duplicate(); 
		stored_range.moveToElementText(el); 
		stored_range.setEndPoint( 'EndToEnd', range ); 
		el.selectionStart = stored_range.text.length - range.text.length; 
		el.selectionEnd = el.selectionStart + range.text.length;
		el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ el.value.substring(el.selectionStart,el.selectionEnd) + "\[\/" + ins + "]" + el.value.substring(el.selectionEnd,el.value.length) ; 
    }
}
//el = Element
//ins = tag
//op = option
//text = text
function insertOpText(el,ins,op,text) {
	if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ text +"\[\/" + ins + "]"+ el.value.substring(el.selectionStart,el.value.length); 
    }else if (document.selection){
		var range = document.selection.createRange(); 
		var stored_range = range.duplicate(); 
		stored_range.moveToElementText(el); 
		stored_range.setEndPoint( 'EndToEnd', range ); 
		el.selectionStart = stored_range.text.length - range.text.length; 
		el.selectionEnd = el.selectionStart + range.text.length;
		el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ text + "\[\/" + ins + "]" + el.value.substring(el.selectionStart,el.value.length) ; 
    }
}
//el = Element
//ins = tag
//text = text
function insertText(el,ins,text) {
	if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"]"+ text +"\[\/" + ins + "]"+ el.value.substring(el.selectionStart,el.value.length); 
    }else if (document.selection){
		var range = document.selection.createRange(); 
		var stored_range = range.duplicate(); 
		stored_range.moveToElementText(el); 
		stored_range.setEndPoint( 'EndToEnd', range ); 
		el.selectionStart = stored_range.text.length - range.text.length; 
		el.selectionEnd = el.selectionStart + range.text.length;
		el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"]"+ text + "\[\/" + ins + "]" + el.value.substring(el.selectionStart,el.value.length) ; 
    }
}
//el = Element
//ins = tag
//op = option
//the tag will be formated in the following way:
//\[ins=op]
function insertSingle(el, ins, op){
	if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ el.value.substring(el.selectionStart,el.value.length); 
    }else if (document.selection){
		var range = document.selection.createRange(); 
		var stored_range = range.duplicate(); 
		stored_range.moveToElementText(el); 
		stored_range.setEndPoint( 'EndToEnd', range ); 
		el.selectionStart = stored_range.text.length - range.text.length; 
		el.selectionEnd = el.selectionStart + range.text.length;
		el.value = el.value.substring(0,el.selectionStart) + "[" + ins +"=" + op + "]"+ el.value.substring(el.selectionStart,el.value.length) ; 
    }
}

var popup; //A global variable that will act as the Popup ID
function makeExternalPopup(page) {
	
	//Now create the HTML code that is required to make the popup
	var content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
	content += "<html><head><script type='text/javascript'>";
	content += "function setData(){";
	content += "window.opener.document.getElementById('temp').value = document.getElementById('name').value;";
	content += "window.opener.document.getElementById('temp2').value = document.getElementById('name2').value;";
	content += "window.opener.handleInput("+page+");";
	content += "window.close();";
	content += "}";
	content += "<\/script></head><body>";
	content += htmls[page];
	content += "</body></html>";

	//Create the popup and store the returning id in the variable 
	popup = window.open("", "popup", "scrollbars,resizable,width="+widths[page]+",height="+heights[page]+"");
	popup.document.write(content); //Write content into it.
	popup.document.close();
}
function createPreview(id){
	var str = document.getElementById(id).value;
	str = str.replace(/\[b\]/gi, "<b>");
	str = str.replace(/\[\/b\]/gi, "</b>");
	str = str.replace(/\[i\]/gi, "<i>");
	str = str.replace(/\[\/i\]/gi, "</i>");
	str = str.replace(/\[u\]/gi, "<u>");
	str = str.replace(/\[\/u\]/gi, "</u>");
	str = str.replace(/\[url=/gi, "<a target='_blank' href='");
	str = str.replace(/\[\/url\]/gi, "</a>");
	str = str.replace(/\[img\]/gi, "<img src='");
	str = str.replace(/\[\/img\]/gi, "' alt='' />");
	str = str.replace(/\[quote\]/gi, "<div style='margin-left: 10px;'><table style='border-collapse: collapse;'><tr><td style='border: 1px solid black; padding: 5px;'>Quote</td></tr><tr><td style='border: 1px solid black; padding: 5px;'>");
	str = str.replace(/\[\/quote\]/gi, "</td></tr></table></div>");
	str = str.replace(/\[code\]/gi, "<div style='font-family: monospace;'><table style='border-collapse: collapse;'><tr><td style='border: 1px solid black; padding: 5px;'>Code</td></tr><tr><td style='border: 1px solid black; padding: 5px;'>");
	str = str.replace(/\[\/code\]/gi, "</td></tr></table></div>");
	str = str.replace(/\[size=/gi, "<spanstyle='font-size:");
	str = str.replace(/\[\/size\]/gi, "</span>");
	str = str.replace(/\[color=/gi, "<span style='color:");
	str = str.replace(/\[\/color\]/gi, "</span>");
	str = str.replace(/\]/gi, "'>");
	str = str.replace(/\n/gi, "<br />");
	
	while(str.indexOf("<spanstyle='font-size:") >= 0){
		ind = str.indexOf("<spanstyle='font-size:");
		str = str.substring(0, ind+5) + " "+str.substring(ind+5, ind+24) + "px" + str.substring(ind+24, str.length);
	}
	
	//Now create the HTML code that is required to make the popup
	var content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
	content += "<html><body>";
	content += str;
	content += "</body></html>";

	//Create the popup and store the returning id in the variable 
	popup = window.open("", "popup", "scrollbars,resizable,width=600,height=400");
	popup.document.write(content); //Write content into it.
	popup.document.close();
}
function handleInput(page){
	var ins = "";
	var el = document.getElementById('message');
	var op = document.getElementById('temp').value;
	var text = document.getElementById('temp2').value;
	if(page == 0){		//URL
		ins = "url";
	}else if(page == 1){
		ins = "img";
	}else if(page == 2){
		ins = "size";
	}else if(page == 3){
		ins = "color";
	}
	if(op == ""){
		insertText(el, ins, text);
	}else if(text == ""){
		insertOp(el, ins, op);
	}else{
		insertOpText(el, ins, op, text);
	}
}
function getData() {
	//Access the popup elements using this ID and fetch data from it 
	var data = popup.document.getElementById('popup_data').value; 
	document.frm.txt.value = data;
}
