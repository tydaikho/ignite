<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
 * ******************************************************************
 */
 
/**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * TuiyoTableEvents
 * 
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoTableEvents extends JTable{
	
	//`eventid` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $eventid		= null;
	//`title` VARCHAR(150) NOT NULL,
 	var $title			= null ;
	//`location` VARCHAR(100),
	var $location 		= null ;
	//`street` VARCHAR(100),
	var $street 		= null ;
	//`city` VARCHAR(100),
	var $city			= null ;
	//`coordinates` VARCHAR(255),
	var $coordinates 	= null ;
	//`stardate` DATE  NOT NULL,
	var $startdate		= null ;
	//`enddate` DATE  NOT NULL,
	var $enddate		= null ;
	//`starttime` TIMESTAMP NOT NULL,
	var $starttime		= null ;
	//`endtime` TIMESTAMP NOT NULL,
	var $endtime		= null ;
	//`privacy` ENUM('0','1','2') NOT NULL,
	var $privacy 		= null ;
	//`poster` TEXT,
	var $poster			= null ;
	//`type` VARCHAR(45) DEFAULT 'general',
	var $type			= null ;
	//`description` TEXT,
	var $description 	= null;
	//`params` TEXT,
	var $params			= null;
	//`createdby` INTEGER UNSIGNED NOT NULL,
	var $createdby		= null ;
	
	
    /**
     * TuiyoTableEvents::__construct()
     * 
     * @param mixed $db
     * @return
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_events", "eventid", $db );
    }
    
    /**
     * TuiyoTableEvents::getUserEvents()
     * 
     * @param mixed $userID
     * @param mixed $month
     * @param mixed $year
     * @return
     */
    public function getUserEvents( $userID , $cMonth, $cYear )
	{
		$dbo		= $this->_db ;
		$userID		= $dbo->Quote( (int)$userID );
		$timestamp  = mktime(0,0,0,$cMonth,1,$cYear);
		$startdate 	= date('Y-m-d', $timestamp );
		$lastDate	= date("t", $timestamp) ;
		$enddate	= date('Y-m-d' , strtotime( $cYear.'-'.$cMonth.'-'.$lastDate ) );
		 
		$query 		= "SELECT r.role, e.* , i.username, i.name, i.email FROM #__tuiyo_events_rsvp AS r
					LEFT JOIN #__users AS i ON r.userid = i.id
					LEFT JOIN #__tuiyo_events as e ON r.eventid = e.eventid
					WHERE r.userid = {$userID} AND e.startdate >= '{$startdate}' AND e.startdate <= '{$enddate}'
					ORDER BY e.startdate ASC"
				;
		$dbo->setQuery( $query );
		
		//echo $dbo->getQuery();
		
		return $dbo->loadObjectList();
		
	}
	
	/**
	 * TuiyoTableEvents::getUserEventsStartingOn()
	 * Gets all On day events
	 * @param mixed $date
	 * @param mixed $userID
	 * @return
	 */
	public function getUserEventsStartingOn( $startdate, $userID){
		
		$dbo		= $this->_db ;
		$userID		= $dbo->Quote( (int)$userID );
		 
		$query 		= "SELECT r.role, e.* , i.username, i.name, i.email FROM #__tuiyo_events_rsvp AS r
					LEFT JOIN #__users AS i ON r.userid = i.id
					LEFT JOIN #__tuiyo_events as e ON r.eventid = e.eventid
					WHERE r.userid = {$userID} AND e.startdate = '{$startdate}'
					ORDER BY e.startdate ASC"
				;
				
		$dbo->setQuery( $query );
		
		//echo $dbo->getQuery();
		
		return $dbo->loadObjectList();		
	}
	
	public function getAllUsersEvents(){}
	
	public function getAllCommunityEvents(){}
    	
	
    /**
     * TuiyoTableEvents::getInstance()
     * Gets an instance of the Tuiyo Events Table Object
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance) && !empty($instance) && $ifNotExist) {
            if (is_object($instance)) {
                return $instance;
            } else {
                unset($instance);
                TuiyoTableEvents::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableEvents($db);
        }
        return $instance;
    }		
}