<?php
defined('_JEXEC') or die('Restricted access');

$last = 0;
$allSame = true;
for($i = 0; $i <= 6; $i++){
	if($this->messageLimits[$i] == $last || $i == 0){
		$last = $this->messageLimits[$i];
	}else{
		$allSame = false;
		$last = 0;
		break;
	}
}
?>

<form action="index.php" method="get" name="adminForm" id="adminForm">
<div class="col100">
	<table class="admintable">
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;"><label for="title">
				<h3 style='margin: 0px;'><?php echo JText::_( 'GENERALOPTIONS' ); ?></h3>
			</label></td><td></td>
		</tr>
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;">
				<label for="nameSuggestion">
					<?php echo JText::_( 'SUGGESTEDNAME' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" name="nameSuggestion" value="0" <?php echo ($this->nameSuggestion==0?"checked":""); ?>/><?php echo JText::_( 'USERNAME' ); ?><br />
				<input type="radio" name="nameSuggestion" value="1" <?php echo ($this->nameSuggestion==1?"checked":""); ?>/><?php echo JText::_( 'NAME' ); ?><br />
				<input type="radio" name="nameSuggestion" value="2" <?php echo ($this->nameSuggestion==2?"checked":""); ?>/><?php echo JText::_( 'BOTH' ); ?><br />
				<input type="radio" name="nameSuggestion" value="3" <?php echo ($this->nameSuggestion==3?"checked":""); ?>/><?php echo JText::_( 'NOSUGGESTION' ); ?>
			</td>
		</tr>
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;">
				<label for="sendNotify">
					<?php echo JText::_( 'SENDNOTIFY' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" name="sendNotify" value="1" <?php echo ($this->sendNotify==1?"checked":""); ?>/><?php echo JText::_( 'YES' ); ?><br />
				<input type="radio" name="sendNotify" value="0" <?php echo ($this->sendNotify==0?"checked":""); ?>/><?php echo JText::_( 'NO' ); ?>
			</td>
		</tr>
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;">
				<label for="limitAddress">
					<?php echo JText::_( 'USERSSENDTOUSERS' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" name="limitAddress" value="1" <?php echo ($this->limitAddress==1?"checked":""); ?>/><?php echo JText::_( 'YES' ); ?><br />
				<input type="radio" name="limitAddress" value="0" <?php echo ($this->limitAddress==0?"checked":""); ?>/><?php echo JText::_( 'NO' ); ?>
			</td>
		</tr>
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;"><label for="title">
				<h3 style='margin: 0px;'><?php echo JText::_( 'GROUPLIMITS' ); ?></h3>
			</label></td><td><?php echo JText::_( 'LIMITINBOXSIZE' ); ?></td>
		</tr>
		<tr style="display: block;">
			<td width="100" align="right" style="width: 185px;" class="key">
				<label for="individualChange"><?php echo JText::_( 'CHANGESIZEINDIVIDUAL' ); ?></label>
			</td>
			<td>
				<input type="radio" name="individualChange" value="1"<?php echo ($allSame?"":" checked"); ?> onchange="document.getElementById('messageLimitDefaultBox').style.display='none';for(i=0;i<7;i++){document.getElementById('messageLimitDefaultBox'+i).style.display='block';}" /><?php echo JText::_( 'YES' ); ?><br />
				<input type="radio" name="individualChange" value="0"<?php echo ($allSame?" checked":""); ?> onchange="document.getElementById('messageLimitDefaultBox').style.display='block';for(i=0;i<7;i++){document.getElementById('messageLimitDefaultBox'+i).style.display='none';}" /><?php echo JText::_( 'NO' ); ?>
			</td>
		</tr>
		<tr style="display: block;" id="messageLimitDefaultBox">
			<td width="100" align="right" style="width: 185px;" class="key">
				<label for="messageLimitDefault"><?php echo JText::_( 'DEFAULTSIZE' ); ?></label>
			</td>
			<td>
				<input type="text" value="<?php echo $last; ?>" maxlength="4" size="4" id="messageLimitDefault" name="messageLimitDefault" class="text_area"/>
			</td>
		</tr>
		<?php
			$curIndex = 0;
			
			//Loop through all types
			while(sizeof($this->types) > $curIndex){
				echo '<tr style="display: block;" id="messageLimitDefaultBox'.$curIndex.'"><td width="100" align="right" class="key" style="width: 185px;"><label for="messageLimit">';
				echo $this->types[$curIndex];
				echo '</label></td>';
				echo '<td>';
				echo '<input class="text_area" type="text" name="messageLimit[]" id="messageLimit'.$curIndex.'"';
				echo 'size="4" maxlength="4" value="';
				if(isset($this->messageLimits[$curIndex])){
					echo $this->messageLimits[$curIndex];
				}else{
					echo 0;
				}
				echo '" /></td></tr>';
				
				
				$curIndex++;
			}
		?>
		<tr style="display: block;">
			<td width="100" align="right" class="key" style="width: 185px;">
				<label for="submit">
					
				</label>
			</td>
			<td>
				<input class="submit" type="submit" name="submit" id="submit" size="32" maxlength="100" value="<?php echo JText::_( 'SUBMIT' ); ?>" />
			</td>
		</tr>
	</table>
	<?php if($allSame){ ?>
	<script type='text/javascript'>document.getElementById('messageLimitDefaultBox').style.display='block';for(i=0;i<7;i++){document.getElementById('messageLimitDefaultBox'+i).style.display='none';}</script>
	<?php }else{ ?>
	<script type='text/javascript'>document.getElementById('messageLimitDefaultBox').style.display='none';for(i=0;i<7;i++){document.getElementById('messageLimitDefaultBox'+i).style.display='block';}</script>
	<?php } ?>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_messaging" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="message" />
</form>
