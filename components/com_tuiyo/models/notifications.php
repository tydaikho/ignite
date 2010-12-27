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
 * joomla MOdel
 */
jimport('joomla.application.component.model');


/**
 * TuiyoModelNotifications
 * 
 * @package Joomla
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoModelNotifications extends JModel
{
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
     * TuiyoModelNotifications::getAllNotifications()
     * Add function documentation
     * @param mixed $userID
     * @param mixed $status
     * @return void
     */
    public function getAllNotifications( $userID, $status = NULL )
	{
		$nTable 	= TuiyoLoader::table("notifications", true );
		
		
		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart' );
		
		$notices 	= $nTable->getUserNotifications( (int)$userID , $limitstart, $limit );
		
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $nTable->_db ;
		$this->_total		= $dbo->loadResult();
		
		$pageNav 			= new JPagination( $this->_total, $limitstart, $limit );
		
		$this->pageNav 		= $pageNav->getPagesLinks();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		$this->setState('pagination' ,  $this->pageNav );		
		
		return $notices ;
		
	}
	
	/**
	 * TuiyoModelNotifications::loadNotice()
	 * Remove LoadNotice
	 * @param mixed $noticeID
	 * @param mixed $userID
	 * @return
	 */
	public function loadNotice($noticeID, $userID ){
		
		$nTable 	= TuiyoLoader::table("notifications", true );
		$document   = TuiyoApi::get("document");
		$nTable->load( (int)$noticeID ); 
		
		if( (int)$userID === (int)$nTable->userid ):
			$nTable->status = 1 ;
			if(!$nTable->store()){
				$document->enqueMessage( $nTable->getError(), "error") ;
			}
		endif; 
		
		return $nTable ;
		
	}
	
	/**
	 * TuiyoModelNotifications::removeNotice()
	 * Remove the notifications
	 * @param mixed $noticeID
	 * @param mixed $userID
	 * @return void
	 */
	public function removeNotice( $noticeID, $userID )
	{
		$notice 	= &$this->loadNotice( $noticeID  , $userID);
		$document   = TuiyoApi::get("document");		
				
		if( (int)$notice->userid === (int)$userID ):
		
			if(!$notice->delete()){
				$document->enqueMessage( $notice->getError(), "error") ;
				return false;
			}
			
		endif;
	}
	
	/**
	 * TuiyoModelNotifications::__construct()
	 * Construct the Tuiyo Notifications Model
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	
	    global $mainframe, $option;
	
	    // Get pagination request variables
	    $limit 		= 5;
	    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	
	    // In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	    $this->setState('limit', $limit ); //$limit
	    $this->setState('limitstart', $limitstart);
	    
	    $this->pageNav  = NULL ;
	}	
}