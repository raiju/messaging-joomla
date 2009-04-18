<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class MessagesViewMessage extends JView
{
	function display($tpl = null)
	{
		$script = '
		var getUser = function(){
			var select = document.getElementById("toList");
			
			while(select.options.length > 0){
				select.remove(0);
			}
			var to = document.getElementById("to");
			var select = document.getElementById("toList");
			var text = to.value;
			if(text.lastIndexOf (",") >= 0){
				text = text.substring((text.lastIndexOf (",") + 2));
			}
			
			if(text == ""){
				for(i = 0; i < users.length; i++){
					appendOption(users[i]);
				}
				return true;
			}
			var length = text.length;
			for(i = 0; i < users.length; i++){
				var temp = users[i].substring(0, length);
				if(temp.toLowerCase() == text.toLowerCase()){
					appendOption(users[i]);
				}
			}
			if(select.options.length == 0){
				appendOption("'.JText::_('NOSUGGESTIONS').'");
				return true;
			}
			
			return true;
		}

		var setUser = function(){
			var select = document.getElementById("toList");
			var to = document.getElementById("to");
			var selected = select.selectedIndex;
			var text = select.options[selected].text
			
			if(text == "'.JText::_('NOSUGGESTIONS').'"){
				return true;
			}
			currentValue = to.value;
			currentValue = currentValue.substring(0, (currentValue.lastIndexOf (",")+1));
			if((currentValue.lastIndexOf (",") + 1)==0){
				to.value = text;
			}else{
				to.value = currentValue.substring(0, currentValue.length-1)+", "+text;
			}
			
			return true;
		}

		function appendOption(text)
		{
		  var elOptNew = document.createElement("option");
		  elOptNew.text = text;
		  elOptNew.value = text;
		  var elSel = document.getElementById("toList");

		  try {
		    elSel.add(elOptNew, null);
		  }
		  catch(ex) {
		    elSel.add(elOptNew);
		  }
		}
		
		function showHide(id){
			var x = document.getElementById(id);
			var x2 = document.getElementById("status");
			var y = document.getElementById(id+"Link");
			if(x.style.display == "none"){
				x.style.display = "block";
				x2.style.display = "block";
				y.innerHTML = "'.JText::_("SIMPLEFORMATTING").'";
			}else{
				x.style.display = "none";
				x2.style.display = "none";
				y.innerHTML = "'.JText::_("EXTENDEDFORMATTING").'";
			}
		}

		var htmls = new Array();
		htmls[0] = "<table><tr><td>'.JText::_("NAME").':</td><td><input type=\'text\' id=\'name2\' /></td></tr><tr><td>'.JText::_("LINK").':</td><td><input type=\'text\' id=\'name\' /></td></tr><tr><td></td><td><input type=\'button\' value=\''.JText::_("MAKELINK").'\' onclick=\'setData()\' /><input type=\'button\' value=\''.JText::_("CANCEL").'\' onclick=\'window.close();\' /></td></tr></table>";
		htmls[1] = "<table><tr><td>'.JText::_("LINK").':</td><td><input type=\'text\' id=\'name2\' /><input type=\'hidden\' id=\'name\' /></td></tr><tr><td></td><td><input type=\'button\' value=\''.JText::_("MAKEPICTURE").'\' onclick=\'setData()\' /><input type=\'button\' value=\''.JText::_("CANCEL").'\' onclick=\'window.close();\' /></td></tr></table>";
		htmls[2] = "<table><tr><td>'.JText::_("SIZE").' ('.JText::_("INPIXELS").'):</td><td><input type=\'text\' id=\'name\' maxlength=\'2\' style=\'width: 20px;\' /><input type=\'hidden\' id=\'name2\' /></td></tr><tr><td colspan=\'2\'><input type=\'button\' value=\''.JText::_("SETSIZE").'\' onclick=\'setData()\' /><input type=\'button\' value=\''.JText::_("CANCEL").'\' onclick=\'window.close();\' /></td></tr></table>";
		htmls[3] = "<table><tr><td>'.JText::_("COLORS").':</td><td><input type=\'hidden\' id=\'name\' /><input type=\'hidden\' id=\'name2\' />";

		var colors = "";
		hex = new Array();
		hex[0] = "0";
		hex[1] = "3";
		hex[2] = "6";
		hex[3] = "9";
		hex[4] = "C";
		hex[5] = "F";
		s = hex.length-1;

		colors += "<div style=\'width: 160px;float: left;\'>";
		for(var i = 0; i < s+1; i++){
			for(var j = 0; j < s+1; j++){
				for(var k = 0; k < s+1; k++){
					colors += "<a href=\'#\' onclick=\'document.getElementById(\\"name\\").value=\\"#"+hex[i]+hex[i]+hex[j]+hex[j]+hex[k]+hex[k]+"\\"; setData();\'>";
					colors += "<div style=\'float: left; width: 25px; height: 25px; background-color: #"+hex[i]+hex[i]+hex[j]+hex[j]+hex[k]+hex[k]+"\'>";
					colors += "</div>";
					colors += "</a>";
				}
			}
			
			if(i%3==2 && i > 0){
				colors += "</div>";
				colors += "<div style=\'width: 160px; float: left;\'>";
			}
}
			colors += "</div>";
			htmls[3] += colors;
			htmls[3] += "</td></tr><tr><td></td><td><input type=\'button\' value=\''.JText::_("CANCEL").'\' onclick=\'window.close();\' /></td></tr></table>";

			var widths = new Array();
			widths[0] = 350;
			widths[1] = 350;
			widths[2] = 250;
			widths[3] = 600;

			var heights = new Array();
			heights[0] = 120;
			heights[1] = 100;
			heights[2] =  80;
			heights[3] = 400;

			function setStatus(text){
						document.getElementById("status").innerHTML = "'.JText::_("BUTTON").': "+text;
			}
		';
		// Get data from the model
		$users			= & $this->get('Users');

		$this->assignRef('users',		$users);
		$this->assignRef('script',		$script);

		parent::display($tpl);
	}
}
