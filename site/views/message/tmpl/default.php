<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
////////////////////////////////////
// PHP - Template creators, ignore this //
////////////////////////////////////
$to = "";
$subject = "";
$message = "";
if(JRequest::getInt("sendError", 0, "GET") == 1){
	$to = JRequest::getString("to", "", "GET");
	$subject = JRequest::getString("subject", "", "GET");
	$message = JRequest::getString("message", "", "GET");
}

$document =& JFactory::getDocument();

$document->addScriptDeclaration($this->script);
$document->addScriptDeclaration($this->users);
$dir = JURI::base()."components/com_messaging/";
$document->addScript($dir."com_messaging.script.js");

//////////////////////////////////////////////////////
// End PHP - If you want to edit the template, edit from here //
//////////////////////////////////////////////////////
?>
<!-- Title -->
<h1><?php echo JText::_("NEWMESSAGE"); ?></h1>

<!-- Begin Menu -->
<div id='messaging_menu' style='height: 30px;'>
	<table>
		<tr>
			<td>
				<a href='<?php echo JRoute::_("index.php?option=com_messaging&view=messages"); ?>'><?php echo JText::_("INBOX"); ?></a>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a href='<?php echo JRoute::_("index.php?option=com_messaging&view=sentmessages"); ?>'><?php echo JText::_("SENTMESSAGES"); ?></a>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<a href='<?php echo JRoute::_("index.php?option=com_messaging&controller=message&view=message"); ?>'><?php echo JText::_("NEWMESSAGE"); ?></a>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
</div>
<!-- End Menu -->

<!-- Begin Form for message -->
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="to">
					<?php echo JText::_( 'TO' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="to" id="to" size="32" maxlength="100" value="<?php echo $to; ?>" onkeyup="return getUser()" style="height: 32px; float: left;" />
				<select id="toList" style="width: 200px; float: left; margin-left: 8px; vertical-align: middle;" onchange="return setUser()" size="2">
					<option><?php echo JText::_( 'NOSUGGESTIONS' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="subject">
					<?php echo JText::_( 'SUBJECT' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="subject" id="subject" size="32" maxlength="100" value="<?php echo $subject; ?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="message">
					<?php echo JText::_( 'MESSAGE' ); ?>:
				</label>
			</td>
			<td>
			<!--  Link to enable extended formatting -->
			<div class='extendedLink'>
				<a href='#' onclick='showHide("extended"); return false;' id='extendedLink'><?php echo JText::_( 'EXTENDEDFORMATTING' ); ?></a>
			</div>
			
			<!-- Begin of the extended formatting box (this is hidden until the user activates it by clicking on the button above -->
			<div id='extended'style='display:none;'>
				<table>
					<tr>
						<td>
							<a href='#' onclick="insert(document.getElementById('message'),'b'); return false;" onmouseover='setStatus("<?php echo JText::_("BOLD") ?>");'><img src='<?php echo $dir; ?>images/bold.gif' alt='<?php echo JText::_("BOLD") ?>' title='<?php echo JText::_("BOLD") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="insert(document.getElementById('message'),'i'); return false;" onmouseover='setStatus("<?php echo JText::_("ITALIC") ?>");'><img src='<?php echo $dir; ?>images/italic.gif' alt='<?php echo JText::_("ITALIC") ?>' title='<?php echo JText::_("ITALIC") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="insert(document.getElementById('message'),'u'); return false;" onmouseover='setStatus("<?php echo JText::_("UNDERLINE") ?>");'><img src='<?php echo $dir; ?>images/underline.gif' alt='<?php echo JText::_("UNDERLINE") ?>' title='<?php echo JText::_("UNDERLINE") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="makeExternalPopup(0); return false;" onmouseover='setStatus("<?php echo JText::_("LINK") ?>");'><img src='<?php echo $dir; ?>images/url.gif' alt='<?php echo JText::_("LINK") ?>' title='<?php echo JText::_("LINK") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="makeExternalPopup(1); return false;" onmouseover='setStatus("<?php echo JText::_("PICTURE") ?>");'><img src='<?php echo $dir; ?>images/image.gif' alt='<?php echo JText::_("PICTURE") ?>' title='<?php echo JText::_("PICTURE") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="insert(document.getElementById('message'),'quote'); return false;" onmouseover='setStatus("<?php echo JText::_("QUOTE") ?>");'><img src='<?php echo $dir; ?>images/quote.gif' alt='<?php echo JText::_("QUOTE") ?>' title='<?php echo JText::_("QUOTE") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="insert(document.getElementById('message'),'code'); return false;" onmouseover='setStatus("<?php echo JText::_("CODE") ?>");'><img src='<?php echo $dir; ?>images/code.gif' alt='<?php echo JText::_("CODE") ?>' title='<?php echo JText::_("CODE") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="makeExternalPopup(2); return false;" onmouseover='setStatus("<?php echo JText::_("SIZE") ?>");'><img src='<?php echo $dir; ?>images/size.gif' alt='<?php echo JText::_("SIZE") ?>' title='<?php echo JText::_("SIZE") ?>' class='button' /></a>
						</td>
						<td>
							<a href='#' onclick="makeExternalPopup(3); return false;" onmouseover='setStatus("<?php echo JText::_("COLOR") ?>");'><img src='<?php echo $dir; ?>images/color.gif' alt='<?php echo JText::_("COLOR") ?>' title='<?php echo JText::_("COLOR") ?>' class='button' /></a>
						</td>
						<td>
							<input type='button' onclick='createPreview("message")' value='<?php echo JText::_("CREATEPREVIEW") ?>' onmouseover='setStatus("<?php echo JText::_("CREATEPREVIEW") ?>");' />
						</td>
					</tr>
				</table>
			</div>
			<!-- End of the extended formatting box -->
			<div>
				<table>
					<tr>
						<td>
							<div style='width: 100%; border: solid 1px #A0A0A0; color: #A0A0A0; display: none;' id='status'><?php echo JText::_("BUTTON") ?>:&nbsp;</div>
						</td>
					</tr>
					<tr>
						<td>
							<textarea rows="8" cols="60" name="message" id="message"><?php echo $message; ?></textarea>
						</td>
					</tr>
				</table>
				<!-- Do NOT remove these two input boxes -->
				<input type='hidden' id='temp' />
				<input type='hidden' id='temp2' />
			</div>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="submit">
					
				</label>
			</td>
			<td>
				<input class="submit" type="submit" name="submit" id="submit" size="32" maxlength="100" value="<?php echo JText::_( 'SUBMIT' ); ?>" />
			</td>
		</tr>
	</table>
</div>
<div class="clr"></div> <!-- Clears the float -->


<!-- Do not edit after this point, it does not change the visual style -->
<input type="hidden" name="option" value="com_messaging" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="message" />
<?php
jimport('joomla.utilities.date');
$now = new JDate();
$date = $now->toMySQL();
$user =& JFactory::getUser();
?>
<input type="hidden" name="date" value="<?php echo $date; ?>" />
<input type="hidden" name="idFrom" value="<?php echo $user->id; ?>" />
<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>
<script type='text/javascript'>
	<!--
	getUser();
	-->
</script>
