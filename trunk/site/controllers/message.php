<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

/**
 * Messaging Controller for Messaging Component
 * This will be called if the controller is set to "message"
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Messaging Message Controller
 *
 */
class MessagesControllerMessage extends MessagesController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * display the add form
	 * @return void
	 */
	function add()
	{
		//Set the right view
		JRequest::setVar( 'view', 'message' );

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		// perform token check (prevent spoofing)
		$token	= JUtility::getToken();
		if(!JRequest::getInt($token, 0, 'post')) {
			JError::raiseError(403, JText::_('REQUESTFORBIDDEN'));
		}
		//Get the model to use the store function
		$model = $this->getModel('message');
		
		if ($model->store()) {
			$msg = JText::_( 'MESSAGESENT')."!";
		} else {
			$msg = JText::_("ERROR").": ".$model->getError();
			$data = JRequest::get( 'post' );
			//Build the URL if the message hasn't been sent
			$getSuffix = '&sendError=1';
			$getSuffix .= '&to='.$data["to"];
			$getSuffix .= '&subject='.$data["subject"];
			$getSuffix .= '&message='.$data["message"];
			$link = JRoute::_('index.php?option=com_messaging&view=message'.$getSuffix);
			$link = str_replace("&amp;", "&", $link);
			//Redirect
			$this->setRedirect($link, $msg);
			return;
		}
		
		//If it has been sent, set the message and redirect
		$link = JRoute::_('index.php?option=com_messaging');
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		// perform token check (prevent spoofing)
		$token	= JUtility::getToken();
		if(!JRequest::getInt($token, 0, 'post')) {
		JError::raiseError(403, 'REQUESTFORBIDDEN');
		}
		
		//Get the model to use the delete method
		$model = $this->getModel('message');
		if(!$model->delete()) {
			$msg = JText::_( "ERROR").": ".$model->getError();
		} else {
			$msg = JText::_( 'MESSAGEDELETED' );
		}

		$this->setRedirect( JRoute::_('index.php?option=com_messaging'), $msg );
	}
}
?>
