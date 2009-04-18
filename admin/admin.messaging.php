<?php
defined('_JEXEC') or die();

JToolBarHelper::title('Messaging Properties', 'generic.png');

$user =& JFactory::getUser();
if($user->guest){
	echo JText::_("LOGIN").".<br /><br /><br /><br /><br />";
}else{
	// Require the base controller
	require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php');

	// Require specific controller if requested
	if($controller = JRequest::getVar('controller')) {
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php');
	}

	// Create the controller
	$classname	= 'MessagesController'.$controller;
	$controller = new $classname( );

	// Perform the Request task
	$controller->execute( JRequest::getVar('task'));

	// Redirect if set by the controller
	$controller->redirect();
}
?>