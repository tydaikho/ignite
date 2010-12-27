<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform                              *
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
 * TuiyoUsers
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableUsers extends JTable
{	
	/**
	 * Ideally the same joomla userid, unique key (int)
	 */
	var $userID 		= null;
	
	/**
	 * The profile id, auto incremented, unique key (int)
	 */
	var $profileID  	= null;
	
	/**
	 * The profile Name, could be different from joomla name (text)
	 */
	var $profileName 	= null;
	
	/**
	 * The datetime, the profile was created (datetime)
	 */
	var $dateCreated	= null;
	
	/**
	 * User sex, 1=Male, 0=Femail (enum(0,1))
	 */
	var $sex			= null;
	
	/**
	 * date of birth (date)
	 */
	var $dob			= null;
	
	/**
	 * profile picture filename, excludes location dir (varchar(100))
	 */
	var $picture		= null;
	
	/**
	 * User last status id, linked to in the activity table (int)
	 */
	var $statusID		= null;
	
	/**
	 * The number of times this profile is viewed
	 */
	var $profileView	= null;
	
	/**
	 * 	The overall profile rating if used
	 */
	var $profileRatings = null;
	
	/**
	 * The total votes attributed to this profile
	 */
	var $totalVotes		= null;
	
	/**
	 * Additional profile paramteters
	 */
	var $params			= null;
	
	/**
	 * Style ID, linked to the styles table
	 */
	var $styleID		= null;
	
	/**
	 * External ids, such as facebookId, Flickr Ids, etc!
	 */
	var $externalIDs	= null;
	
	/**
	 * Link to the privacy table!
	 */
	var $privacyID 		= null;
	
	/**
	 * Level of suspension, 0=active 1= supsended, 2=Banned, 3=Deactivated Enum
	 */
	var $suspended		 = null;
	
    /**
     * TuiyoUsers::__construct()
     * 
     * @param mixed $db
     * @return void
     */
    public function __construct(&$db)
    {
        parent::__construct('#__tuiyo_users', 'userID', $db);
    }
    
    /**
     * TuiyoTableUsers::storeObj()
     * Overites the parent store to allow for compound keys
     * 
     * @param bool $updateNulls
     * @return
     */
    public function storeObj( $updateNulls=false )
	{
		$key 	= "profileID";
		$db 	= $this->_db ;
		$tbl	= $this->_tbl;
		
		if(!empty($this->$key)){
			$return = $db->updateObject( $tbl, $this, $key, $updateNulls );
		}else{
			$return = $db->insertObject( $tbl, $this, $key );
		}
		
		if(!$return){
			$this->setError( get_class( $this ).'::storeObj failed - '.$db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
	public function getFeaturedUsers( $limit=10 , $newest=true, $oldest=false){
		
		$users = $this->getAllUsers(false, false, 0, $limit, $newest, $oldest );	
		
		return $users;
	
	}
    
    /**
     * TuiyoTableUsers::getInstance()
     * 
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
				TuiyoTableUsers::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableUsers( $db  )	;	
		}
		return $instance;	 
	 }
	 
	 /**
	  * TuiyoTableUsers::getUserStatusText()
	  * Gets the user status text from the activity table
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function getUserStatusText($userid = null)
	 {}
	 
	 /**
	  * TuiyoTableUsers::getUserAvatar()
	  * Gets the Current Users Avatar otherwise if id specified
	  * @param mixed $userid
	  * @return void
	  */
	 public function getUserAvatar( $userid ){
	 	
	 	$dbo   = $this->_db;
	 	$query = "SELECT CONCAT(r.filePath,r.fileName)"
		       . "\nFROM #__tuiyo_users u"
			   . "\nRIGHT JOIN #__tuiyo_resources r"
			   . "\nON u.picture = r.resourceID"
			   . "\nWHERE u.userID = ".$dbo->Quote((int)$userid );
	 	$dbo->setQuery( $query );
		$pic   = $dbo->loadResult(); 
		$pic   = (file_exists($pic)) ? $pic : TUIYO_FILES.DS.'noimage.jpg' ;
		
		return $pic;
	 }
	 
	 /**
	  * TuiyoTableUsers::setUserStatusId()
	  * sets the user status ID
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function setUserStatusId($userid = null )
	 {}
	 
	 
	 /**
	  * TuiyoTableUsers::getUserStyle()
	  * Gets the user Style from the style table
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function getUserStyle($userid = null)
	 {}
	 
	 /**
	  * TuiyoTableUsers::setUserStyleID()
	  * sets the user Style ID in the user table
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function setUserStyleID($userid = null)
	 {}
	 
	 /**
	  * TuiyoTableUsers::getMonthOfBirth()
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function getMonthOfBirth($userid = null )
	 {}
	 
	 /**
	  * TuiyoTableUsers::getDayMonthOfBirth()
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function getDayMonthOfBirth($userid = null )
	 {}
	 
	 
	 /**
	  * TuiyoTableUsers::getPrivacySettings()
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function getPrivacySettings($userid = null )
	 { }
	 
	 /**
	  * TuiyoTableUsers::setPrivacyID()
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function setPrivacyID($userid = null )
	 {}
	 
	 
	 /**
	  * TuiyoTableUsers::deleteProfile()
	  * 
	  * @param mixed $userid
	  * @return void
	  */
	 public function deleteProfile($userid = null)
	 {
		//Finally check all other tables before deleting profile
	 }
	 
	 
	 /**
	  * TuiyoTableUsers::getUserID()
	  * Method to get userID from userName;
	  * @param mixed $username
	  * @return interger 
	  */
	 public function getUserID($username)
	 {
	 	$dbo 	= $this->_db;
	 	$query 	= "SELECT u.id FROM #__users u "
	 			. "\nWHERE u.username=".$dbo->Quote( $username )
	 			. "\nLIMIT 1"
	 			;
		$dbo->setQuery( $query );
		$userID = $dbo->loadResult();
	
		//Return the userID
		return (int)$userID ;
	 }
	 
	 /**
	  * TuiyoTableUsers::userProfileExists()
	  * Checks if a profile exists;
	  * @param mixed $userID
	  * @return
	  */
	 public function userProfileExists( $userID ){
	 	
	 	$dbo 	= $this->_db ;
	 	$query 	= "SELECT COUNT(1) FROM #__tuiyo_users tu"
	 			. "\nWHERE tu.userID = ".$dbo->quote((int)$userID);
	 			
		$dbo->setQuery( $query );
		$result = $dbo->loadResult();
		
		return (bool)$result;
		
	 }
	 
	 /**
	  * TuiyoTableUsers::getAllUsers()
	  * Generates a list of all users. 
	  * @param bool $extended, Adds Avatars, SEX and DOB
	  * @param bool $blocked, Blocked status
	  * @return array of objects
	  */
	 public function getAllUsers( $extended = true , $blocked = false , $limitstart= 0, $limit = 10, $newest=true, $oldest=false){
	 	
	 	$user 	=& TuiyoAPI::get("user" , null );
		$state 	= ($blocked)? 1 : 0 ;
		 
	 	$dbo   	= $this->_db;
	 	
	 	$order	= ($newest) ? "\nORDER BY u.registerDate ASC" : "";
		$order  = ($oldest) ? "\nORDER BY u.registerDate DESC": $order ;
	 	
	 	$query 	= "SELECT SQL_CALC_FOUND_ROWS u.id, u.username, u.name, u.email, u.usertype, u.gid, u.registerDate, u.lastvisitDate" 
		        . "\nFROM #__users u"
	 			. "\nWHERE u.block =".$dbo->quote((int)$blocked)
	 			. $order
	 			;
	 	
		 $dbo->setQuery( $query , $limitstart , $limit );
		 
		 $users = $dbo->loadObjectList(); 
		 
		 $dbo->setQuery('SELECT FOUND_ROWS();'); 		 
		 
		 return (array)$users ;		
	 }
	 
	 /**
	  * TuiyoTableUsers::getAlOnlinelUsers()
	  * Gets list of users currently online
	  * @param bool $extended
	  * @param bool $blocked
	  * @param integer $limitstart
	  * @param integer $limit
	  * @return
	  */
	 public function getAllOnlineUsers( $extended = TRUE , $blocked = FALSE , $limitstart= 0, $limit = 10){
	 	
	 	$user 	=& TuiyoAPI::get("user" , null );
		$state 	= ($blocked)? 1 : 0 ;
		 
	 	$dbo   	= $this->_db;
	 	
	 	$query 	= "SELECT SQL_CALC_FOUND_ROWS s.userid, u.id, u.username, u.name, u.email, u.usertype, u.gid, u.registerDate, u.lastvisitDate "
		 		. "\nFROM #__session AS s" 
		        . "\nLEFT JOIN #__users AS u"
				. "\nON u.id = s.userid"
	 			. "\nWHERE u.block =".$dbo->quote((int)$blocked)
			    . "\nAND s.guest < 1"	 			
	 			. "\nGROUP BY s.userid ORDER BY s.time ASC"
	 			;
	 	
		 $dbo->setQuery( $query , $limitstart , $limit );
		 
		 //echo $dbo->getQuery();
		 
		 $users = $dbo->loadObjectList(); 
		 
		 $dbo->setQuery('SELECT FOUND_ROWS();'); 		 
		 
		 return (array)$users ;		
	 }	 

}
