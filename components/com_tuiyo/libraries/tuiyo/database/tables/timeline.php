<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                           *
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
 /**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * TuiyoTableTimeline
 * 
 * @package tuiyo
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableTimeline extends JTable{
	
	var $ID				= null;
	
	var $datetime 		= null;
	
	var $userID 		= null;
	
	var $appID			= null;
	
	var $source	    	= null;
		
	var $template		= null;
	
	var $data			= null;
	
	var $state 			= null;
	
	var $type			= null;
	
	var $inreplyto		= 0;
	
	var $mentions 		= null;
	
	var $tags 			= null;
	
	var $likes 			= null;
	
	var $dislikes 		= null;
	
	var $sharewith		= null;
	
	var $geolocation 	= null;
	
	var $mapresourceid  = null;
	
	var $params			= null;
	
	/**
	 * TuiyoTableTimeline::__construct()
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( &$db ){
		parent::__construct( '#__tuiyo_timeline', 'ID', $db );
	}
	
	/**
	 * TuiyoTableTimeline::loadLastTimelineItem()
	 * 
	 * @param mixed $userID
	 * @param mixed $groupID
	 * @param mixed $storyID
	 * @param bool $isPublic
	 * @return void
	 */
	public function loadLastTimelineItem($userID = NULL, $groupID = NULL, $storyID = NULL, $isPublic = false )
	{		
		$dbo 		= $this->_db;
		
		$userID		= (empty($userID)) ? null: "\nAND t.userID =".$dbo->Quote((int)$userID ) ;					

		$query 		= "SELECT t.sharewith, t.userID as userid, t.ID as id, t.type as itemType, t.datetime, t.data as bodytext"
					. "\nFROM #__tuiyo_timeline t"
					. "\nWHERE t.state='1' AND t.type='status'".$userID 
					. "\nORDER BY t.datetime DESC"
		 			;
		$dbo->setQuery( $query, 0 , 1 );
		$status 	= $dbo->loadObject();
		$return 	= null ;
		
		if(is_object($status)){
			$return = $status ;
		}
		return $status;
	}
	
	/**
	 * TuiyoTableTimeline::loadTimeline()
	 * Gets all activities from the activity table
	 * @param mixed $userID
	 * @param bool $isPublic
	 * @return
	 */
	public function loadTimeline($userID = NULL, $groupID = NULL, $storyID = null, $isPublic = FASLE , $limitstart=0, $limit = 20, $statusID = NULL , $type = null , $source = null){
		
		$dbo 		= $this->_db;
		
		$inReplyTo 	= (!empty($storyID)&&(int)$storyID > 0) 
					? "\nAND t.inreplyto=".$dbo->Quote( (int)$storyID ) 
					: "\nAND t.inreplyto='0'";
					
		$statusID 	= (!empty($statusID)&&(int)$statusID > 0) ? 
						"\nAND t.ID=".$dbo->Quote( (int)$statusID ) : null ;
					
		$userID		= (empty($userID)) ? null: 
						"\nAND t.userID =".$dbo->Quote((int)$userID )." OR t.sharewith REGEXP ".$dbo->quote( "%p{$userID}%" );
						
		$isPublic 	= (!is_bool($isPublic)) ? null : 
						($isPublic) ? "\nAND t.sharewith REGEXP ".$dbo->quote( "%p00%" ) : null ; 
						
		$groupID 	= (empty($groupID)) ? null :
						"\nAND t.sharewith REGEXP ".$dbo->quote( "%g{$groupID}%" );
		
		$filterType = (empty($type) || (string)$type == "status") ? null :
						"\nAND t.type =".$dbo->Quote( trim($type) );
		
		$sourceType = (empty($source) || (string)$source == "") ? null :
						"\nAND t.source =".$dbo->Quote( trim($source) );
						
		$order		= (empty($storyID))? "\nORDER BY t.datetime DESC" : "\nORDER BY t.datetime ASC" ;
		
		$query 	= "SELECT SQL_CALC_FOUND_ROWS t.sharewith, t.userID as userid, t.ID as id, t.type as itemType, t.datetime, t.data as bodytext,"
				. "u.username, a.name as source, a.extID, t.likes, t.dislikes,"
				. "t.inreplyto as parentid, 1 as cancomment, 0 as candelete,'' as userpic, '' as comments, a.identifier, s.* "
				. "\nFROM #__tuiyo_timeline t"
				. "\nLEFT JOIN #__tuiyo_applications a ON t.appID = a.extID"
				. "\nLEFT JOIN #__tuiyo_timelinetmpl s ON t.template = s.ID"
				. "\nLEFT JOIN #__users u  ON t.userID = u.id"
				. "\nWHERE t.state='1'"
				. ( !is_null($statusID)? $statusID : $inReplyTo.$userID.$isPublic.$groupID )
				. $filterType
				. $sourceType
				. $order
				;
				
		$dbo->setQuery( $query , $limitstart , $limit );
		$rows = $dbo->loadObjectList( );
		
		//echo $dbo->getQuery();
		
		$dbo->setQuery('SELECT FOUND_ROWS();'); 
		
		return (array)$rows;
	}
	
	/**
	 * TuiyoTableTimeline::getAllCommenters()
	 * Gets all users who participated on a status update
	 * @param mixed $inReplyTo
	 * @return void
	 */
	public function getAllCommenters( $inReplyTo )
	{
		if( (int)$inReplyTo > 0 ):
			$dbo 	= $this->_db;
			
			$query1 = "SELECT t.userID as participant, s.userID as author FROM #__tuiyo_timeline t"
					.	"\nLEFT JOIN #__tuiyo_timeline s ON t.inreplyto = s.ID"
					.	"\nWHERE t.inreplyto =".$dbo->quote( (int)$inReplyTo )
					.	"\nGROUP BY t.userID;"
					;
			//Participant v Author
			$dbo->setQuery( $query1 );
			$result1 = $dbo->loadObjectList();
			
			//Sharewith Authors
			$query2 = "SELECT j.sharewith FROM  #__tuiyo_timeline j WHERE j.ID=".$dbo->quote( (int)$inReplyTo );
			$dbo->setQuery( $query2 );
			
			$result2 = $dbo->loadResult();
			$result2 = json_decode( $result2 );
									
			foreach( (array)$result2 as $key=>$participant ):			
				if( strpos( $participant , "%p" ) !== false ):
					$participating 		= new stdClass ;
					$participatinguser 	= str_replace( array("%p", "%"), array("", ""), (string)$participant );

					$participating->author = intval($participatinguser );
					$participating->participant = intval($participatinguser );
					
					if( $participating->author > 0 ):
						$result1[] = $participating ;
					endif;
					
				endif;
			endforeach;
			
			$result = array(
				"author" 	=>array(),
				"participant"=>array()
			);
			
			//Sort into Authors and Participants
			foreach($result1 as $sp ):
				if(!in_array($sp->author , $result["author"])):
					$result["author"][] = $sp->author;
				endif;
				if(!in_array($sp->participant , $result["participant"])):
					$result["participant"][] = $sp->participant;
				endif;
			endforeach; 
			
			//Remove Authors who commented
			foreach($result["author"] as $author):
				$index = array_search($author, $result["participant"]);
				if($index !== FALSE):
					unset($result["participant"][$index]);
				endif;
			endforeach;
			
			return $result;
			
		else:
			return array();
		endif; 
	}
	
	/**
	 * TuiyoTableTimeline::countUserActivities()
	 * Get activity documentation
	 * @param mixed $userID
	 * @param mixed $groupID
	 * @param mixed $isPublic
	 * @return void
	 */
	public function countUserActivities($userID = NULL, $groupID = NULL, $isPublic = NULL, $inReply = FALSE )
	{
		$dbo 		= $this->_db;
				
		//Reply Type
		$inReplyTo 	= ($inReply) ? "\nAND t.inreplyto != 0": NULL;
			
		//UserID		
		$userID		= (empty($userID)) ? null: "\nAND t.userID =".$dbo->Quote((int)$userID )." OR t.sharewith REGEXP ".$dbo->quote( "%p{$userID}%" );
		//Public Messages				
		$isPublic 	= (!is_bool($isPublic)) ? null :  ($isPublic) ? "\nAND t.sharewith REGEXP ".$dbo->quote( "%p00%" ) : null ; 
		
		//Group Messages				
		$groupID 	= (empty($groupID)) ? null : "\nAND t.sharewith REGEXP ".$dbo->quote( "%g{$groupID}%" );
		
		$query 	= "SELECT COUNT(*)"
				. "\nFROM #__tuiyo_timeline t"
				. "\nWHERE t.state='1'"
				. $inReplyTo.$userID.$isPublic.$groupID 
				;
				
		$dbo->setQuery( $query );
		//echo $dbo->getQuery();
		
		$count = $dbo->loadResult();
		
		return $count ;											
	}
	
    /**
     * TuiyoTableTimeline::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableTimeline::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableTimeline( $db  )	;	
		}
		
		return $instance;	 
	 }	
	
}