<?php
/**
 * ******************************************************************
 * Messages model Class/Object for the Tuiyo platform               *
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
 * TuiyoModelMessages
 * @package tuiyo
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelMessages extends JModel{
	
	/**
	 * TuiyoModelMessages::getMessages()
	 * Gets User Messages,
	 * @param mixed $userID
	 * @param mixed $states
	 * @return array
	 */
	public function getMessages($userID, $states = null)
	{	
		//1. Pre-requisites
		$mTable 	= TuiyoLoader::table('messages', true );
		$mStates	= array("unread", "read");
		
		//2. Get the message List
		$mList 		= $mTable->getMessageList( (int)$userID , $mStates);
		
		//3. Trigger Message event, get message from other sources

		
		//4. Return messages
		return (array)$mList;
	}
	
	/**
	 * TuiyoModelMessages::initiateChatRoom()
	 * Initiates a chatRoom. creating a new one if non existent
	 * @param mixed $initiateor
	 * @param mixed $participant
	 * @return void
	 */
	public function initiateChatRoom( $initiator, $participant )
	{
		$crTable 	= TuiyoLoader::table('chatrooms', true );
		$curTable 	= TuiyoLoader::table('chatusersrooms', true );	
		
		//1. Create/LOAD A room;
		$existing 	= $crTable->loadIfExistsBetweenMembers($initiator , $participant);
		if((int)$existing > 0){
			$crTable->load( (int)$existing );			
		}else{
			$crTable->createRoomBetweenParticipants( $initiator , $participant );
		}
		//2.Check that the room is loaded or eles, Kill!
		if(empty($crTable->id) || empty($crTable->datafile)){
			JError::raiseError( TUIYO_SERVER_ERROR, _('Could not initate chat room') ) ;
			return false;
		}
		//3.Create or Parse the data file if non existent
		//4.Return a chatroom objects
		return array(
			"roomID" 	=> $crTable->id,
			"datafile"	=> $crTable->datafile,
			"member"	=> (int)$participant, 
			"initiator"	=> (int)$initiator ,
			"status"	=> $crTable->status 
		);		
	}
	
	/**
	 * TuiyoModelMessages::storeChatMessage()
	 * Adds the userPostMessage
	 * @param mixed $data
	 * @param mixed $user
	 * @return
	 */
	public function storeChatMessage( $data , $user)
	{
		$crTable 	= TuiyoLoader::table('chatrooms', true );
		$curTable 	= TuiyoLoader::table('chatusersrooms', true );
		$document 	= TuiyoAPI::get("document");	
		
		if(!$crTable->load( $data['chatRoom']) || !$curTable->hasMember( $user->id , $crTable->id ) ){
			JError::raiseError(TUIYO_SERVER_ERROR , _('Could not Load the chat Room. Message cannot be posted'));
			return false;			
		}
		
		$message 		= htmlentities(strip_tags( trim( $data['chatPostMessage'] ) ) );
		
		$tmplPath 		= TUIYO_VIEWS.DS."messages".DS."tmpl" ;
		
		$tmplVars 		= array(
			"postAuthor"=>	$user->username,
			"postBody"	=>	$message,
			"postTime"	=> 	date( 'Y-m-d H:i:s')
		);
		
		$content 	    = $document->parseTmpl("chatroompost" , $tmplPath , $tmplVars);	
		
		$datafile 		= TUIYO_FILES.DS.'chat'.DS.$crTable->datafile;
	 	$regxUrl 		= "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	  
	 	
	 	
		 if(($message)  != "\n"): 
				//Parse URLS and usernames
			 	if(preg_match($regxUrl, $message, $url)):
	       			$message = preg_replace($regxUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
				endif; 		
				//Write the file
				 $chatLog 		= fopen( $datafile, 'a');
				 $chatPostEnc	= base64_encode( $content );//$this->encryptToggleDecrypt( $content );
				 				         	
	 	 		 fwrite( $chatLog , "\n".$chatPostEnc); 
 	 		 //echo $chatPostEnc;
 	 		 
		 endif;		
		 
		 $crTable->status++; 
		 
		 if(!$crTable->store()){
		 	JError::raiseError(TUIYO_SERVER_ERROR, $crTable->getError());
		 	return false;
		 }	
		
		return true;
	} 
	
	/**
	 * TuiyoModelMessages::getChatMessages()
	 * Gets the last updated chat message from the room
	 * @param mixed $data
	 * @param mixed $user
	 * @return
	 */
	public function getChatMessages( $data , $user )
	{
		$crTable 	= TuiyoLoader::table('chatrooms', true );
		$curTable 	= TuiyoLoader::table('chatusersrooms', true );
		$document 	= TuiyoAPI::get("document");
		
		if(!$crTable->load( $data['chatRoom']) || !$curTable->hasMember( $user->id , $crTable->id ) ){
			JError::raiseError(TUIYO_SERVER_ERROR , _('Could not Load the chat Room. Message cannot be posted'));
			return false;			
		}
		
		$datafile 		= TUIYO_FILES.DS.'chat'.DS.$crTable->datafile;
		$lines			= null;
		$message		= null; 
		
		if(file_exists( $datafile) ):
		
			$lines 		= file( $datafile );
			$count		= count( $lines );
			$state		= (int)$data['status'];
			
			//If we have no updated message
  			 $text		= null;
  			 if($state+1  >= $count){
  			 	$message= $text;
  			 }else{
				 foreach ($lines as $n => $line)
	               {
					   if($n > (int)$state ){
	                 		$text .= str_replace("\n", "", base64_decode( $line ) );
					   } 
	 				}
				 $message 	= $text; 								
			}
		endif; 		
		
		return array( 
			"html" 		=> $message,
			"status"	=> $crTable->status
		);
					
	}
	
	/**
	 * TuiyoModelMessages::encryptToggleDecrypt()
	 * Encrypts or decrypts a string accordingly
	 * @param mixed $string
	 * @return
	 */
	private function encryptToggleDecrypt($string) 
	{ 
	    $stringLen		=	strlen($string); 
	    $stringEncrypted=	""; 
	    
	    for($p = 0; $p<$stringLen; $p++){ 
	        
			$salt 		= (($stringLen+$p)+1); 
	        $salt 		= (255+$salt) % 255; 
	        
	        $byteToEncrypt 		= substr($string, $p, 1); 
	        $asciiByteToEncrypt = ord($byteToEncrypt); 
	        $xoredByte 			= $asciiByteToEncrypt ^ $salt;  //xor operation 
	        $encryptedByte 		= chr($xoredByte );
			 
	        $stringEncrypted 	.= $encryptedByte; 
	         
	    } 
	    return $stringEncrypted; 
	} 
	
	/**
	 * TuiyoModelMessages::deleteMessage()
	 * Removes a message from the TuiyoTable
	 * @param mixed $messageId
	 * @param mixed $userID
	 * @return void
	 */
	public function deleteMessage($messageId, $userID)
	{
		$mTable 	= TuiyoLoader::table('messages', true );
		
		//Load the message!
		if(!$mTable->load((int)$messageId)){
			JError::raiseError(TUIYO_SERVER_ERROR, $mTable->getError());
			return false;
		}
		$isSender	= ($mTable->user_id_to <> (int)$userID)? true : false ;
		$sFid 		= (string)$mTable->folder_id ;
		
		if($mTable->folder_id >= 34 ){
			if(!$mTable->delete()){
				JError::raiseError(TUIYO_SERVER_ERROR, $mTable->getError());
				return false;
			} return true; //We are done!
		}else{
			$mTable->setMessageFolderCode( "delete" );
			$mTable->store();
			return true; //We are done!
		}
		return false;
	}
	
	/**
	 * TuiyoModelMessages::getNotifications()
	 * Method to get all existing notifications
	 * @param mixed $userID
	 * @param mixed $group
	 * @return void
	 */
	public function getNotifications($userID, $group = NULL)
	{}
	
	/**
	 * TuiyoModelMessages::getAddresses()
	 * Returns contacts in users Address Book!
	 * @param mixed $userID
	 * @param mixed $group
	 * @return void
	 */
	public function getAddresses($userID, $group=NULL)
	{}
	
	/**
	 * TuiyoModelMessages::addMessage()
	 * Adds a message to the user table;
	 * @param mixed $userID
	 * @param mixed $postData
	 * @param integer $status
	 * @return boolean true if successful
	 */
	public function addMessage($thisUserID, $post, $status = 0)
	{		
		$pmsTable	= TuiyoLoader::table('messages', true );
		$uTable 	= TuiyoLoader::table('users', true );
		$document 	= TuiyoAPI::get('document');
		
		//1. Get Users ID
		$recipients	= $post["sendTo"] ;
		$subject 	= strip_tags( trim($post["messageSubject"]));
		$message 	= strip_tags( trim($post["newMessageText"]) );
		$datetime	= date('Y-m-d H:i:s');
		$status 	= (!empty($status)) ? (int)$status : 0 ;
		
		//NO Empty Messages
		$message 	= (!empty($message)) ? $message : "{tuiyo:undefined-messages}" ;
		
		//Get the recipients
		//$recipients = explode( "," , $sendTo );
		$count		= sizeof( $recipients  );
		$failed		= array();
		
		//print_R($recipients);
		
		//die;
		
		//2. Send the Message to Every User
		foreach($recipients as $i=>$thatUserID):
		
			$thatUserID	= (int)	$thatUserID ;
			$mTable  	= clone $pmsTable  ;
			//Get the UserID from the userTable
			//$thatUserID 			= $uTable->getUserID( trim( (string)$recipient ) );
			//Check we have a valid ID and no empty message;
			if(empty($thatUserID) || (int)$thatUserID < 1 || !$uTable->userProfileExists( (int)$thisUserID )) {
				$failed[] = $thatUserID ;
				$document->enqueMessage(  sprintf( _("Invalid user with ID: %s")  , $thatUserID ) , "error");
				continue;
				//return false;
			};
			
			$mTable->load( null );
			$mTable->user_id_from 	= (int)$thisUserID;
			$mTable->user_id_to		= (int)$thatUserID;
			$mTable->subject 		= !empty( $subject ) ? $subject : "{tuiyo:undefined-subject}";
			$mTable->date_time 		= $datetime ;
			$mTable->message		= $message;
			$mTable->state			= $status ;
			$mTable->folder_id		= 10;
			//die;
			
			if(!$mTable->store()):
				//Oops something bad happened
				JError::raiseError(TUIYO_SERVER_ERROR, $table->getError());
				return false;
			endif;
			
			//destroy $mTable
			if((int)$mTable->user_id_to === (int)$mTable->user_id_from ){
				$document->enqueMessage( _("Messages to self do not show in inbox but in sent folder" ), "notice");
			}		
			
			//Notify Recepient
			TuiyoLoader::library("mail.notify");
			
			if((int)$mTable->user_id_to > 0 && (int)$mTable->user_id_to <> $mTable->user_id_from ):
				$userTo 	= TuiyoAPI::get("user", $mTable->user_id_to );
				$userFrom   = TuiyoAPI::get("user", $mTable->user_id_from );
				$actionLink = JRoute::_(TUIYO_INDEX."&view=messages");

				
		        $emailTitle = sprintf(_("%s sent you a new private message"), "@".$userFrom->username );
				$emailBody 	= "";//str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
	   			
				//echo $notifyEmail ;
				TuiyoNotify::_( $userTo->id, $emailTitle , $actionLink , _("View Messages") );			
			endif;
				
			
			$mTable->message_id = null;
			unset($mTable);
		endforeach;
		
		//Retur true
		return true;
	}
	
	/**
	 * TuiyoModelMessages::setMessageStatus()
	 * Sets the status of the message
	 * @param mixed $messageID
	 * @param mixed $satusID
	 * @param mixed $userID
	 * @return void
	 */
	public function setMessageStatus($messageID, $statusID, $userID)
	{
		$mTable	= TuiyoLoader::table('messages', true );
		
		//1.load the message
		if(!$mTable->load( (int)$messageID)){
			JError::raiseError(TTUIYO_SERVER_ERROR, _("Message could not be loaded") );
			return false;
		}
		//2. Check permisisons
		if((int)$userID <>( $mTable->user_id_to && $mTable->user_id_from)){
			JError::raiseError(TUIYO_SERVER_ERROR, _("You do not have permission to change this message status" ) );
			return false;
		}
		//Change the state
		$mTable->state = !empty($statusID)? (int)$statusID : $mTable->state ;
		$mTable->setMessageFolderCode( ( (int)$mTable->state > 0 )? "read" : "new" );
		
		//3. Store the update
		if(!$mTable->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, _("Could not update the table") );
			return false;
		}
		//Good JOB!.
		return true;
	}
}