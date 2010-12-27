<?php
/**
 * ******************************************************************
 * TuiyoTableMessages Table  Class/Object for the Tuiyo platform                              *
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
 * TuiyoTableMessages
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableMessages extends JTable{
	
	//DROP TABLE IF EXISTS `joomla`.`jos_messages`;
	//CREATE TABLE  `joomla`.`jos_messages` (
	//  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	var $message_id 	= null;
	//  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
	var $user_id_from 	= null;
	//  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
	var $user_id_to		= null;
	//  `folder_id` int(10) unsigned NOT NULL DEFAULT '0',
	var $folder_id		= null;
	//  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	var $date_time		= null;
	//  `state` int(11) NOT NULL DEFAULT '0',
	var $state			= null;
	//  `priority` int(1) unsigned NOT NULL DEFAULT '0',
	var $priority		= null;
	//  `subject` text NOT NULL,
	var $subject		= null;
	//  `message` text NOT NULL,
	var $message	    = null;
	//  PRIMARY KEY (`message_id`),
	//  KEY `useridto_state` (`user_id_to`,`state`)
	//) ENGINE=MyISAM DEFAULT CHARSET=utf8;	
	
	/**
	 * TuiyoTableMessages::__construct()
	 * Constructs the messagesTable class
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db){
		parent::__construct("#__messages", "message_id", $db );
	}
	
	/**
	 * TuiyoTableMessages::getMessageList()
	 * Get All the messages in the Table 
	 * and group according to state
	 * @param mixed $userID
	 * @param mixed $state
	 * @return array
	 */
	public function getMessageList($userID, array $state)
	{
		$dbo 	= $this->_db;
		$query  = "SELECT m.*, f.name as user_from_name, f.username as user_from_username,"
				. "t.name as user_to_name, t.username as user_to_username, '' as user_from_avatar"
				. "\nFROM #__messages m"
				. "\nRIGHT JOIN #__users as f ON m.user_id_from=f.id"
				. "\nRIGHT JOIN #__users as t ON m.user_id_to=t.id"
				. "\nWHERE m.user_id_to=".$dbo->Quote( (int)$userID )
				. "\nOR m.user_id_from=".$dbo->Quote( (int)$userID )
				;
		$dbo->setQuery( $query );
		$rows 	= $dbo->loadObjectList();
		$list 	= array(
			"inbox" => array(),
			"sent"	=> array(),
			"drafts"=> array(),
			"trash" => array(),
		);
		//Sort The messages;
		foreach((array)$rows as $message):
			//How many words--crude!!;
			$message->word_count = count(explode(" ", trim($message->message) ));
			$message->date_time  = TuiyoTimer::diff( strtotime( $message->date_time));
			$message->user_to_avatar 	= TuiyoUser::getUserAvatar( $message->user_id_to, "thumb35");
			$message->user_from_avatar 	= TuiyoUser::getUserAvatar( $message->user_id_from, "thumb35");
			
			$folder 		 = $this->getMessageFolderFromCode( $message->folder_id , $message->user_id_from );
			$list[$folder][] = $message ;
			//If we sent the messsage, add to sent;
			//if($message->user_id_to <> (int)$userID ):
//				//Draft
//				if($message->message_id < 1 ):
//					$list["drafts"][] = $message;
//					continue;
//				endif;
//				//Trash Sender
//				if($message->foder_id >= 60 ):
//					$list["trash"][] = $message;
//					continue;
//				endif;
//				//Sent
//				$list["sent"][] = $message;
//				continue;
//			else:
//				//Trash Recipient
//				$sFid = (string)$message->folder_id ;
//				if(intval($sFid[strlen($sFid)-1]) === 6):
//					$list["trash"][] = $message;
//					continue;
//				endif;
//				//If we are recieving the message
//				
//				$list["inbox"][] = $message;
//				continue;
//			endif;
			
		endforeach;
		//print_R( $rows );
		return $list;	
	}
	
	public function getMessageFolderFromCode( $folder_id, $userID )
	{
		$user 	= TuiyoAPI::get("user" , NULL );
		
		//If we don't have a valid user Type
		if(is_null($folder_id) || is_null($userID)){
			JError::raiserError(TUIYO_SERVER_ERROR, "Invalid folder type provided" );
			return false;
		}
		//Determine if this user is the sender
		$isSender 	  	= ((int)$userID<> $user->id ) ? false : true ;
		$sFid 			= (string)$folder_id ;
		$recipientDir 	= (int)$sFid[1] ;
		$senderDir		= (int)$sFid[0] ;
		
		//Folder Maps
		$maps   = array( 
			"folders" =>	array( 
				0=>"inbox", 1=>"sent", 2=>"inbox" , 3=>"trash" 
			)
		);
		
		if($isSender){
			$folder = $maps["folders"][$senderDir] ;
		}else{ 
			$folder = $maps["folders"][$recipientDir] ;
		};
		
		return $folder;
	}
	
	public function setMessageFolderCode( $folder , $userID = NULL )
	{
		$user 	= TuiyoAPI::get("user" , NULL );

		//Determine if this user is the sender
		$isSender = ((int)$this->user_id_from <> $user->id ) ? FALSE : TRUE ;
		
		//If its a new message then folder ID is 10;
  		switch($folder){
  			case "sent":
  				$sFid 		= (string)$this->folder_id ;
  				if(strlen( $sFid) < 2){
  					$sFid .= "0";
  				}
  				if($isSender):
  					$folder_id  = (int)(  "1".$sFid[strlen($sFid)-1] );
  				else:
  					$folder_id  = (int)(  $sFid[0]."1" );
  				endif;
  			break;
  			case "read":
 				$sFid 		= (string)$this->folder_id ;
  				if(strlen( $sFid) < 2){
  					$sFid .= "0";
  				} 				
  				if($isSender):
  					$folder_id  = (int)(  "2".$sFid[strlen($sFid)-1] );
  				else:
  					$folder_id  = (int)(  $sFid[0]."2" );
  				endif;
  			break;
  			case "delete":
 				$sFid 		= (string)$this->folder_id ;
  				if(strlen( $sFid) < 2){
  					$sFid .= "0";
  				} 				
  				if($isSender):
  					$folder_id  = (int)(  "3".$sFid[strlen($sFid)-1] );
  				else:
  					$folder_id  = (int)(  $sFid[0]."3" );
  				endif;
  			break;
  			case "new" :
  			default :
  				$folder_id = "10";
  			break;
  		}
  		
  		$this->folder_id = (int)$folder_id ;
	}
	
	/**
	 * TuiyoTableMessages::getInstance()
	 * Gest an Instance of the Message Table for Tuiyo
	 * @param mixed $db
	 * @param bool $ifNotExits
	 * @return
	 */
	public function getInstance($db=null, $ifNotExits = true){
				/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoMessages::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoMessages( $db  )	;	
		}
		return $instance;
	}
}