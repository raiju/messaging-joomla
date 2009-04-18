<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


class TableMessage extends JTable
{
	/*
	MySql syntax:
	CREATE TABLE `#__messaging` (
		`n` int(11) NOT NULL auto_increment,
		`idFrom` int(11) NOT NULL,
		`idTo` int(11) NOT NULL,
		`subject` varchar(100) NOT NULL,
		`message` text NOT NULL,
		`seen` bool NOT NULL,
		`date` datetime NOT NULL,
		PRIMARY KEY  (`n`)
	)
	*/
	var $n = null;
	var $idFrom = null;
	var $idTo = null;
	var $subject = null;
	var $message = null;
	var $seen = null;
	var $date = null;

	function __construct(& $db) {
		parent::__construct('#__messaging', 'n', $db);
		
		jimport('joomla.utilities.date');
		$now = new JDate();
		$this->set( 'date', $now->toMySQL() );
		$user =& JFactory::getUser();
		$this->set( 'idFrom', $user->id );
		$this->set( 'seen', '0');
	}
}
?>
