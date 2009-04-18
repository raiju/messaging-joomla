<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

/**
 * Messaging Component Router
 * @package	Messaging
 * @subpackage	Components
 */
 
/**
* Method to build the Route
*/
function MessagingBuildRoute(&$query)
{
	$segments = array();
	
	//view is required
	if(isset($query['view'])){
		//if set, use that view
		$segments[] = $query['view'];
		
		unset($query['view']);
	}else{
		//Else, set the default view
		$segments[] = "messages";
	}
	
	//controller is also required
	if(isset($query['controller'])){
		$segments[] = $query['controller'];
		
		unset($query['controller']);
	}else{
		$segments[] = "";
	}
	
	//do not pass on temp and temp2
	if(isset($query['temp'])){
		unset($query['temp']);
	}
	if(isset($query['temp2'])){
		unset($query['temp2']);
	}
	
	//Return all parts that haven't been used until now. These will go to the URL
	return $segments;
}

/**
* Method to parse the Route
*/
function MessagingParseRoute($segments)
{
	$vars = array();
	
	//Get the vars set previously
	$vars['view'] = $segments[0];
	if($segments[1] != ""){
		$vars['controller'] = $segments[1];
	}

	return $vars;
}
?>