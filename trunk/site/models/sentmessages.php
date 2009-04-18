<?php
/**
 * @package	Messaging
 * @subpackage	Components
 * @link		http://joomlacode.org/gf/project/messaging/
 * @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class MessagesModelSentMessages extends JModel
{
	var $_data;

	function _buildQuery()
	{
		$user =& JFactory::getUser();
		$query = 'SELECT n, idTo, subject, message, date FROM #__messaging WHERE idFrom='.$user->id.' ORDER BY date DESC';

		return $query;
	}
	
	/**
	* Method to get the data of all messages
	* 
	* @access public
	* @return array	All messages
	*/
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}
		
		for($i = 0; $i < sizeof($this->_data); $i++){
			$this->_data[$i]->message = $this->replaceSmilies($this->_data[$i]->message);
			$this->_data[$i]->message = $this->replaceBBCode($this->_data[$i]->message);
		}
		
		return $this->_data;
	}
	
	/**
	* Method to replace all the BBCode
	* 
	* @access public
	* @return string	Input with all BBCode->html
	*/
	function replaceBBCode($input){
		$output = $input;
		
		//Simple regexes
		$regexes = array('/\[b\](.*)\[\/b\]/iUs', '/\[i\](.*)\[\/i\]/iUs', '/\[u\](.*)\[\/u\]/iUs',
						'/\[img\](.*)\[\/img\]/iUs', '/\[quote\](.*)\[\/quote\]/iUs', '/\[code\](.*)\[\/code\]/iUs',
						'/\[url\](.*)\[\/url\]/iU', '/\[email\](.*)\[\/email\]/iU');
		$curTasks = array('b', 'i', 'u', 'img', 'quote', 'code', 'url', 'email');
		
		//loop through regular expressions
		for($i = 0; $i < sizeof($regexes); $i++){
			$regex = $regexes[$i];
			$curTask = $curTasks[$i];
			
			//Find all
			preg_match_all($regex, $output, $matches);
			
			$count = count($matches[0]);
			
			//If found
		 	if($count){
				for($j=0; $j < $count; $j++){
					$replace = $this->getCode($curTask, $matches[1][$j]);
					$output	= preg_replace( $regex, $replace, $output, 1);
				}
			}
		}
		
		//Regexes with extra options
		$regexes = array('/\[url=(.*)\](.*)\[\/url\]/iUs', '/\[size=(.*)\](.*)\[\/size\]/iUs', '/\[color=(.*)\](.*)\[\/color\]/iUs', '/\[quote=(.*)\](.*)\[\/quote\]/iUs');
		$curTasks = array('url', 'size', 'color', 'quote');
		
		for($i = 0; $i < sizeof($regexes); $i++){
			$regex = $regexes[$i];
			$curTask = $curTasks[$i];
			
			preg_match_all($regex, $output, $matches);
			
			$count = count($matches[0]);
			
		 	if($count){
				for($j=0; $j < $count; $j++){
					$replace = $this->getCode($curTask, $matches[1][$j], $matches[2][$j]);
					$output	= preg_replace( $regex, $replace, $output, 1);
				}
			}
		}
		
		return $output;
	}
	
	//Example if  there are two parts:
	//$curTask			     $match			   $match2
	//   |					|			          |
	//[url	=	http://www.wikipedia.org/]	Wikipedia	[/url]
	//=><a href='$match'>$match2</a>
	//Example if  there is only one part:
	//$curTask		$match = $match2
	//  |				    |
	//[url]		http://www.wikipedia.org/	[/url]
	//=><a href='$match'>$match</a>
	/**
	* Method to get the code for a certain tag
	* 
	* @access public
	* @return string	Code in html
	*/
	function getCode($curTask, $match, $match2 = ""){
		if($match2 == ""){
			$match2 = $match;
		}
		
		$startTag = '';
		$endTag = '';
		
		//Get the right tags
		switch($curTask){
			case "b":
			case "i":
				$startTag = "<".$curTask.">";
				$endTag = "</".$curTask.">";
				break;
			case "u":
				$startTag = '<span style="text-decoration: underline;">';
				$endTag = '</span>';
				break;
			case "img":
				//If the url isn't valid
				if(parse_url($match) == false)
					return $match2;		//Return without the tags
				$startTag = '<img src="';
				$endTag = '" alt="" />';
				break;
			case "quote":
				$startTag = '<div style="margin-left: 10px;"><table style="border-collapse: collapse;"><tr>';
				$startTag .= '<td style="border: 1px solid black; padding: 5px;">'.JText::_('QUOTE');
				if($match != $match2){
					$startTag .= ': '.$match;
				}
				$startTag .= '</td></tr><tr><td style="border: 1px solid black; padding: 5px;">';
				
				$endTag = '</td></tr></table></div>';
				break;
			case "code":
				$startTag = '<div style="font-family: monospace;"><table style="border-collapse: collapse;"><tr>';
				$startTag .= '<td style="border: 1px solid black; padding: 5px;">'.JText::_('CODE');
				$startTag .= '</td></tr><tr><td style="border: 1px solid black; padding: 5px;">';
				
				$endTag = '</td></tr></table></div>';
				break;
			case "url":
				//If the url isn't valid
				if(parse_url($match) == false)
					return $match2;		//Return without the tags
				$startTag = '<a  target="_blank" href="'.$match.'">';
				$endTag = '</a>';
				break;
			case "email":
				//If the email adress isn't valid
				if(!eregi("[A-Za-z0-9_-]+([.]{1}[A-Za-z0-9_-]+)*@[A-Za-z0-9-]+([.]{1}[A-Za-z0-9-]+)+", $match))
					return $match2;		//Return without the tags
				$startTag = '<a href="mailto:'.$match.'">';
				$endTag = '</a>';
				break;
			case "size":
				$startTag = '<span style="font-size: '.intval($match).'px;">';
				$endTag = '</span>';
				break;
			case "color":
				//If the color  isn't valid
				if(!eregi("[#A-Za-z0-9]*", $match))
					return $match2;		//Return without the tags
				$startTag = '<span style="color: '.$match.';">';
				$endTag = '</span>';
				break;
			
			default:
				return $match2;
				break;
		}
		
		//Build the code
		$html = $startTag.$match2.$endTag;
		
		return $html;
	}
	
	/**
	* Method to replace all smilies
	* 
	* @access public
	* @return string	Code with img tags instead of the symbols
	*/
	function replaceSmilies($input){;
		$dir = JURI::base().$this->baseurl."components/com_messaging/smilies";
		//Smilies from
		$smiliesSign = array("O:)",":)",":(",";)",":P","8)","B)",":D",":[",":O",":'(",":\\","*JOKINGLY*",":!","*STOP*","@}->--","*THUMBS UP*",
							"*DRINK*","*HELP*","%)","*OK*","*SORRY*","*BRAVO*","*LOL*","*NO*","*CRAZY*","*YAHOO*","*YES*","*WALL*","*WRITE*","*SCRATCH*",
							"*jokingly*","*stop*","*thumbs up*","*drink*","*help*","*ok*","*sorry*","*bravo*","*lol*","*no*","*crazy*",
							"*yahoo*","*yes*","*wall*","*write*","*scratch*",
							"*Jokingly*","*Stop*","*Thumbs Up*","*Drink*","*Help*","*Ok*","*Sorry*","*Bravo*","*Lol*","*No*","*Crazy*",
							"*Yahoo*","*Yes*","*Wall*","*Write*","*Scratch*");
		//File names
		$smiliesFile = array("aa.gif","ab.gif","ac.gif","ad.gif","ae.gif","af.gif","af.gif","ag.gif","ah.gif","ai.gif","ak.gif","ao.gif","ap.gif",
							"at.gif","av.gif","ax.gif","ay.gif","az.gif","bc.gif","be.gif","bf.gif","bh.gif","bi.gif","bj.gif","bl.gif","bm.gif",
							"bp.gif","bs.gif","bu.gif","bv.gif","bw.gif",
							"ap.gif","av.gif","ay.gif","az.gif","bc.gif","bf.gif","bh.gif","bi.gif","bj.gif","bl.gif","bm.gif","bp.gif","bs.gif",
							"bu.gif","bv.gif","bw.gif",
							"ap.gif","av.gif","ay.gif","az.gif","bc.gif","bf.gif","bh.gif","bi.gif","bj.gif","bl.gif","bm.gif","bp.gif","bs.gif",
							"bu.gif","bv.gif","bw.gif");
		
		for($i = 0; $i < sizeof($smiliesFile); $i++){
			$file = $dir."/".$smiliesFile[$i];
			$smiliesFile[$i] = '<img src="'.$file.'" alt="'.str_replace("-", "&#45;", $smiliesSign[$i]).'" title="'.str_replace("-", "&#45;", $smiliesSign[$i]).'">';
		}
		
		$output = str_replace($smiliesSign, $smiliesFile, $input);
		return $output;
	}
}
