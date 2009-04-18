<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Hello Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
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
	}
	
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
	
	function store()
	{
		$data = JRequest::get( 'get' );
		$messageLimit = $data['messageLimit'];
		$individual = $data['individualChange'];
		
		$db =& JFactory::getDBO();
		
		for($i = 0; $i < sizeof($messageLimit); $i++){
			if($individual == 1){
				$query = "UPDATE #__messaging_groups SET messageLimit=".$messageLimit[$i]." WHERE n=".$i;
			}else{
				$query = "UPDATE #__messaging_groups SET messageLimit=".$data['messageLimitDefault']." WHERE n=".$i;
			}
			$db->setQuery($query);
			$db->query();
		}
		
		$query = "UPDATE #__messaging_groups SET messageLimit=".intval($data['nameSuggestion'])." WHERE n=7";
		$db->setQuery($query);
		$db->query();
		$query = "UPDATE #__messaging_groups SET messageLimit=".intval($data['sendNotify'])." WHERE n=8";
		$db->setQuery($query);
		$db->query();
		$query = "UPDATE #__messaging_groups SET messageLimit=".intval($data['limitAddress'])." WHERE n=9";
		$db->setQuery($query);
		$db->query();
		
		return true;
	}
}
?>
