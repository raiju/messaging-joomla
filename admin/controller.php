<?php
/**
 * Messaging default controller
 */

jimport('joomla.application.component.controller');

/**
 * Messaging Component Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MessagesController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		JRequest::setVar( 'view', 'message' );
		
		parent::display();
	}

}
?>
