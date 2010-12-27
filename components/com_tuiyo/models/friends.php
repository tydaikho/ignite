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
jimport( 'joomla.application.component.model' );

/**
 * TuiyoModelFriends
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelFriends extends JModel{
	
	/**
	 * TuiyoModelFriends::getLists()
	 * Returns a list of users related to profileID
	 * @param 	mixed $profileID
	 * @param 	string $type 2 for mutual friends 1 for pending, 0 for all friends
	 * @param 	bool $extended include just username and names or include avatars
	 * @return 	mixed array list
	 */
	public function getFriendLists($profileID, $userID = NULL, $state = NULL, $genIDs = false)
	{
		$tble 	= TuiyoLoader::table("friends");
		$user	= TuiyoAPI::get( "user" );
		
		$user1 	= !empty($userID) ? (int)$userID : TuiyoAPI::get( "user" )->id ;
		$user2	= !empty($profileID) ? (int)$profileID : $user1;
		
		$list 	= $tble->getUserFriends( $user2 , $state );
		$ids	= array();
		
		//add Avatar object
		foreach((array)$list as $friend ): 
			if($genIDs){
				$ids[] = (int)$friend->userID ;	
				continue;
			}
			$friend->avatar = &TuiyoUser::getUserAvatar( $friend->userID );
		endforeach;
		
		//if we need only IDS
		if($genIDs){
			return $ids ;
		}
	
		
		return $list;
	}
	
	
	/**
	 * TuiyoModelFriends::generateIDs()
	 * Generates a simple array of friends userIDs for comparison;
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @param mixed $state
	 * @return
	 */
	public function generateIDs($profileID, $userID = NULL, $state= NULL){
		
		return (array)$this->getFriendLists($profileID, $userID, $state, TRUE );
		
	}
	
	/**
	 * TuiyoModelFriends::removeFriend()
	 * Removes a friend from your friend lists
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return
	 */
	public function removeFriend($profileID, $userID=NULL)
	{
		$tble 	= TuiyoLoader::table("friends");
		
		//user1 and user2
		$user1 	= !empty($userID) ? (int)$userID : TuiyoAPI::get( "user" )->id ;
		$user2	= !empty($profileID) ? (int)$profileID : JError::raiseError(500, _('Invalid Profile ID') );
		$user2P = TuiyoUser::getInstance( $user2 );
		
		if(($rel = $this->isFriendOf( $user1, $user2 ) ) !== FALSE)
		{	 
			if(!$rel->delete() ){
				JError::raiseError(500, $rel->getErrorMsg());
				return false;
			}
			return sprintf(_("%s has been removed from your friends list"), $user2P->name  );
		}
		JError::raiseError(500, sprintf(_("There was a problem removing %s from your friends list") , $user2P->name ) );
	}
	
	/**
	 * TuiyoModelFriends::addFriend()
	 * Adds a user as friend;
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return
	 */
	public function addFriend($profileID, $userID = NULL)
	{
		$tble 	= TuiyoLoader::table("friends");
        //Send and Email to the user with the activation code;
        TuiyoLoader::helper("parameter");
        TuiyoLoader::library("mail.notify");
	      
        $notifyParams = TuiyoParameter::load( "emails");
        		
		//user1 and user2
		$user1 	= !empty($userID) ? (int)$userID : TuiyoAPI::get( "user" )->id ;
		$user2	= !empty($profileID) ? (int)$profileID : JError::raiseError(500, _('Invalid Profile ID') );
		
		//Verify that friend does not already exists!
		if(($rel = $this->isFriendOf( $user1, $user2 ) ) !== FALSE)
		{
			if( ((int)$rel->thisUserID <> (int)$user1 ) && (int)$rel->state < 1 )
			{	
				$rel->state			= 	(int)$rel->state + 1 ;
				$rel->lastUpdated 	=	date('Y-m-d H:i:s');
				$rel->user1			= 	( (int)$rel->thisUserID < (int)$rel->thatUserID ) ? (int)$rel->thisUserID : $rel->thatUserID ;
				$rel->user2			=   ( (int)$rel->thisUserID > (int)$rel->thatUserID ) ? (int)$rel->thisUserID : $rel->thatUserID ;
				
				$relProfileLink 	= 	TUIYO_INDEX.'&view=profile&pid='.$rel->user1 ;
				$relUser1Object 	=	TuiyoAPI::get("user", (int)$rel->user1 );
				$relUser2Object 	=	TuiyoAPI::get("user", (int)$rel->user2 );
				 
				if(!$rel->store() ){
					JError::raiseError(500, $rel->getErrorMsg());
					return false;
				}				
				//echo $notifyEmail ;
				TuiyoNotify::_( $rel->user2, sprintf(_("%s confirmed you as friend"), "@".$relUser1Object->username ), $relProfileLink , _("View profile") );
							//4b. Publish Activity Story;
				$uActivity 	= TuiyoAPI::get("activity", null );
	
				$uStoryLine = sprintf( _('%1s is now friends with %2s'), "@".$relUser1Object->username , "@".$relUser2Object->username  );
				$uActivity->publishOneLineStory( $relUser1Object, $uStoryLine , "friends");
				
				$uStoryLine2 = sprintf( _('%1s is now friends with %2s'), "@".$relUser2Object->username , "@".$relUser1Object->username  );
				$uActivity->publishOneLineStory( $relUser2Object, $uStoryLine2 , "friends");				
									
				return $rel->state;
			}
			return false;
		}else{
			$tble->load( null );
			$tble->type 		=	"friends";
			$tble->thisUserID	= 	(int)$user1 ;
			$tble->thatUserID	= 	(int)$user2 ;
			$tble->user1		= 	( (int)$user1 < (int)$user2 ) ? (int)$user1 : $user2 ;
			$tble->user2		=   ( (int)$user1 > (int)$user2 ) ? (int)$user1 : $user2 ;
			$tble->state		= 	(int)0;
			$tble->listID		= 	 0 ;
			
			if(!$tble->store() ){
				JError::raiseError(500, $tble->getErrorMsg());
				return $tble->state;
			}
			$relUser1Object 	=	TuiyoAPI::get("user", (int)$tble->user1 );
			$relUser2Object 	=	TuiyoAPI::get("user", (int)$tble->user2 );
			$relProfileLink 	= 	TUIYO_INDEX.'&view=profile&pid='.$tble->user1;
			
      		$tVars 		= array( $relUser2Object->name, JURI::base() ,$relUser1Object->name, "" );
        	$tSearch 	= array("[thisuser]", "[link]", "[thatuser]", "[message]") ;			
			
	        $emailTitle = str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailTitle" , null ) );
			$emailBody 	= str_replace( $tSearch , $tVars , $notifyParams->get( "connectionRequestEmailBody" , null ) );
   			
			//echo $notifyEmail ;
			TuiyoNotify::_( $tble->user1, sprintf(_("%s has requested to add you as friend"), "@".$relUser2Object->username ), $relProfileLink , _("View profile") );			
			TuiyoNotify::sendMail( $relUser1Object->email, $emailTitle, $emailBody );	
					
			return $tble->state	;		
		}
		//We should not get to this point
		return false;
	}
	
	/**
	 * TuiyoModelFriends::addInvite()
	 * Generates an Invite
	 * @param mixed $inviter
	 * @param mixed $invitee
	 * @return
	 */
	public function addInvite($inviter, $invitee, $message = NULL)
	{
		$iTable 	= TuiyoLoader::table("invites", TRUE );
		$iNotify 	= TuiyoLoader::library("mail.notify", true );
		
		if(empty($invitee->email) || !isset($invitee->email) || $iTable->hasExistingAccount( $invitee->email ) ):
			return false;
		endif;
		
		$iTable->load( NULL );
		$iData  = array(
			"userid"	=> $inviter->id,
			"email"		=> $invitee->email,
			"state"		=> 0,
			"name"		=> !empty($invitee->name)? $invitee->name : $invitee->email,
			"code"		=> $iTable->generateCode()	
        );
        
        if(!$iTable->bind( $iData )){
        	JError::raiseError(TUIYO_SERVER_ERROR, $iTable->getError());
        	return false;
        }
		
		//We can Now save the table
  		if(!$iTable->store( $iData )){
        	return false;
        }
        //Send and Email to the user with the activation code;
        TuiyoLoader::helper("parameter");
        TuiyoLoader::library("mail.notify");
	      
        $notifyParams = TuiyoParameter::load( "emails");

        $tVars 		= array( $invitee->name, JURI::base() ,$inviter->username,$message );
        $tSearch 	= array("[name]", "[link]", "[username]", "[message]") ;
        
        $emailTitle = str_replace( $tSearch , $tVars , $notifyParams->get( "inviteEmailTitle" , null ) );
		$emailBody 	= str_replace( $tSearch , $tVars , $notifyParams->get( "inviteEmailBody" , null ) );
		
		//echo $notifyEmail ;
		TuiyoNotify::sendMail( $invitee->email, $emailTitle, $emailBody );
		        
        return true;
	}
	
	/**
	 * TuiyoModelFriends::generateInviteHistory()
	 * @param mixed $inviter
	 * @return
	 */
	public function generateInviteHistory( $inviter )
	{
		$iTable 	= TuiyoLoader::table("invites", TRUE );
		$user 		= TuiyoAPI::get("user" , NULL );
		
		$inviter	= !empty($inviter ) ? (int)$inviter : $user->id;
		$invites 	= $iTable->findAllMyInvites( $inviter );
		
		return (array)$invites ;
	}
	
	/**
	 * TuiyoModelFriends::deleteInvite()
	 * @param mixed $inviteID
	 * @return void
	 */
	public function deleteInvite( $inviteID )
	{
		$iTable 	= TuiyoLoader::table("invites", TRUE );
		$user 		= TuiyoAPI::get("user" , NULL );
		
		if(empty($inviteID) || !$iTable->load( (int)$inviteID)){
			JError::raiseError( TUIYO_SERVER_ERROR, _("Could not find the specified Invite") );
			return false;
		}
		//Check Permission
		if($iTable->userid <> $user->id ){
			JError::raiseError( TUIYO_SERVER_ERROR,_( "You do not have permission to delete the invite") );
			return false;
		}
		//Delete the invite
		if(!$iTable->delete() ){
			JError::raiseError( TUIYO_SERVER_ERROR, $iTable->getError());
			return false;
		}
		return TRUE;	
	}
	
	
	/**
	 * TuiyoModelFriends::isFriendOf()
	 * Checks if two users are related
	 * @param mixed $userOneID
	 * @param mixed $userTwoID
	 * @return return relID if exists and false if notexists
	 */
	public function isFriendOf($userOneID, $userTwoID ){
		
		$tble 	= TuiyoLoader::table("friends");
		
		//Checks that on the user table;
		$rel 	= $tble->checkRelationship( (int)$userOneID  , (int)$userTwoID );
		
		if(intval($rel) > 0){
			$tble 	= TuiyoLoader::table("friends");
			$tble->load( (int)$rel );
			
			return (object)$tble;
		}
		return false;
	}
	
}