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


class TuiyoTableChatRooms extends JTable{
	
	//`id` tinyint(4) NOT NULL AUTO_INCREMENT,
	var $id			= null;
	//`name` varchar(20) NOT NULL,
	var $name		= null;
	//`usercount` int(10) NOT NULL,
	var $usercount 	= null;
	//`datafile` varchar(30) NOT NULL,
	var $datafile	= null;
	//status 
	var $status 	= null;
	
    /**
     * TuiyoTableChatUsers::__construct()
     * 
     * @param mixed $db
     * @return
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_chat_rooms", "id", $db );
    }
	
	/**
	 * TuiyoTableChatRooms::loadIfExistsBetweenMembers()
	 * Loads a chat room if any exists between participants
	 * @param mixed $initiator
	 * @param mixed $member
	 * @return void
	 */
	public function loadIfExistsBetweenMembers($initiator, $member)
	{
		$dbo 	= $this->_db;
		$userA 	= $dbo->quote( (int)$initiator );
		$userB	= $dbo->quote( (int)$member );
		$query 	= "SELECT j.room FROM #__tuiyo_chat_users_rooms AS j".
				"\nLEFT JOIN #__tuiyo_chat_users_rooms AS s ON j.room = s.room".
				"\nWHERE j.userid = {$userA} AND s.userid = {$userB}"
				; 
		$dbo->setQuery( $query );		
		$roomID = $dbo->loadResult();
		
		if(!empty($roomID)){
			return (int)$roomID ;
		}
		return false;		
	}	
	
	/**
	 * TuiyoTableChatRooms::createRoomBetweenParticipants()
	 * Creates a new chat room
	 * @param mixed $initiator
	 * @param mixed $member
	 * @return void
	 */
	public function createRoomBetweenParticipants($initiator, $member)
	{	
		$initiator		= (int)$initiator ;
		$member			= (int)$member ;
		
		//Chat Room Users Table
		$curTable 	= TuiyoLoader::table('chatusersrooms', true );
		$user1Obj	= TuiyoAPI::get("user" , $initiator );
		$user2Obj	= TuiyoAPI::get("user" , $member );	
		
		//1st we have to create a room;
		if(empty($initiator) || empty($member)){
			JError::raiseError(TUIYO_SERVER_ERROR , _('Could not find particpants, imposible to create room'));
			return false;
		}

		$this->load( null );
		$this->name 	= $initiator.$member.date('YmdHis');
		$this->datafile	= $this->name.'.txt';
		$this->status	= 0 ;
		$this->usercount= 2;
		
		if(!$this->store()){
			JError::raiseError(TUIYO_SERVER_ERROR , $this->getError() );
			return false;
		}		
		
		//2nd Add Participants
		$curTable->load( null );
		
		$roomID 	   		= $this->id; 
				
		$userA 				= clone $curTable ;
		$userB				= clone $curTable ;
		
		$userA->username 	= $user1Obj->username ;
		$userA->userid		= $user1Obj->id ;
		$userA->room		= $roomID ;
		
		if(!$userA->store()){
			$this->delete();
			JError::raiseError( TUIYO_SERVER_ERROR , $userA->getError() );
			return false;
		}
		
		$userB->username 	= $user2Obj->username;
		$userB->userid		= $user2Obj->id ; 
		$userB->room		= $roomID ;
		
		if(!$userB->store()){
			$this->delete();
			$userA->delete();
			JError::raiseError( TUIYO_SERVER_ERROR , $userB->getError( ) );
			return false;
		} 
		
		unset($curTable);
		
		//3rd Create a data file; //joomla !!
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		
		$path 		= TUIYO_FILES.DS.'chat';
		$filename 	= $path.DS.$this->datafile ;
		
		if(!JFile::exists( $filename ) ){
			JFile::write( $filename , '' );
			if(!JFile::exists( $filename )){
				$this->delete();
				$userA->delete();
				$userB->delete();
				JError::raiseError( TUIYO_SERVER_ERROR , _('Could not create chat Log'));
			}
			//JPath::setPermissions( $filename , 0777 , 0777 );	
		}
		
		return $this ;			
	}

    /**
     * TuiyoTableChatUsers::getInstance()
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
                TuiyoTableChatRooms::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableChatRooms($db);
        }
        return $instance;
    }	
}