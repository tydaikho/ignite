<?php
/**
 * ******************************************************************
 * Tuiyo Search Helper Classs                                       *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo                                               *
 * ******************************************************************
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
define( 'TUIYO_EXECUTE', 1 );

//get Time Helper
require_once('timer.php');

/**
 * TuiyoHelperSearch
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoHelperSearch{
	
	/**
	 * TuiyoHelperSearch::searchProfiles()
	 * @param mixed $text
	 * @param string $phrase
	 * @param string $ordering
	 * @return void
	 */
	public function searchProfiles($text, $phrase = '', $ordering = '')
	{
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();	
			
		$wheres = array();
		
		switch ($phrase) {
			case 'exact':
				$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$wheres2 	= array();
				$wheres2[] 	= "u.username LIKE ".$text;
				$wheres2[] 	= "u.name LIKE ".$text;
				$wheres2[]  = "u.email LIKE ".$text ;
				$where 		= '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
	
			case 'all':
			case 'any':
			default:
				$words = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$wheres2 	= array();
					$wheres2[] 	= "u.username LIKE ".$word;
					$wheres2[] 	= "u.name LIKE ".$word;
					$wheres2[]  = "u.email LIKE ".$word ;					
					$wheres[] 	= implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}
		$morder = '';
		switch ($ordering) {
			case 'alpha':	
			case 'newest':
				default:
				$order = 'u.name ASC';
				break;
		}
		
		$newArray = array();
		
		$query 	= "SELECT u.id, u.name, u.username, u.registerDate, u.lastvisitDate, u.usertype, "
				. "\nCONCAT(u.name, u.username) AS title"
				. "\nFROM #__users u"
				. "\nWHERE (".$where. ")"
				. "\nAND u.block = ".$db->Quote((int)0 ) //Only active users
				. "\nGROUP BY u.id"
				. "\nORDER BY ".$order
				;
		
		$db->setQuery( $query );
		$rows 	= $db->loadAssocList();
		
		if(!sizeof((array)$rows)> 0 ) return array();
		
		foreach($rows as $row):
		
			$rowObject 			= new stdClass ;
			$rowObject->href 	= JRoute::_( 'index.php?option=com_tuiyo&view=profile&do=view&pid='.$row["id"] ); 
			$rowObject->title 	= ucfirst( $row["name"]." ( ".$row["username"]." ) " ) ; 
			$rowObject->section = "Profile"; 
			$rowObject->created = $row["lastvisitDate"] ;
			$rowObject->text 	= $row["name"]." is a ".$row["usertype"]
								 ." user, registered with the username @{$row['username']}"
								 .TuiyoTimer::diff( strtotime( $row["registerDate"] ) )." and last seen here "
								 .TuiyoTimer::diff( strtotime( $row["lastvisitDate"] ) ) ;
			
			$rowObject->browsernav = 2;
			
			$newArray[] 	= $rowObject ;
				
		endforeach;
		
		return $newArray;
		
	}
	
	
	public function searchGroups(){}
	
	
	public function searchResources(){}
	
	
	public function searchPhotos(){}
	
	
	public function searchApplications(){}
	
 	/**
 	 * TuiyoHelperSearch::getInstance()
 	 * @param bool $ifNotExist
 	 * @return
 	 */
 	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoHelperSearch()	;	
		}
		return $instance;	
   }
   
   private function getUserAvatar(){
   	
   }	
}