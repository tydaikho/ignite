<?php
/**
 * ******************************************************************
 * User object for the Tuiyo platform                               *
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
 
 defined('TUIYO_EXECUTE') || die;
 
 /**
  * TuiyoUser
  * 
  * @package tuiyo
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoUser{
 	
 	/**
	 * error Handle
	 */
 	private $_userID  = NULL;
 	
 	/**
	 * error Handle
	 */
 	private $_errors  = Array();
 	
 	/**
 	 * Loads user profile;
 	 */
 	private $_profileLoaded = FALSE;
 	
	 /**
	 * Unique id
	 */
	var $id				= null;

	/**
	 * The users real name 
	 */
	var $name			= "Guest User";

	/**
	 * The login name (or nickname)
	 */
	var $username		= "Guest";

	/**
	 * The email
	 */
	var $email			= null;

	/**
	 * MD5 encrypted password
	 */
	private $_password	= null;
	/**
	 * Description
	 */
	var $usertype		= null;

	/**
	 * Description
	 */
	var $block			= null;

	/**
	 * Description
	 */
	var $sendEmail		= null;

	/**
	 * The group id number
	 */
	var $gid			= null;

	/**
	 * Description
	 * @var datetime
	 */
	var $registerDate	= null;

	/**
	 * Description
	 */
	var $lastvisitDate	= null;

	/**
	 * Description
	 */
	var $activation		= null;

	/**
	 * Description
	 */
	var $params			= null;

	/**
	 * Description
	 */
	var $aid 			= null;

	/**
	 * Description
	 */
	var $guest     		= null;

	/**
	 * User parameters
	 */
	private $_params 	= null;

	/**
	 * Stores the users profile
	 */
	private $_profile 	= null;


 	/**
 	 * TuiyoUser::TuiyoUser()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function __construct($userID = NULL)
 	{
 		$this->_bind( JFactory::getUser( $userID ) );	
 		$this->_getUserData( $userID );
 	}

 	
 	/**
 	 * TuiyoUser::getUserSession()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function getUserSession($userID = NULL)
 	{  	
		$self = (!is_a($this , 'TuiyoUser')) ? TuiyoUser::getInstance() : $this ;
 	}
 	
 	/**
 	 * TuiyoUser::getUserActivity()
 	 * Returns the user activity object
 	 * 
 	 * @param mixed $userID
 	 * @return bool FALSE on error / Activity Object on Success
 	 */
 	public function getUserActivity($userID = NULL)
 	{ 	/*Returns the acitivity Object for the current User*/
 		$self = (!is_a($this , 'TuiyoUser')) ? TuiyoUser::getInstance() : $this ;
	}
 	
 	/**
 	 * TuiyoUser::getUserContact()
 	 * Returns an object with the users contact data
 	 * 
 	 * @param mixed $userID
 	 * @return object
 	 */
 	public function getUserContact($userID = null)
    { 	
    	static $instance  = array();
    	
    	$self 	 	= (!is_a($this , 'TuiyoUser')) ? TuiyoUser::getInstance() : $this ;
    	$contact	= new stdClass;
    	$userID		= (empty($userID)) ? $self->id : $userID ;
   		/**Returns an object with the current users */ 
    	if(isset($instance[$userID]) && is_object($instance[$userID]) ){
    		return (object)$instance[$userID];
    	}
		$params 	= TuiyoApi::get("params");
		$params->loadParams("user.contact" , $userID );
		
		$contact->company 		= $params->get("company", null );
		$contact->region		= $params->get("region" , null );
		$contact->description 	= $params->get("description" , null );
		$contact->town 			= $params->get("town" , null );
		$contact->postcode 		= $params->get("postcode", null );
		$contact->email			= $params->get("email", $self->email );
		$contact->phone			= $params->get("phone", null );
		$contact->street		= $params->get("street" );
		$contact->website		= $params->get("website");  
		$contact->skypeID		= $params->get("skypeID"); 
		$contact->yahooID 		= $params->get("yahooID"); 
		$contact->msnID			= $params->get("msnID"); 
		$contact->gTalkID		= $params->get("gTalkID"); 
		$contact->website		= $params->get("aol"); 
		
		//free some memory
		$params 			= null;
		$instance[$userID] = $contact ;
		
		unset  ($params );
		return (object) $instance[$userID];
  	}
  	
 	/**
 	 * TuiyoUser::getUserAvatar()
 	 * @param mixed $userID
 	 * @return object
 	 */
 	public function getUserAvatar($userID = NULL, $type = NULL)
	 {	
    	$avatar		=  new userAvatar();
		$self 	 	=  TuiyoUser::getInstance( (empty($userID)) ? null : (int)$userID ) ;
    	$userID		= (empty($userID)) ? $self->id : (int)$userID ;
    	
   		/**Returns an object with the current users */ 
 		$params 			= TuiyoApi::get("params");
 		
		$params->loadParams("user.avatar" , $userID );
		
		$thumb200 	= $params->get("thumb200" , null );
		$thumb70  	= $params->get("thumb70", 	null);
		$thumb35  	= $params->get("thumb35" , 	null );

		//Paths 
		$pathThumb200 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  , substr($thumb200, 1 ) );
		$pathThumb70 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  , substr($thumb70, 1 ) );
		$pathThumb35 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  , substr($thumb35, 1 ) );
		$pathRoot 			=  JURI::root();
		
		//No avatr Urls
		$noAvatarThumb200URL=  "components/com_tuiyo/files/noavatar200.jpg";
		$noAvatarThumb70URL =  "components/com_tuiyo/files/noavatar70.jpg";
		$noAvatarThumb35URL =  "components/com_tuiyo/files/noavatar35.jpg";		
		$gravatarURL 		=  "http://www.gravatar.com/avatar.php?gravatar_id=%s&default=%s&size=%s&border=%s&rating=%s";
		$userEmail 			= 	$self->joomla->get('email');
		
		//Finally Tell us What the avatars truley are
		$avatar->thumb200 	= (!empty($thumb200) && file_exists($pathThumb200) ) ? $pathRoot.substr($thumb200, 1 ) : $pathRoot.$noAvatarThumb200URL ;
		$avatar->thumb70 	= (!empty($thumb70) && file_exists($pathThumb70) ) ? $pathRoot.substr($thumb70, 1 ) : $pathRoot.$noAvatarThumb70URL ;
		$avatar->thumb35 	= (!empty($thumb35) && file_exists($pathThumb35) ) ? $pathRoot.substr($thumb35, 1 ) : $pathRoot.$noAvatarThumb35URL ;

		if($avatar->thumb200 == $noAvatarThumb200URL ){
			//Check for the existence of a gravatar		
			TuiyoLoader::helper("parameter");
			
			$sysCfg			= TuiyoParameter::load("global");
			$maxRating 		= $sysCfg->get("siteGravatarMaxRating", "X") ;
			
			if((bool)$sysCfg->get("siteEnableGravatars", 1 )):	
				$hash 		= md5($userEmail);
				$uri 		= 'http:www.gravatar.com/avatar/'. $hash . '?d=404';
				$jRoot		= JURI::root();				
				$avatar->thumb200 = sprintf($gravatarURL, $hash, $jRoot.'/'.$noAvatarThumb200URL, 200 , 0 , $maxRating );
				$avatar->thumb70  = sprintf($gravatarURL, $hash, $jRoot.'/'.$noAvatarThumb70URL, 70 , 0 , $maxRating );
				$avatar->thumb35  = sprintf($gravatarURL, $hash, $jRoot.'/'.$noAvatarThumb35URL, 35 , 0 , $maxRating );
			endif;
			
		}
		$params 			= null;		
		unset  ($params );
		
		if(isset($type) && !empty($type) && isset($avatar->$type)){
			$required = $avatar->$type;
			unset( $avatar );
			return $required ;
		}
		
		$required = clone $avatar;
		
		unset($avatar);
		//return (object) $instance[$userID];
		return (object)$required;
		
 	}  	
 	
 	
 	/**
 	 * TuiyoUser::get()
 	 * 
 	 * @param mixed $varname
 	 * @param string $default
 	 * @param string $dataType
 	 * @return void
 	 */
 	public function get( $varname , $default = "" , $dataType = "string" ){
 		//get a private variable deep in the user object
 		$self 		= ($this instanceof self)? $this : self::getInstance();
 		$profile 	= (object)$self->_profile;
 		
		//Unset Things we don't need;
		unset($profile->_tbl);
		unset($profile->_tbl_key);
		unset($profile->_errors );
		
		//Check for the variable	
 		if(isset($profile->$varname)){
 			return $profile->$varname; //TODO: Validate the result with the dataType variables
 		}elseif(!empty($default)){
 			return $default;
 		}
 		return NULL;
 	}
 	
 	
 	
 	/**
 	 * TuiyoUser::getUserFromRequest()
 	 * Gets User profile from request;
 	 * @return
 	 */
 	public function getUserFromRequest( $method = "request" )
	{
		$mainframe 	= $GLOBALS['mainframe'];
		$thisuser 	= $GLOBALS['API']->get( 'user' );
		$thatuser 	= null ;
		//2. Identify the profile ID;
		$userID 	= JRequest::getVar('pid' , null );
		$username 	= JRequest::getVar('user', null );
		
		if(!empty($userID)):
		
			$profileID 	= (int)$userID ;
			$thatuser 	= $GLOBALS['API']->get('user', empty($profileID) ? null : (int)$profileID  );
			
		elseif(empty($userID) && !empty($username)):
		
			$username 	= strval( $username );
			$thatuser 	= TuiyoLoader::getUserByUserName(  $username );
			if(!is_object( $thatuser) ) :
				$thatuser 	= $thisuser;
			endif;
			
		else:
		
			$thatuser 	= $thisuser;
			
		endif;
		
		return (object)$thatuser ;
	}
 	
 	/**
 	 * TuiyoUser::getUserLoggedInState()
 	 * 
 	 * @param mixed $userid
 	 * @return void
 	 */
 	public function isUserLoggedIn($userid = NULL)
	{
 		$user 	= JFactory::getUser( $userid );
 		$return = (!$user->get('guest'))? true : false ;
		
		return (bool)$return;
 	}
 	
 	/**
 	 * TuiyoUser::profileExists()
 	 * 
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function profileExists( $userID = NULL ){
 		
 		$user  = JFactory::getUser( $userID );
 		$table = TuiyoLoader::table("users");
 		
 		return $table->userProfileExists( $userID );
 	}
 	
 	/**
 	 * TuiyoUser::hasProfile()
 	 * 
 	 * @param mixed $userid
 	 * @return void
 	 */
 	public function hasProfile( $userid = null ){
 		//1.Check Profile loaded
 		if($this->_profileLoaded){
 			$U = $this->getProfileData( $userid );
 			if(!empty($U->profileID) && !is_null($U->profileID)){
 				return true;
 			}
 			return false;
 		}
 		return false;
 	}
 	
 	/**
 	 * TuiyoUser::getUserRating()
 	 * Returns the user rating
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function getUserRating($userID = NULL ){
 		
	    static $instance  = array();
		 
		$self 	 	= (!is_a($this , 'TuiyoUser')) ? TuiyoUser::getInstance( (empty($userID)) ? null : (int)$userID ) : $this ;
    	$userID		= (empty($userID)) ? $self->id : (int)$userID ;
    	
   		/**Returns an object with the current users */ 
    	if(isset($instance[$userID]) && is_object($instance[$userID]) ){
    		return (object)$instance[$userID];
    	}
    	
 		$rating = round((int)$self->get('profileRatings') / (int)$self->get('totalVotes') , 0 );
 		
 		$instance[$userID] = (int)$rating;
 		
 		return (int)$rating;
 		
 	}
 	
 	/**
 	 * TuiyoUser::getUserData()
 	 * 
 	 * @param mixed $userID
 	 * @return bool FALSE on error / Data Object on Success
 	 */
 	private function _getUserData($userID = NULL)
 	{ /*Returns an Object Representation of the User Table*/
 		$userID = !is_null($userID) ? $userID : $this->id ;
	 
	 	if(!is_object( $this ) || !is_int( (int)$userID )){
 			JError::raiseError(TUIYO_SERVER_ERROR , _("Invalid Call, TuiyoUser" ) );
		}
	 	
		if(!$this->_profileLoaded){
			//user proile loaded
			$usersTable = &TuiyoLoader::table( "users" , true );
			$usersTable->load( $userID  );
		
			//load the profile
			$this->_profile = $usersTable;
			if(is_a($this->_profile, 'TuiyoTableUsers')){
				$this->_profileLoaded = true;
				return true;
			}
			//return success
			return false;
	 	}
	 	return true;
 	}
 	
 	/**
 	 * TuiyoUser::getProfilePicture()
 	 * OBsulate, User getUserAvatar instead
 	 * @param int $userID
 	 * @return
 	 */
 	public function getProfilePicture($userID = NULL){
 		$user  		= &$this;
		$cached 	= array();
		
		if( !($user instanceof self) ){
			$user  	= self::getInstance();
		}
 		$userID 	= (is_null($userID)) ? $user->id : $userID ;
		//If we already have one
		if(isset($cached[$userID])) return $cached[$userID];
		
 		$userTable 	= &TuiyoLoader::table("users" , true);
 		
 		$userPIC 	= $userTable->getUserAvatar( $userID );
 		$cached[$userID] = $userPIC;
 		
 		return $cached[$userID];
 	}
 	
 	/**
 	 * TuiyoUser::getProfileData()
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function getProfileData($userID = null){
 		if(!$this->_profileLoaded){
 			if($this->getUserData( $userID )){
 				if(is_a($this->_profile, 'TuiyoTableUsers')){
 					return $this->_profile;
 				}
 			}else{
 				//maybe try something else!...?
 				JError::raiseError(TUIYO_SERVER_ERROR , _("Could not load Profile, TuiyoUser") );
 			}
 		}
		return $this->_profile;
 	}
 	
 	/**
 	 * TuiyoUser::getUserSocialBook()
 	 * Returns an array of user social information
 	 * @param mixed $userID
 	 * @return void
 	 */
 	public function getUserSocialBook($userID = NULL )
 	{
    	static $instance  = array();
    	
    	$table 		= TuiyoLoader::table("fields", true);
    	$self 	 	= (!is_a($this , 'TuiyoUser')) ? TuiyoUser::getInstance() : $this ;
    	$contact	= new stdClass;
    	$userID		= (empty($userID)) ? $self->id : $userID ;
   		/**Returns an object with the current users */ 
    	if(isset($instance[$userID]) && is_object($instance[$userID]) ){
    		return (object)$instance[$userID];
    	}
    	
    	//Load User Social
    	$fields 	= $table->listAll( TRUE );
		$params 	= TuiyoApi::get("params");
		
		$params->loadParams("user.social" , $userID );
		$defines 	= $params->defined;
		
		foreach($fields as $field ):
			$field->fd = $params->get( $field->fn );
		endforeach;
		
		//Return a list of fields and values
		return (array) $fields ; 		
 	}
 	
 	/**
 	 * TuiyoUser::getUserStatus()
 	 * Returns an object representation of the user status
 	 * 
 	 * @param mixed $userID
 	 * @return bool FALSE on errors / Array on success
 	 */
 	public function getUserCurrentStatus($userID = NULL)
 	{}
 	
 	/**
 	 * TuiyoUser::getUserSettings()
 	 * Gets the user config params as an object. 
 	 * 
 	 * @param mixed $userID
 	 * @param mixed $paramGroup 
 	 * @return bool FALSE on errors
 	 */
 	public function getUserParams($userID = NULL, $paramGroup = NULL)
 	{
 		
 	}
 	
 	
 	/**
 	 * TuiyoUser::getUserAppParams()
 	 * Gets user specific Application settings as an object
 	 * 
 	 * @param mixed $appID
 	 * @param mixed $userID
 	 * @return void
 	 */
 	public function getUserAppParams($appID, $userID = NULL){}
 	
 	/**
 	 * TuiyoUser::getUserConnection()
 	 * Loads the user relationship management class
 	 * 
 	 * @param mixed $userID
 	 * @return bool FALSE on errors/ Object on success
 	 */
 	public function getUserConnection($userID = NULL)
 	{}
 	
	/**
	 * TuiyoInitiate::getErrors()
	 * Gets all errors that might have occured whilst processing
	 * 
	 * @return
	 */
	public function getErrors()
	{
		$errors = "";
		if(isset($this->_errors)){
			foreach($this->_errors as $id=>$msg){
				$errors .= "[$id] $msg\n";
			}
		}
		return nl2br( $errors );
	}
 	
 	/**
	 * TuiyoUser::_setErrors()
	 * Sets any processing errors for later
	 * 
	 * @param mixed $error
	 * @return
	 */
	private function _setErrors($identifier, $errorMsg )
	{
		$identifier = !empty($identifier) ? $identifier : TUIYO_SERVER_ERROR;
		$message	= trim( $errorMsg );
		
		$this->_errors[$identifier] = $message;
	}
	
	
	/**
	 * TuiyoUser::createProfile()
	 * 
	 * @param mixed $data
	 * @return
	 */
	public function createProfile( $data )
	{	//Do we have data to deal with?
		if(!isset($data) || empty($data)){
			$this->_setErrors(TUIYO_SERVER_ERROR , _("Invalid UserData, Could not create Profile") );
			return false;
		}
		//Get required table!
		$table =&TuiyoLoader::table( "users" , true );
		
		//Name required
		if(empty($data['profileName'])){
			$this->_setErrors(TUIYO_SERVER_ERROR , _("Profile name required to create a profile") );
			return false;
		}
		//die;
		$table->bind( $data );
		//Make sure this variables are what we expect
		$table->userID 			= $this->id ;
		$table->dateCreated 	= date('Y-m-d H:i:s');
		$table->sex 			= (int)$table->sex;
		
		//store the new user
		if(!$table->storeObj()){
			$this->_setErrors(TUIYO_SERVER_ERROR, $table->getError( ));
			return false;
		}
		
		//Yuppee!
		return true;
	}
	
	
	/**
	 * TuiyoUser::getInstance()
	 * 
	 * Gets an instance of the user object
	 * 
	 * @param mixed $userid
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($userid = null, $ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance['uid:'.$userid])&&$ifNotExist&&!empty($userid)){
			if(is_object($instance['uid:'.$userid])){
				return $instance['uid:'.$userid];
			}else{
				unset($instance['uid:'.$userid]);
				TuiyoUser::getInstance($userid , $ifNotExist );			
			}								
		}else{
			$object = new TuiyoUser( $userid );
			if(!empty($userid)){
				$instance['uid:'.$userid]  = $object ;	
			}	
		}
		return $object;	
  	}
  	
  	/**
  	 * Combines the UserObject with this Object
  	 * TuiyoUser::_bind()
  	 * 
  	 * @param mixed $userObject
  	 * @return void
  	 */
  	private function _bind( $userObject ){
  		
	  	if(!is_object($userObject)){
  			TuiyoError::raiseError(TUIYO_NOT_ACCEPTABLE, _("The Entity Passed is not an Object") );  		
  		}
  		//bind
  		$this->id 			= isset($userObject->id)? $userObject->id : $this->id;
  		$this->username 	= isset($userObject->username)?  $userObject->username : $this->username;
  		$this->_password 	= isset($userObject->password)? $userObject->password : $this->_password;
  		$this->activation  	= isset($userObject->activation)? $userObject->activation : $this->activation;
  		$this->aid 			= isset($userObject->aid)? $userObject->aid : $this->aid;
  		$this->block 		= isset($userObject->block)? $userObject->block : $this->block;
  		$this->email 		= isset($userObject->email)? $userObject->email : $this->email;
  		$this->gid			= isset($userObject->gid)?$userObject->gid: $this->gid;
  		$this->lastvisitDate= isset($userObject->lastvisitDate)?$userObject->lastvisitDate: $this->lastvisitDate;
  		$this->registerDate	= isset($userObject->registerDate)?$userObject->registerDate: $this->registerDate;
  		$this->guest		= isset($userObject->guest)?$userObject->guest: $this->guest;
  		$this->sendEmail	= isset($userObject->sendEmail)?$userObject->sendEmail: $this->sendEmail;
  		$this->name			= isset($userObject->name)?$userObject->name	: $this->name;
  		$this->params		= isset($userObject->params)?$userObject->params: $this->params;
  		$this->usertype		= isset($userObject->usertype)?$userObject->usertype: $this->usertype;
  		$this->joomla   	= $userObject ;
  		
  		return true;
  	}
 }
 
 /*** Exception ***/
 class userAvatar{}