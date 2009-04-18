<?php
defined('_JEXEC') or die();

class MessagesControllerMessage extends MessagesController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function save()
	{
		$model = $this->getModel('message');
		
		if ($model->store()) {
			$msg = JText::_('Configuration Saved').'!';
		} else {
			$msg = JText::_("Error").": ".$model->getError() ;
		}
		
		$link = 'index.php?option=com_messaging';
		$this->setRedirect($link, $msg);
	}
}
?>
