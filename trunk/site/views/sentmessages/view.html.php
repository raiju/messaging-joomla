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

class MessagesViewSentMessages extends JView
{
	function display($tpl = null)
	{
		// Get data from the model
		$items			= & $this->get('Data');
		
		//Assign vars for the view
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
