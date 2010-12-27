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
 * TuiyoTableChatUsersRooms
 * 
 * @package Joomla
 * @author stoney
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class TuiyoTableChatUsersRooms extends JTable{

	//`id` int(100) NOT NULL AUTO_INCREMENT,
	var $id 		= null;
	//`username` varchar(100) NOT NULL,
	var $username 	= null;
	//`room` varchar(100) NOT NULL,
	var $userid		= null;
	
	var $room 		= null;
	//`lastupdated` int(40) NOT NULL,
	var $lastupdated = null;
    /**
     * TuiyoTableChatUsersRooms::__construct()
     * 
     * @param mixed $db
     * @return
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_chat_users_rooms", "id", $db );
    }	
    
    /**
     * TuiyoTableChatUsersRooms::hasMember()
     * Checks that the specified room has the specified member id
     * @param mixed $userid
     * @param mixed $roomid
     * @return boolean
     */
    public function hasMember( $userid, $roomid )
	{
		$dbo 	= $this->_db ;
		$query 	= "SELECT COUNT(1) FROM #__tuiyo_chat_users_rooms AS cr".
				  "\nWHERE cr.userid=".$dbo->quote( (int)$userid ).
				  "\nAND cr.room=".$dbo->quote( (int)$roomid )
				  ;
  		$dbo->setQuery( $query );
  		$result = $dbo->loadResult();
  		
  		if(!empty($result) && (int)$result > 0) {return true ; }
  		
  		return false;				 
	}

    /**
     * TuiyoTableChatUsersRooms::getInstance()
     * 
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
                TuiyoTableChatUsersRooms::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableChatUsersRooms($db);
        }
        return $instance;
    }	
}
	