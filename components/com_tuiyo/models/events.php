<?php
/**
 * ******************************************************************
 * Events model Class/Object for the Tuiyo platform               *
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
 * TuiyoModelEvents
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoModelEvents extends JModel{
	
    /**
     * Total number of items
     * @var integer
     */
    public $_total = null;

    /**
     * The Pagination object
     * @var object
     */
    public $_pagination = null;
		
	
	/**
	 * TuiyoModelEvents::getAllEvents()
	 * Gets All Events the user will be attending
	 * @param mixed $userID
	 * @param string $filterType
	 * @param string $cMonth
	 * @param string $cYear
	 * @param integer $limit
	 * @return void
	 */
	public function getAllEvents( $userID, $filterType="" , $cMonth = "" , $cYear = "" , $limit = 10 )
	{
		$eventTable	= TuiyoLoader::table('events', true );
		$rsvpTable	= TuiyoLoader::table('eventsrsvp', true );		
	}
	
	/**
	 * TuiyoModelEvents::getUserEvents()
	 * 
	 * @param mixed $userID
	 * @param mixed $cMonth
	 * @param mixed $cYear
	 * @return
	 */
	public function getUserEvents( $userID , $cMonth, $cYear )
	{
		$eventTable	= TuiyoLoader::table('events', true );
		$rsvpTable	= TuiyoLoader::table('eventsrsvp', true );
		
		$events 	= $eventTable->getUserEvents( $userID , $cMonth , $cYear );
		
		$timeStamp  = mktime(0,0,0,$cMonth,1,$cYear);
		$numDays	= date("t", $timeStamp);
		$dateInfo 	= getdate( $timeStamp );		
		
		//Sort
		$sorted 	= array();
		
		foreach($events as $event):
		
			$eTimeStamp = strtotime( $event->starttime );			
			$eDateInfo 	= getdate( $eTimeStamp );			
			$sorted[$eDateInfo["mday"]][] = $event ;
						
		endforeach;
		
		return $sorted;
	}	
	
	/**
	 * TuiyoModelEvents::getUserDayEvents()
	 * 
	 * @param mixed $userID
	 * @param mixed $date
	 * @return void
	 */
	public function getUserDayEvents( $userID, $date)
	{
		$eventTable	= TuiyoLoader::table('events', true );
		$events 	= $eventTable->getUserEventsStartingOn( $date, $userID);
		
		return $events ;
		
	}
	
	public function setUserRSVP(){
		
		$eventTable	= TuiyoLoader::table('events', true );
		$rsvpTable	= TuiyoLoader::table('eventsrsvp', true );
		$auth		= TuiyoAPI::get('authentication');
		$doc 		= TuiyoAPI::get('document');
		
		//Must be loggged IN
		$auth->requireAuthentication();
	}
	
	/**
	 * TuiyoModelEvents::addEvent()
	 * Creates and stores a new User Event
	 * @param mixed $userID
	 * @param mixed $postData
	 * @return void
	 */
	public function addEvent($userID, $postData)
	{
		$eventTable	= TuiyoLoader::table('events', true );
		$rsvpTable	= TuiyoLoader::table('eventsrsvp', true );
		
		$auth		= TuiyoAPI::get('authentication');
		$doc 		= TuiyoAPI::get('document');
		
		//Must be loggged IN
		$auth->requireAuthentication();

		$eventTable->title 		= trim($postData['title']) ;
		
		if(empty($eventTable->title)){
			$doc->enqueMessage(_('You did not specify an event title') , 'error');
			return false;
		}
		
		$eventTable->location 	= trim($postData['location']) ;
		$eventTable->street		= trim($postData['street']) ;
		$eventTable->city		= trim($postData['city']);
		
		$eventTable->startdate	= date('Y-m-d', strtotime( $postData['startdate']) );
		$eventTable->enddate	= date('Y-m-d', strtotime( $postData['enddate']) );
		
		$startYear 	= date('Y', strtotime($postData['startdate'] ) );
		$startMonth = date('m', strtotime($postData['startdate']) );
		$startDay	= date('d', strtotime($postData['startdate']) );
		
		$startHour 	= (int)$postData['startTimeHour'];
		$startMin	= (int)$postData['startTimeMin'];
		$startSec	= (int)$postData['startTimeSec'];

		$endYear 	= date('Y', strtotime($postData['enddate']) );
		$endMonth 	= date('m', strtotime($postData['enddate']) );
		$endDay		= date('d', strtotime($postData['enddate']) );
		
		$endHour 	= (int)$postData['endTimeHour'];
		$endMin		= (int)$postData['endTimeMin'];
		$endSec		= (int)$postData['endTimeSec'];			
				

		$startTime 	= mktime( $startHour, $startMin, $startSec ,  $startMonth , $startDay , $startYear );
		$endTime 	= mktime( $endHour, $endMin, $endSec , $endMonth , $endDay , $endYear );
		
		if($endTime < $startTime){
			$doc->enqueMessage( _('The event end Date/Time cannot be before the start Date/Time') , "error");
			return false;
		}
		
		$eventTable->starttime	= date( 'Y-m-d H:i:s', $startTime );	
		$eventTable->endtime	= date( 'Y-m-d H:i:s', $endTime );	
		
		$eventTable->description= $postData['description'];
		$eventTable->poster		= trim( htmlspecialchars_decode( $postData['poster'] ) );
		$eventTable->privacy 	= (int)$postData['privacy'];
		$eventTable->createdby	= (int)$userID ;
		
		if(!$eventTable->store()){
			$doc->enqueueMessage( $eventTable->getError() , "error" );
			return false;
		}
		
		//addHost to Event
		$rsvpTable->eventid 	= $eventTable->eventid;
		$rsvpTable->role		= 'host';
		$rsvpTable->userid		= $eventTable->createdby ;
		$rsvpTable->approved 	= 1 ;
		
		if(!$rsvpTable->store()){
			$eventTable->delete();
			$doc->enqueueMessage( _('An error occured whilst adding you to the event'), "error");
			$doc->enqueueMessage( $eventTable->getError() , "error" );
			return false;
		}	
		
		return true;
	}
	
	/**
	 * TuiyoModelEvents::__construct()
	 * Construct the Events Model;
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	
	    global $mainframe, $option;
	
	    // Get pagination request variables
	    $limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	
	    // In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	    $this->setState('limit', $limit ); //$limit
	    $this->setState('limitstart', $limitstart);
	    
	    $this->pageNav  = NULL ;
	}	
	
}