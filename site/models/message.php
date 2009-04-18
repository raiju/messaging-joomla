<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class MessagesModelMessage extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
		$this->db =& JFactory::getDBO();
		$this->user =& JFactory::getUser();
		$this->post = JRequest::get('post');
	}

	/**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int identifier
	 * @return	void
	 */
	function setId($n)
	{
		// Set id and wipe data
		$this->_n		= $n;
		$this->_data	= null;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$data = JRequest::get( 'post' );
		
		if($data['subject'] == ""){
			$data["subject"] = JText::_("NOSUBJECT");
		}
		
		$num2Array = $this->getMessageLimits();
		$reversedTypes = array( "Super Administrator"=>0, "Administrator"=>1, "Manager"=>2, "Publisher"=>3, "Editor"=>4, "Author"=>5, "Registered"=>6 );
		//$to = array($data["to"]);
		$to = explode(", ", $data["to"]);
		$toType = array();
		$gla = $this->getLimitAddress();
		
		//Loop through all addressees
		for($i = 0; $i < sizeof($to); $i++){
			$query = "SELECT id, usertype FROM #__users WHERE (name='".$to[$i]."' OR username='".$to[$i]."') AND params NOT LIKE '%messaging=0%'";
			$curUser =& JFactory::getUser();
			
			$isAdmin = false;
			if($curUser->usertype=="Super Administrator"||$curUser->usertype=="Administrator"||$curUser->usertype=="Manager")
				$isAdmin = true;
			
			if($gla == 0 && !$isAdmin){
				$query = $query." AND (usertype LIKE '%Administrator%' OR usertype LIKE '%Manager%')";
			}
			$this->db->setQuery($query);
			$rows = $this->db->loadObjectList();
			
			if(sizeof($rows) >= 1){		//Get the id of the addressee
				$to[$i] = $rows[0]->id;
				$toType[$i] = $rows[0]->usertype;
				if(!isset($reversedTypes[$toType[$i]])){
					$toType[$i] = "Registered";
				}
			}else{
				$continue = false;
				if(sizeof($to) > 1){
					$this->setError(JText::_("ONEUSERNOTFOUND"));
				}else{
					$this->setError(JText::_("USERNOTFOUND"));
				}
				return false;
			}
			
			//Get the user info
			$user =& JFactory::getUser($to[$i]);
			$query = "SELECT n FROM #__messaging WHERE idTo=".$user->id;
			$this->db->setQuery($query);
			$rows = $this->db->loadObjectList();
			$num1 = sizeof($rows);
			$num2 = $num2Array[$reversedTypes[$toType[$i]]];
			
			if($num1 < $num2 || $num2 == 0){
				//The Inbox of the other person is not full
			}else{
				if(sizeof($to) > 1){
					$this->setError(JText::_("INBOXRECIPIENTFULL"));
				}else{
					$this->setError(JText::_("INBOXONERECIPIENTFULL"));
				}
				return false;
			}
		}
		
		$sendNotify = $this->getSendNotify();
		
		//Send multiple messages if there are more senders
		for($i = 0; $i < sizeof($to); $i++){
			$data["idTo"] = $to[$i];
			
			$row =& $this->getTable();
			
			// Bind the form fields to the hello table
			if (!$row->bind($data)) {
				$this->setError($this->db->getErrorMsg());
				return false;
			}

			// Make sure the hello record is valid
			if (!$row->check()) {
				$this->setError($this->db->getErrorMsg());
				return false;
			}
			
			// Store the web link table to the database
			if (!$row->store()) {
				$this->setError($this->db->getErrorMsg() );
				return false;
			}
			
			if($sendNotify == 1){
				$this->sendMail($to[$i]);
			}
		}

		return true;
	}
	
	/**
             * Method to send mail(s)
             *
             * @access       public
             * @return         void
             */

	function sendMail($id)
	{
		global $mainframe;
		$user =& JFactory::getUser($id);
		if (($user->getParam("messaging", 1) == 1) && ($user->getParam("messaging_mail", 1) == 1))
		{
		   $mailer =& JFactory::getMailer();
		   $me   =& JFactory::getUser();
		   $mailer->setSender(array($mainframe->getCfg('mailfrom'), 'Messaging - '.$mainframe->getCfg('fromname')));
		   // Build e-mail message format
		   $mailer->setSubject(JText::_('YOURECEIVEDMESSAGE'));
		   
		   $body = JText::_('HELLO').' '.$user->name.','."\n"."\n";
		   $body .= JText::_('YOURECEIVEDMESSAGEFROM').' '.$me->name."\n"."\n";
		   $body .= $mainframe->getCfg('sitename').' ('.JURI::root().')';
		   
		   $mailer->setBody($body);
		   $mailer->addRecipient($user->email);
		   $mailer->IsHTML(0);
		   // Send the Mail
		   $mailer->Send();
		}
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		//Check if the messages are for the right person -> if the user has the right to delete
		$db =& JFactory::getDBO();
		$thisuser =& JFactory::getUser();
		$db->setQuery("SELECT n FROM #__messaging WHERE idTo=".$thisuser->id." ORDER BY date DESC");
		
		$rows = $db->loadObjectList();

		$ids = array();
		foreach($rows as $row){
			$ids[$row->n] = 1;
		}

		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if(!isset($ids[$cid])){		//Do not delete messages that are not for that person
					continue;
				}
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		return true;
	}
	
	/**
	* Method to get all users that have the component activated
	* 
	* @access public
	* @return string	Javascript array with all users
	*/
	function getUsers(){
		$result = "var users = Array();";
		$ns = $this->getNameSuggestion();
		if($ns == 3)
			return $result;
		
		$db =& JFactory::getDBO();
		$gla = $this->getLimitAddress();
		$query = "SELECT * FROM #__users WHERE params NOT LIKE '%messaging=0%'";
		$curUser =& JFactory::getUser();
		
		$isAdmin = false;
		if($curUser->usertype=="Super Administrator"||$curUser->usertype=="Administrator"||$curUser->usertype=="Manager")
			$isAdmin = true;
		
		//If is admin, send to anyone
		if($gla == 0 && !$isAdmin){
			$query = $query." AND (usertype LIKE '%Administrator%' OR usertype LIKE '%Manager%')";
		}
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();

		$users = array();
		foreach($rows as $row){
			if($ns == 0 || $ns == 2){
				$users[] = str_replace("'", "\'", $row->username);
			}
			if ((!($row->name == $row->username) || $ns == 1) && $ns != 0 && $ns != 3) {
				$users[] = str_replace("'", "\'", $row->name);
			}
		}
		array_multisort($users, SORT_ASC);

		$i = 0;
		foreach($users as $user){
			$result .= "users[".$i."] = '".$user."';";
			$i++;
		}
		return $result;
	}
	
	/**
	* Method to get the message limit
	* 
	* @access public
	* @return int The maxsize of the Inbox
	*/
	function getMessageLimits(){
		$types = array( "Super Administrator", "Administrator", "Manager", "Publisher", "Editor", "Author", "Registered" );
		$reversedTypes = array( "Super Administrator"=>0, "Administrator"=>1, "Manager"=>2, "Publisher"=>3, "Editor"=>4, "Author"=>5, "Registered"=>6 );
		
		$db =& JFactory::getDBO();
		$query = "SELECT groupName, messageLimit FROM #__messaging_groups";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$messageLimits = array();
		for($i = 0; $i < sizeof($rows); $i++){
			$type = $reversedTypes[$rows[$i]->groupName];
			
			$messageLimits[$type] = $rows[$i]->messageLimit;
		}
		
		return $messageLimits;
	}
	
	function getNameSuggestion(){
		$db =& JFactory::getDBO();
		$query = "SELECT messageLimit FROM #__messaging_groups WHERE n=7";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows[0]->messageLimit;
	}
	
	function getSendNotify(){
		$db =& JFactory::getDBO();
		$query = "SELECT messageLimit FROM #__messaging_groups WHERE n=8";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows[0]->messageLimit;
	}
	
	function getLimitAddress(){
		$db =& JFactory::getDBO();
		$query = "SELECT messageLimit FROM #__messaging_groups WHERE n=9";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows[0]->messageLimit;
	}
}
?>
