<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform             *
 * 640 = ONLY ME ; 630 = FRIENDS ; 620 = REGISTERED ; 610 = ANYBODY *
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
 * TuiyoPrivacy
 * 
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoPrivacy{
	
	
	var $key 	= 	"user.privacy";
	
	/**
	 * TuiyoPrivacy::__construct()
	 * Class Constructor
	 * @return void
	 */
	public function __construct()
	{}
	
	/**
	 * TuiyoPrivacy::canRecieveNotifications()
	 * Checks if a user can recieve notifications 
	 * @param mixed $profileID
	 * @return boolean true or false
	 */
	public function canRecieveNotifications( $profileID = NULL )
	{ return true; }
	
	/**
	 * TuiyoPrivacy::canViewProfile()
	 * Verifies that $userID can view $profileID profile.
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean true if can view, or false if cant
	 */
	public function canViewProfile($profileID, $userID)
	{ 
		$params 	= $GLOBALS['API']->get("params", $this->key , $profileID);
		$params->loadParams( $this->key ,  $profileID );
		
		$permission = $this->defineUser($userID , $profileID);
		$userDef	= $params->get("viewProfile", 000 );				
		$minimum	= max($permission ) ;
		if( (int)$userDef <= (int)$minimum ):
			$return = true;
		else:
			$return = false;
		endif;
				
		return $return; 
	}
	
	/**
	 * TuiyoPrivacy::defineUser()
	 * 
	 * Function to define the relationship between to users
	 *   => is user a not a registered member? => 610
	 * 	 => is user a registered member => 620
	 *   => is user a friend to related user => 630
	 *   => is user the same as the related user (onlyMe) => 640
	 * 
	 * returns an array with with valid permissions
	 * 
	 * @param mixed $userID
	 * @param mixed $inRelationToUserID
	 * @return void
	 */
	private function defineUser( $userID , $inRelationToUserID ){
		
		$permissions = array();
		$userObj 	 = TuiyoAPI::get("user" , (int)$userID );
		
		//Is user a registered user?
		if($userObj->joomla->get('guest')):
			$permissions[]	= 610;
		else:
			$permissions[]	= 620;
		endif;
		//Is the user a friend of the user
		if($this->isFriendOf( $inRelationToUserID , $userID )) :
			$permissions[]	= 630;
		endif;
		//Is the user me?
		if((int)$userID === (int)$inRelationToUserID):
			$permissions[] 	= 630;
			$permissions[] 	= 640;
		endif;
		//Tell us what we have
		return $permissions; 		
	}
	
	/**
	 * TuiyoPrivacy::canViewGroup()
	 * Verifies that $userID can browse $groupID
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return boolean true or false
	 */
	public function canViewGroup($groupID, $userID = NULL)
	{ return true; }
	
	/**
	 * TuiyoPrivacy::canJoinGroup()
	 * Verifies that $userID can join $groupID
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return
	 */
	public function canJoinGroup($groupID, $userID = NULL )
	{ return true; }
	
	/**
	 * TuiyoPrivacy::canEditGroup()
	 * Verifies that $userID has group administrative permission 
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return boolean true or false
	 */
	public function canEditGroup($groupID, $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canViewExtendedProfile()
	 * Verifies that $userID can view the extended profile of $profileID
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean true or false
	 */
	public function canViewExtendedProfile($profileID, $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canViewProfileInformation()
	 * Verifies that $userID can view $profileID's social book'
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean true or false
	 */
	public function canViewProfileInformation($profileID, $userID = NULL)
	{}
	
	/**
	 * TuiyoPrivacy::canUserApplication()
	 * Verifies that a user has correct access level for an application
	 * @param mixed $appNameIdentifier
	 * @param mixed $userID
	 * @return boolean true or false
	 */
	public function canUseApplication($appNameIdentifier , $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canViewPhotoAlbums()
	 * Verifies that $userID can view $profileID photos
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canViewPhotoAlbums($profileID, $userID = NULL)
	{}
	
	/**
	 * TuiyoPrivacy::canCommentOnPhotos()
	 * Verifies $userID can add comments on $profileID photos
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canCommentOnPhotos($profileID, $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canViewContactInfo()
	 * Verifies that $userID, can view $profileID's contact
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canViewContactInfo($profileID, $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canContact()
	 * Verifies that $userID can contact $profileID
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canContact($profileID, $userID = NULL)
	{}
	
	/**
	 * TuiyoPrivacy::isFriendTo()
	 * Verifies that $userID is friends with $profileID
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	private function isFriendOf($profileID, $userID = NULL )
	{ 
		$model 		=& TuiyoLoader::model("friends", true );
		$user		=& TuiyoAPI::get("user", $userID );
		
		if(empty($profileID) && $user->joomla->get('guest')){			
			return false;
		}
		
		$profileID 	= !empty($profileID) ? (int)$profileID : JError::raiseError(404, _("Profile does not exists") );
		$userID 	= !empty($userID) 	 ? (int)$userID : $user->id ;
		
		if ( ($rel = $model->isFriendOf($profileID, $userID)) !== FALSE ){
			if((int)$rel->state < 1 && $rel->thisUserID <> $userID ){
				return false;
			}
			return true;
		}
		
		//Are not friends
		return false;
	}
	
	/**
	 * TuiyoPrivacy::isExecutive()
	 * Determines if the userID supplied has special Rights
	 * @param mixed $userID
	 * @return void
	 */
	private function isSpeicalUser($userID)
	{}
	
	/**
	 * TuiyoPrivacy::getPrivacySettings()
	 * Gets the privacy settings of $profileID
	 * @param mixed $profileID
	 * @return mixed object
	 */
	private function getPrivacySettings( $profileID )
	{
		$tParams 	=& TuiyoAPI::get("params");
		$profileID 	= (int)$profileID;
		$paramKey  	= "user.privacy";
		
		$tParams->loadParams($paramKey , $profileID );
		
		//store params
		if($this instanceof self ) $this->params = $tParams ;
		
		//Return object
		return (object)$tParams ;
	}
	
	
	/**
	 * TuiyoPrivacy::canViewProfileFriends()
	 * Verifies that user can view profile friends
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canViewProfileFriends( $profileID, $userID = NULL )
	{}
	
	/**
	 * TuiyoPrivacy::canAddAsFriends()
	 * Verifies that user can add as friends
	 * Anybody who is not friends with user and is not user himself
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @return boolean
	 */
	public function canAddAsFriends( $profileID, $userID = NULL  )
	{ 
		$user		=& TuiyoAPI::get("user", null );
		
		if(empty($profileID) && $user->joomla->get('guest')){
			
			$mainframe 	=& $GLOBALS['mainframe'];
			$welcome 	= JRoute::_(TUIYO_INDEX.'&amp;redirect=welcome&amp;do=login', false, null);
			
            $mainframe->redirect( $welcome , _("You must be logged in to proceed"), "notice");
		}		
		
		$profileID 	= !empty($profileID) ? (int)$profileID : JError::raiseError(404, _("Profile does not exists") );
		$userID 	= !empty($userID) 	 ? (int)$userID : $user->id ;
		
		//Check that userID is not view own profile
		if((int)$profileID === (int)$userID ){ 
			return false;
		}
		//Next check whether userID is already friend of profileID
		//If is friend return false;
		if($this instanceof self) :
			$self = $this; 
		else :
			$self = self::getInstance();
		endif; 
		
		//Check are friends	
		if($self->isFriendOf($profileID , $userID)) return false;
		
		return true; 
	}
	
	/**
	 * TuiyoPrivacy::isMemberOfGroup()
	 * Verifies if $userID is a member of $groupID
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return boolean
	 */
	private function isMemberOfGroup($groupID, $userID = NULL )
	{}
	
	
	/**
	 * TuiyoPrivacy::canRateUser()
	 * Checks if userID can rate profileID
	 * @param mixed $profileID
	 * @param mixed $userID
	 * @param mixed $IP
	 * @return true if can rate or false if can't
	 */
	public function canRateUser($profileID, $userID = NULL, $IP = NULL){
		
		//joomla !!
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		
		$user 		= &TuiyoAPI::get("user", $userID );
		$profile 	= &TuiyoAPI::get("user", (int)$profileID);

		//**If guest || if same user
		if($user->joomla->get("guest") || (int)$profile->id === (int)$user->id ){
			return false;
		}

		$IP   	= (is_null($IP)||empty($IP))? getenv('REMOTE_ADDR') : $IP;
		$IPfile	= TUIYO_FILES.DS."logs".DS.strval($profile->id).DS."rating.log";
		
		if(!JFile::exists($IPfile)){
			JFile::write($IPfile, "#Tuiyo Rating log; Profile:".$profile->id."\n\n" );
			return TRUE;
		}
		$rateLog 	= TuiyoAPI::parseINI( $IPfile );
		$rateLogKey = $user->id."@".$IP ;
		
		if( array_key_exists( $rateLogKey, $rateLog ) ){
			return false;
		} 
		//Yes user can vote
		return TRUE;
	}
	
	/**
	 * TuiyoPrivacy::hasAccessLevelOf()
	 * Gets the system joomla $userID access group 
	 * @param mixed $userID
	 * @return string
	 */
	private function hasAccessLevelOf( $userID )
	{ /** Gets a users access Level **/ }
	
 	/**
 	 * TuiyoPrivacy::getInstance()
 	 * Returns an instance of TuiyoPrivacy object is non exist
 	 * @param bool $ifNotExist
 	 * @return object
 	 */
 	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoPrivacy()	;	
		}
		return $instance;	
  	}		
}