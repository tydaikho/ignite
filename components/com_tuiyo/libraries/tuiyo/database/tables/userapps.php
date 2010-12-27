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
 * TuiyoTableUserApps
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableUserapps extends JTable{
	
	var $ID 			= null;
	
	var $appID			= null;
	
	var $userID 		= null;
	
	var $ordering 		= null;
	
	var $lastUsed  		= null;
	
	var $dateAdded 		= null;
	
	var $appName		= null;
	
	var $hasProfile		= 0;
	
	var $hasSearch		= 0;
	
	var $hasNotifications = 0;
	
	var $hasTimeline	= 0 ;
	
	
	/**
	 * TuiyoTableUserapps::__construct()
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db = null)
	{
		parent::__construct("#__tuiyo_userapps", "ID", $db );	
	}
	
	/**
	 * TuiyoTableUserapps::userHasApp()
	 * Checks whether a user already has an application
	 * Added to their profile
	 * @param mixed $appName
	 * @param mixed $userID
	 * @return void
	 */
	public function userHasApp($appName, $userID ){
		
		static $checked = array();
		
		if(isset($checked[$userID.$appName])){
			return $checked[$userID.$appName];
		}
		
		$appName= strtolower( $appName ) ;
		$dbo 	= &$this->_db ;
		$query 	= "SELECT appID "
				. "\nFROM #__tuiyo_userapps"
				. "\nWHERE userID=".$dbo->Quote((int)$userID )
				. "\nAND appName=".$dbo->Quote( $appName )
				;
		$dbo->setQuery( $query );
		$result = (int)$dbo->loadResult();
		
		$checked[$userID.$appName] = false;
		
		if(!empty($result) && intval($result)>0  ){
			
			$dbo->setQuery( 
				"UPDATE #__tuiyo_userapps "
				. "\nSET lastUsed=".$dbo->quote( date('Y-m-d H:i:s') )
				. "\nWHERE appID=".$dbo->quote( (int)$result )
				. "\nAND userID=".$dbo->quote( (int)$userID )
			);
			$dbo->query();
			
			$checked[$userID.$appName]  = true;
		}
		
		//echo (bool)$checked[$userID.$appName];
		
		return (bool)$checked[$userID.$appName] ;
		
	}
	
	/**
	 * TuiyoTableUserapps::uninstallUserApp()
	 * Removes an application from the user tables
	 * @param mixed $appName
	 * @param mixed $userID
	 * @return void
	 */
	public function uninstallUserApp($appName, $userID){
		
		$appName= strtolower( $appName );
		$dbo	= $this->_db;
		$query 	= "DELETE FROM #__tuiyo_userapps"
				. "\n WHERE appName=".$dbo->Quote( $appName ) 
				. "\n AND userID = ".$dbo->Quote( $userID )
				;
		$dbo->setQuery( $query );
		$dbo->query();
		
		echo $dbo->getQuery();
		
		return true;
	}
	
	/**
	 * TuiyoTableUserapps::getAlluserApps()
	 * Gets All or one a single user App meeting the specified conditions
	 * @param mixed $userID
	 * @param mixed $appName case sensitive
	 * @return
	 */
	public function getAllUserApps(  $userID, $appName = null, $limit=NULL, $orderby = 'indentifier', $orderDir = 'ASC'){
		
		$db 		= $this->_db;
		$cd 		= (!empty($appName)&&$appName)? "\nAND a.appName =".$db->Quote( $appName ) : null;
		$orderby	= (!empty($orderby)) ? "" : "" ;
		
		$query 	= "SELECT a.lastUsed, a.dateAdded, a.hasTimeline, a.hasSearch, a.hasProfile, a.hasNotifications, a.ID, p.* "
				. "\nFROM #__tuiyo_userapps a"
				. "\nRIGHT JOIN #__tuiyo_applications p "
				. "\nON a.appName = p.identifier"
				. "\nWHERE a.userID =".$db->Quote( (int)$userID )
				. $cd
				. "\nORDER BY p.identifier ASC"
				;
		
		$db->setQuery( $query );
		$rows 	= $db->loadAssocList( );
		
		//echo $db->getQuery();
		
		return (array)$rows ;		
	}
	
	/**
	 * TuiyoTableUserapps::getRecentlyUsed()
	 * Gets the most recently used applications
	 * @param mixed $userID
	 * @param integer $limit
	 * @return
	 */
	public function getRecentlyUsed( $userID, $limit = 10 ){
		
		$db 		= $this->_db;
		
		$query 	= "SELECT a.lastUsed, a.dateAdded, a.hasTimeline, a.hasSearch, a.hasProfile, a.hasNotifications, a.ID, p.* "
				. "\nFROM #__tuiyo_userapps a"
				. "\nRIGHT JOIN #__tuiyo_applications p "
				. "\nON a.appName = p.identifier"
				. "\nWHERE a.userID =".$db->Quote( (int)$userID )
				. "\nORDER BY a.lastUsed DESC"
				. "\nLIMIT $limit"
				;

		$db->setQuery( $query );
		$rows 	= $db->loadObjectList( );
		
		//echo $db->getQuery();
			
		return (array)$rows ;
	}
	
	/**
	 * TuiyoTableUserapps::incrAppUserCount()
	 * Increments the Application userCount by specified value
	 * @param mixed $appID
	 * @param integer $increment
	 * @return void
	 */
	public function incrAppUserCount($appID, $increment = 1){
		
		$db 	= $this->_db;
		$query 	= "UPDATE #__tuiyo_applications"
		. 		  "\nSET usersCount = usersCount+".(int)$increment
		. 		  "\nWHERE extID=".$db->Quote( (int)$appID )
		;
		
		$db->setQuery( $query );
		$db->query( );
		
		return true;
		
	}
	
	/**
	 * TuiyoTableUserapps::decrAppUserCount()
	 * Decreses use count Same as above with -1 but ..
	 * @param mixed $appID
	 * @param integer $increment
	 * @return
	 */
	public function decrAppUserCount($appID, $increment = 1){
		
		$db 	= $this->_db;
		$query 	= "UPDATE #__tuiyo_applications"
		. 		  "\nSET usersCount = usersCount - ".(int)$increment
		. 		  "\nWHERE identifier=".$db->Quote( (int)$appID )
		;
		
		$db->setQuery( $query );
		$db->query( );
		
		return true;
		
	}	
	
    /**
     * TuiyoTableApplications::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db=null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableUserapps::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableUserapps( $db  )	;	
		}
		return $instance;	 
	 }		
}