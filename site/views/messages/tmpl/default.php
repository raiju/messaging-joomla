<?php defined('_JEXEC') or die('Restricted access');
////////////////////////////////////
// PHP - Template creators, ignore this //
////////////////////////////////////

//Sets the percentage of the Inbox fill if there is a message limit
if($this->messageLimit > 0){
	$ratio = count($this->items)/$this->messageLimit;
	$percentage = ceil($ratio*100);
}
$messagePercentageTitle = "";
if($this->messageLimit > 0){
	$messagePercentageTitle = " - ".$percentage."% ".JText::_("FULL");
}

$k = 0;
$document =& JFactory::getDocument();

$script = "";

//The script contains all message information
$script .= "function setMessage(n){"."\n";
$script .= "\t".'var text = "<table width=\'100%\'><tr><td class=\'key\' style=\'width:70px;\'>'.JText::_("FROM").':</td><td>"+fromText[n]+"</td></tr><tr><td class=\'key\'>'.JText::_("SUBJECT").':</td><td>"+subjectText[n]+"</td></tr><tr><td colspan=\'2\'><hr /></td></tr><tr><td class=\'key\'>'.JText::_("MESSAGE").':</td><td>"+messageText[n]+"</td></tr></table>";'."\n";
$script .= "\t"."document.getElementById('messaging_message').innerHTML = text;"."\n";
$script .= "}"."\n";
$script .= "fromText = new Array();"."\n";
$script .= "subjectText = new Array();"."\n";
$script .= "messageText = new Array();"."\n";

//////////////////////////////////////////////////////
// End PHP - If you want to edit the template, edit from here //
//////////////////////////////////////////////////////
?>
<!-- Title, also shows how full the inbox is -->
<h1><?php
echo JText::_("INBOX");
// Prints how full the inbox is
echo $messagePercentageTitle;
?></h1>

<!-- Begin of the menu -->
<div id='messaging_menu' style='height: 30px;'>
	<table style='float: left;'>
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
			<td>
				<a href='javascript:document.getElementById("form").submit();'><?php echo JText::_("DELETE"); ?></a>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
	
	<?php
	//If there is a message limit
	if($this->messageLimit > 0){
		//display the box showing how far the inbox is filled
		?>
			<div style='text-align:right; float: right;'> <!-- Container -->
				<div style='width: 100px; border: 1px solid black;'> <!-- Border -->
					<div style='width: <?php echo $percentage; ?>px; background-color: <?php
					//Colors
					//if <90% -> rgb(195, 210, 229)
					//if >90% -> red
					echo ($percentage<90?"rgb(195, 210, 229)":"red");
					?>; text-align: center;'> <!-- Div showing the bar -->
						<?php echo $percentage; ?>%
					</div>
				</div>
			</div>
		<?php
	}
	?>
	<div style='clear: both;'></div>
</div>
<!-- End of the menu -->

<!-- Begin of the form -->
<form action="index.php" method="post" name="adminForm" id="form">
<div id="messaging_editcell">
	<!-- Begin of the message list box (where you can select the messages) -->
	<div style="border: 1px solid gray; height: 200px; overflow: auto;">
		<table class="adminlist" style='border-collapse: collapse; width: 100%;'>
		<thead>
			<tr style='background: #E0E0E0; width: 100%;'>
				<th>
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>	
				<th style='background: #E0E0E0;'>
					<?php echo JText::_( 'FROM' ); ?>
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
		
		if(count( $this->items ) == 0){		//Called if there are no messages -> Shows a text that spreads over the whole table
			?>
			<tr><td colspan='4'><?php echo JText::_("INBOXEMPTY"); ?></td></tr>
			<?php
		}
		//Loop that loops through all messages
		for ($i=0, $n=count( $this->items ); $i < $n; $i++)
		{
			//Build Vars - No editing!!
			$row = &$this->items[$i];
			$bold = false;
			if($row->seen == 0){		//If the message hasn't been seen
				$bold = true;
			}
			
			$tempuser =& JUser::getInstance($row->idFrom);
			
			//Build the "Reply" link + code
			$getSuffix = '&sendError=1';
			$getSuffix .= '&to='.$tempuser->name;
			$getSuffix .= '&subject=RE: '.htmlspecialchars($row->subject, ENT_QUOTES);
			$getSuffix .= '&message=';
			$link = JRoute::_('index.php?option=com_messaging&view=message'.$getSuffix);
			$replyCode = " <div style=\\'float:right;\\'><a href=\\'".$link."\\'>".JText::_("REPLY")."</a></div>";
			
			$checked 	= JHTML::_('grid.id',   $i, $row->n );
			$link 		= "javascript:setMessage(".$i.");";
			$script 	.= "fromText[".$i."] = '<div style=\\'float:left\\'>".htmlspecialchars($tempuser->name, ENT_QUOTES)."</div>".$replyCode."<div style=\\'clear:both\\'></div>';"."\n";
			$script 	.= "subjectText[".$i."] = '".htmlspecialchars($row->subject, ENT_QUOTES)."';"."\n";
			$script 	.= "messageText[".$i."] = '".str_replace(array("\r", "\n", "<br /><br />","'"), array("<br />","<br />","<br />","&#039;"), $row->message)."';"."\n";
			//End Build Vars - Edit from here
			/* For info:
			the "if($bold){ echo "<b>"; }" part, is to show the message in bold if it hasn't been read yet 
			the "if($i%2 == 1){ echo "style='background: #F0F0F0;'"; }" code colors one on the two colums to #F0F0F0
			*/
			?>
			<tr class="<?php echo "row$k"; ?>" <?php if($i%2 == 1){ echo "style='background: #F0F0F0;'"; } ?>>
				<td>
					<?php echo $checked; ?>
				</td>
				<!-- From -->
				<td>
					<a href="<?php echo $link; ?>">
					<?php if($bold){ echo "<b>"; } ?>
					<?php echo $tempuser->name ?>
					<?php if($bold){ echo "</b>"; } ?>
					</a>
				</td>
				<!-- Subject -->
				<td style="padding-left: 15px;">
					<a href="<?php echo $link; ?>">
					<?php if($bold){ echo "<b>"; } ?>
					<?php echo $row->subject; ?>
					<?php if($bold){ echo "</b>"; } ?>
					</a>
				</td>
				<!-- Date -->
				<td style="padding-left: 15px;">
					<a href="<?php echo $link; ?>">
					<?php if($bold){ echo "<b>"; } ?>
					<?php echo $row->date; ?>
					<?php if($bold){ echo "</b>"; } ?>
					</a>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		$document->addScriptDeclaration($script);	//Add the javascript that shows the messages
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

<!-- Do not edit after this, these objects don't influence how the page looks -->
<input type="hidden" name="option" value="com_messaging" />
<input type="hidden" name="task" value="remove" />
<input type="hidden" name="view" value="message" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="message" />
<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>
<br />
<br />