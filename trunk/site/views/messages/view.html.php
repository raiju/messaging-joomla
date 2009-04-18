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

class MessagesViewMessages extends JView
{
	function display($tpl = null)
	{
		//Menu items
		$menu = array(
			"INBOX" => JRoute::_("index.php?option=com_messaging&view=messages"),
			"SENTMESSAGES" => JRoute::_("index.php?option=com_messaging&view=sentmessages"),
			"NEWMESSAGE" => JRoute::_("index.php?option=com_messaging&controller=message&view=message"),
			"DELETE" => "javascript:document.getElementById(\"form\").submit();"
		);
		// Get data from the model
		$items			= & $this->get('Data');
		$messageLimit	= & $this->get('MessageLimit');
		
		//Assign vars for the view
		$this->assignRef('menu',		$menu);
		$this->assignRef('items',		$items);
		$this->assignRef('messageLimit',		$messageLimit);

		parent::display($tpl);
	}
}
class MessagesViewFrontpage extends JView
{
	function display($tpl = null)
	{
		// Get data from the model
		$items			= & $this->get('Data');
		$messageLimit	= & $this->get('MessageLimit');

		$this->assignRef('items',		$items);
		$this->assignRef('messageLimit',		$messageLimit);

		parent::display($tpl);
	}
}
