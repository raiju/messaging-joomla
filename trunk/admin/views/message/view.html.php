<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class MessagesViewMessage extends JView
{
	function display($tpl = null)
	{
		// Get data from the model
		$messageLimits	= & $this->get('MessageLimits');
		$nameSuggestion	= & $this->get('NameSuggestion');
		$sendNotify		= & $this->get('SendNotify');
		$limitAddress	= & $this->get('LimitAddress');
		$types = array( JText::_( "SUPERADMINISTRATOR" ), JText::_( "ADMINISTRATOR" ), JText::_( "MANAGER" ), JText::_( "PUBLISHER" ), JText::_( "EDITOR" ), JText::_( "AUTHOR" ), JText::_( "REGISTERED" ) );

		$this->assignRef('messageLimits', $messageLimits);
		$this->assignRef('nameSuggestion', $nameSuggestion);
		$this->assignRef('sendNotify', $sendNotify);
		$this->assignRef('limitAddress', $limitAddress);
		$this->assignRef('types', $types);

		parent::display($tpl);
	}
}
