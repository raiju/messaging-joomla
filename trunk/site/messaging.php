<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

/**
 * Messaging entry point file for Messaging Component
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$user =& JFactory::getUser();
if($user->guest){	//Check if the user is logged in
	echo JText::_("LOGIN").".<br /><br /><br /><br /><br />";
}else if($user->getParam("messaging", 1) == 0){		//Also check if the user has activated the component for himself
	echo JText::_("ACTIVATE").".<br /><br /><br /><br /><br />";
}else{	//If everything is OK, continue
	// Require the base controller
	require_once (JPATH_COMPONENT.DS.'controller.php');

	// Require specific controller if requested
	$controller = JRequest::getVar('controller');
	$allowed = false;
	//Check if the controller is allowed
	switch($controller){
		case "":
		case "message":
			$allowed = true;
			break;
	}
	if($allowed) {
		if($controller)
			require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

		// Create the controller
		$classname	= 'MessagesController'.$controller;
		$controller = new $classname( );

		// Perform the Request task
		$controller->execute( JRequest::getVar('task'));

		// Redirect if set by the controller
		$controller->redirect();
	}else{
		JError::raiseError(403, JText::_('REQUESTFORBIDDEN'));
	}
}
?>
