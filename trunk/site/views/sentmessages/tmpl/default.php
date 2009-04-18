<?php defined('_JEXEC') or die('Restricted access');

$k = 0;
$document =& JFactory::getDocument();

//The script contains all message information
$script .= "function setMessage(n){"."\n";
$script .= "\t".'var text = "<table width=\'100%\'><tr><td class=\'key\' style=\'width:70px;\'>'.JText::_("TO").':</td><td>"+fromText[n]+"</td></tr><tr><td class=\'key\'>'.JText::_("SUBJECT").':</td><td>"+subjectText[n]+"</td></tr><tr><td colspan=\'2\'><hr /></td></tr><tr><td class=\'key\'>'.JText::_("MESSAGE").':</td><td>"+messageText[n]+"</td></tr></table>";'."\n";
$script .= "\t"."document.getElementById('messaging_message').innerHTML = text;"."\n";
$script .= "}"."\n";
$script .= "fromText = new Array();"."\n";
$script .= "subjectText = new Array();"."\n";
$script .= "messageText = new Array();"."\n";
?>
<!-- title -->
<h1><?php
echo JText::_("SENTMESSAGES");
?></h1>

<!-- Begin of the menu -->
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
<!-- end of the menu -->


<div id="messaging_editcell">
	<!-- Begin of the message list box (where you can select the messages) -->
	<div style="border: 1px solid gray; height: 200px; overflow: auto;">
		<table class="adminlist" style='border-collapse: collapse; width: 100%;'>
		<thead>
			<tr style='background: #E0E0E0; width: 100%;'>	
				<th>
					<?php echo JText::_( 'TO' ); ?>
				</th>	
				<th style="padding-left: 15px;">
					<?php echo JText::_( 'SUBJECT' ); ?>
				</th>
				<th style="padding-left: 15px;">
					<?php echo JText::_( 'DATE' ); ?>
				</th>
			</tr>			
		</thead>
		<?php
		
		if(count( $this->items ) == 0){		//Called if there are no messages
			?>
			<tr><td colspan='3'><?php echo JText::_("SENTEMPTY"); ?></td></tr>
			<?php
		}
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			//Build Vars - No editing!!
			$row = &$this->items[$i];
			
			$tempuser =& JFactory::getUser($row->idTo);
			
			$link 		= "javascript:setMessage(".$i.");";
			$script 	.= "fromText[".$i."] = '<div style=\\'float:left\\'>".htmlspecialchars($tempuser->name, ENT_QUOTES)."</div><div style=\\'clear:both\\'></div>';"."\n";
			$script 	.= "subjectText[".$i."] = '".htmlspecialchars($row->subject, ENT_QUOTES)."';"."\n";
			$script 	.= "messageText[".$i."] = '".str_replace(array("\r", "\n", "<br /><br />","'"), array("<br />","<br />","<br />","&#039;"), $row->message)."';"."\n";
			//End Build Vars - Edit from here
			/* For info:
			the "if($i%2 == 1){ echo "style='background: #F0F0F0;'"; }" code colors one on the two colums to #F0F0F0
			*/
			?>
			<tr class="<?php echo "row$k"; ?>" <?php if($i%2 == 1){ echo "style='background: #F0F0F0;'"; } ?>>
				<td>
					<!-- From -->
					<a href="<?php echo $link; ?>"><?php echo $tempuser->name ?></a>
				</td>
				<td style="padding-left: 15px;">
					<!-- Subject -->
					<a href="<?php echo $link; ?>"><?php echo $row->subject; ?></a>
				</td>
				<td style="padding-left: 15px;">
					<!-- Date -->
					<a href="<?php echo $link; ?>"><?php echo $row->date; ?></a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		//Add the javascript to the page
		$document->addScriptDeclaration($script);
		?>
		</table>
	</div>
	<!-- End of the message list box -->
	<br />
	<!-- Begin of the message box (where the messages are shown) -->
	<div style="border: 1px solid gray; padding: 5px; vertical-align: top; height: 200px; overflow: auto;" id="messaging_message">
		<?php echo JText::_("NOMESSAGESELECTED"); ?>
	</div>
	<!-- End of the message box -->
</div>
<br />
<br />