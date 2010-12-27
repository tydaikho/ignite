<?php
/**
 * *************************************************************************
 * TuiyoTableFriends Class/Object for the Tuiyo platform                   * 
 *                                                                         *
 * Important Triggers for TuiyoTableFriends                                *
 *                                                                         * 
 * CREATE TRIGGER finsert BEFORE INSERT ON jos_tuiyo_friends               *
 *		FOR EACH ROW SET NEW.user1 = LEAST(NEW.thisUserID,NEW.thatUserID), * 
 *		NEW.user2 = GREATEST(NEW.thisUserID,NEW.thatUserID);               * 
 *                                                                         * 
 * CREATE TRIGGER fupdate BEFORE UPDATE ON jos_tuiyo_friends               *
 *		FOR EACH ROW SET NEW.user1 = LEAST(NEW.thisUserID,NEW.thatUserID), *
 *		NEW.user2 = GREATEST(NEW.thisUserID,NEW.thatUserID);               *
 *                                                                         *
 * @copyright : 2008 tuiyo Platform                                        *
 * @license   : http://platform.tuiyo.com/license   BSD License            * 
 * @version   : Release: $Id$                                              * 
 * @link      : http://platform.tuiyo.com/                                 * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                        * 
 * @access 	  : Public                                                     *
 * @since     : 1.0.0 alpha                                                *   
 * @package   : tuiyo                                                      *
 * *************************************************************************
 */
 

defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * TuiyoTableFriends
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableFriends extends JTable{
	
	var $ID				= null;
	
	var $type 			= "friend";
	
	var $listID 		= null;
	
	var $thisUserID		= null;
	
	var $thatUserID 	= null;
	
	var $state			= null;
	
	var $lastUpdated 	= null;
	
	var $user1			= null;
	
	var $user2			= null;
	
	/**
	 * TuiyoTableFriends::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( &$db ){
		parent::__construct( '#__tuiyo_friends', 'ID', $db );
	}
	
	/**
	 * TuiyoTableFriends::checkRelationship()
	 * Returns a relationship
	 * @param mixed $user1
	 * @param mixed $user2
	 * @return rel id if exists
	 */
	public function checkRelationship($user1, $user2){
		
		$dbo 	= $this->_db;
		$query 	= "SELECT r.ID FROM #__tuiyo_friends r"
				. "\nWHERE r.thisUserID=".$dbo->Quote( (int)$user1 )
				. "\nAND r.thatUserID=".$dbo->Quote((int)$user2 )
				. "\nOR r.thatUserID=".$dbo->Quote( (int)$user1 )
				. "\nAND r.thisUserID=".$dbo->Quote( (int)$user2 )
				. "LIMIT 1"
				;
		$dbo->setQuery( $query );
		$result = $dbo->loadResult( );
		
		return $result;
	}
	
	/**
	 * TuiyoTableFriends::getUserFriends()
	 * Gets the user friends lists. 
	 * @param mixed $userID
	 * @param bool $extended, include friends profiles, or just a simple list
	 * @return mixed array of objects
	 */
	public function getUserFriends($userID, $state = NULL ){
		
		$dbo 	= $this->_db; 
		
		//the user id
		if(empty($userID) || (int)$userID < 0 ){
			return array();
		}
		if(!empty($state)) {
			$state 	= "\nAND f.state = ".$dbo->Quote( (int) $state );
		}
		$userID = $dbo->Quote( (int)$userID );
		$query 	= "SELECT f.ID as relID, f.state, f.lastUpdated, p.sex, p.dob, p.externalIDs, u.name, u.username," 
		        . "u.id AS userID, u.lastVisitDate, u.email, f.thisUserID"
				. "\nFROM #__tuiyo_friends f"
				. "\nINNER JOIN #__users AS u "
				. "\nON (u.id = f.thatUserID AND f.thisUserID = {$userID}" 
				. "\nOR u.id = f.thisUserID AND f.thatUserID = {$userID} )"
				. "\nLEFT JOIN #__tuiyo_users AS p ON p.userID = u.id"
				. "\nWHERE (f.thisUserID = {$userID} OR f.thatUserID = {$userID} )"
				. $state;
				;
							
		$dbo->setQuery( $query );
		$rows 	= $dbo->loadObjectList();
		
		return (array)$rows;		
	}
	
    /**
     * TuiyoTableFriends::getInstance()
     * 
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
				TuiyoTableFriends::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableFriends( $db  )	;	
		}
		return $instance;	 
	 }	
	
}