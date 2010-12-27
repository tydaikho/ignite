<?php
/**
 * ******************************************************************
 * Core controller object for the Tuiyo platform                           *
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
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * TuiyoControllerCore
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerCore extends JController
{
    /**
     * Current Application
     */
    private $current = null;
    
    
    /**
     * TuiyoControllerCore::ini()
     * Cannot be called from object mode
     * @param mixed $app
     * @param bool $authorize
     * @return
     */
    public function init($app=NULL, $authorize = TRUE){
    	
    	$user = $GLOBALS['API']->get('user', null);
    	
    	if($user->isUserLoggedIn() && !$user->profileExists( $user->id ) ){
			//If user does not have a profile
        	$start 	= JRoute::_(TUIYO_INDEX."&amp;redirect=core&amp;do=start", false , null );
        	$this->setRedirect( $start );
        	$this->redirect();
    	}
    	
  		$self	= new TuiyoControllerCore($app, $authorize);
    	return $self;
    }

    /**
     * Class constructor
     * TuiyoController::__construct()
     * 
     * @return void
     */
    public function __construct($app = null, $authorise = true)
    {
        $this->current = $app;
        $doFoo = JRequest::getVar("do", null);
        $view  = array( JRequest::getVar("view") , JRequest::getVar("redirect") );
        
        //Doable Tasks
        static $dos = array(
        	"help"		=> "showHelp",
			"login" 	=> "doLogin",
			"register" 	=> "registerUser", 
			"exAPI" 	=> "externalLogin",
			"start"		=> "createProfile",
			"setup"		=> "buildProfile",
			"signup"	=> "createUser"
		);

        //Check that the user is logged In / Or allowable task
        if ($authorise && !array_key_exists($doFoo, $dos)){
			
			$user = $GLOBALS['API']->get('user', null);
			
            if (!$user->isUserLoggedIn()) {
                //$document 	= $GLOBALS['API']->get('user', null);
                $welcome 	= JRoute::_(TUIYO_INDEX.'&amp;redirect=welcome&amp;do=login', false, null);
                $this->setRedirect( $welcome , _("You must be logged in to view this section of our site" ), "notice");
                $this->redirect();
                
            }elseif(!$user->hasProfile()){
            	//If user does not have a profile
            	$start 	= JRoute::_(TUIYO_INDEX."&amp;redirect=core&amp;do=start", false , null );
            	$this->setRedirect( $start );
            	$this->redirect();
            }
            TuiyoControllerCore::authorise();
        }
        //Construct the parent aswell!
        parent::__construct();
		TuiyoEventLoader::preparePlugins( "profile" );
		
        //Authorise and register Task
        if (is_null($app) && array_key_exists($doFoo, $dos)) {
            $this->authorise();
            $this->registerTask($doFoo, $dos[$doFoo]);
            //die;
        }elseif(!array_key_exists($doFoo, $dos)&& in_array ( "core" , $view ) ){
        	$GLOBALS['mainframe']->redirect( JRoute::_( TUIYO_INDEX."&view=welcome" ) );	
        }
    }
    
    /**
     * TuiyoControllerCore::buildProfile()
     * 
     * @return void
     */
    public function buildProfile(){
    	
   		
    	
        $view =&$this->getView("profile", "html");
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null,
			"msg"	=> null,
			"msgtyp"=> "messassge"
		);
		
		$file =&JRequest::get( 'files' );
		$data =&JRequest::get( 'post' );

		//avatar Only
		$data['file'] = $file;
    	
    	//1: Validate the data
    	//2:a Save Data into TuiyoUsers
    	$profileLink 	= JRoute::_( TUIYO_PROFILE_INDEX );
    	$profileObj 	= $GLOBALS['API']->get( "user" , null );
    	
    	if(!$profileObj->createProfile( $data ) ){ 
    		$resp["msgtyp"] = "error";
    		$resp["msg"] 	= $profileObj->getErrors();
    	}    	
		//2:b Save Data to all other table (Social fields)
    	//3: If avatar uploaded, Create Avatar Album
    	//4: If Group Joined, add user to group;
    	//5: If Invited add users to friends lists!
    	//6: Call All afterProfileBuild Hooks!
		TuiyoEventLoader::preparePlugins( "profile" );
		$GLOBALS["events"]->trigger("onProfileBuild" , $profileObj );
    	//7: Hurray your profile has been created
    	
    	//Redirect#
		$this->setRedirect( $profileLink , $resp["msg"], $resp["msgtyp"]);
		$this->redirect();
    }
    
   	/**
   	 * TuiyoControllerCore::showHelp()
   	 * 
   	 * @param mixed $tpl
   	 * @return void
   	 */
   	public function showHelp($tpl=null){
   		
		//Ready the view
		$view = $this->getView('welcome' , "html" );
		
		//Display the view!
		$view->help( );
	}
    
    /**
     * TuiyoControllerCore::doLogin()
     * Logs in a user to their profile
     * @return void
     */
    public function doLogin()
    {
        $view = $this->getView("welcome", "json");
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
    	$credentials = array();
   	    $options 	 = array();
   	    
   	    $password 	 = JRequest::getVar("password");
   	    $username	 = JRequest::getVar("username");
   	    
  		if(empty($username) || empty($password)){
  			$resp['code'] 	= TUIYO_AUTH_REQUIRED;
			$resp['error'] 	= _("Authentification required, but you provided incomplete details" ) ; 
			
			return $view->returnJSON( $resp ); 		
  		}

		$credentials['username'] = $username;
		$credentials['password'] = $password;
		
		$options['remember'] 	 = JRequest::getBool('remember', false);
		$options['return'] 	 	 = base64_encode( JRoute::_( TUIYO_INDEX ) );
		
    	$GLOBALS['mainframe']->login($credentials, $options);
    	
    	if($GLOBALS['API']->get('user', null)->isUserLoggedIn()){
    		$resp['data'] = array(
    			_("Logged In successfully!")
			); 
    	}else{
    		$resp['code']  = TUIYO_AUTH_REQUIRED;
			$resp['error'] = _("Authentification required, but the Username and/or Password are/is incorrect" );    	
    	}
    	
        $view->returnJSON( $resp );
    }


    /**
     * Sets the page Title
     * TuiyoControllerCore::setTitle()
     * 
     * @return void
     */
    public function setTitle($title)
    {
        JDocument::setTitle($title);
    }


    /**
     * Sets the pathway for the current controller
     * TuiyoControllerCore::setPathway()
     * @return void
     */
    public function setPathway($pathway)
    {
        JPathway::setPathway($pathway);
    }

    /**
     * TuiyoControllerCore::authorise()
     * 
     * @return void
     */
    public function authorise()
    {
    	$do 		= JRequest::getVar("do" , null );
    	$guestDo 	= array( "help" );
    	if (!$GLOBALS['API']->get('user', null)->isUserLoggedIn() && !in_array($do, $guestDo )) {
	        if (!JRequest::checkToken("request")){
	            JError::raiseError(TUIYO_UNAUTHORISED, "Invalid Token");
	        }
        }
    }
    
    
    /**
     * TuiyoControllerCore::createProfile()
     * 
     * @param mixed $userid
     * @return void
     */
    public function createProfile($userid = null)
    {
    	$view 	= $this->getView("welcome", "html");
    	$user 	= $GLOBALS['API']->get( "user" , null );
    	
    	//1: Get Social profile fields
    	//2: Check settings for profiles 
    	//3: Return form
    	
    	$view->showSetup( $user );
    }
    
    
    /**
     * TuiyoControllerCore::createUser()
     * Adding a user to the core user Table. Ported from JUser
     * @param mixed $userid
     * @return
     */
    public function createUser($userid = NULL )
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Get required system objects
		$user 		= clone( JFactory::getUser() );
		$pathway 	=& $mainframe->getPathway();
		$config		=& JFactory::getConfig();
		$authorize	=& JFactory::getACL();
		$document   =& JFactory::getDocument();
		$params 	=& TuiyoAPI::get( "params" );
		$wasInvited = false;

		// If user registration is not allowed, show 403 not authorized.
		$params->loadParams("system.global");
		
		//Allow registration or invite?
		$enableReg 	 = (bool)$params->get("siteAllowRegistration", FALSE );
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		$welcomeAuth = JRoute::_( TUIYO_INDEX.'&amp;view=welcome&do=auth');
		
		if ($usersConfig->get('allowUserRegistration') == '0' && !$enableReg) {	
			$this->setRedirect( $welcomeAuth, _("Registration is currently disabled via this route" ) , "error" );
			return false;
		}else{
			if( $enableReg ){
				$inviteCode 	= JRequest::getString('inviteCode', '' , 'post' );
				$inviteEmail 	= JRequest::getString('email', '', 'post' );
				$inviteTable 	= TuiyoLoader::table('invites', true );
				$inviteObject 	= $inviteTable->findInvite( (string)$inviteCode );
				
				if (empty($inviteCode) || empty($inviteObject) || !is_object($inviteObject) || ($inviteObject->email <> $inviteEmail ) ) {
					$this->setRedirect( $welcomeAuth,  _("Please provide a valid Invitation code") , "error" );
					return false;
				}	
				$wasInvited = TRUE;
			}else{
				$this->setRedirect( $welcomeAuth, _("Registration is currently disabled") , "error" );
				return false;
			}
		}
		// Initialize new usertype setting
		$newUsertype = $usersConfig->get( 'new_usertype' );
		if (!$newUsertype) {
			$newUsertype = 'Registered';
		}

		// Bind the post array to the user object
		if (!$user->bind( JRequest::get('post'), 'usertype' )) {
			$this->setRedirect( $welcomeAuth,  $user->getError() , "error" );
			return false;
		}

		// Set some initial user values
		$user->set('id', 0);
		$user->set('usertype', $newUsertype);
		$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

		// If user activation is turned on, we need to set the activation information
		$useractivation = $usersConfig->get( 'useractivation' );
		if ($useractivation == '1')
		{
			jimport('joomla.user.helper');
			$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}
		
		//Acepted Terms?
		$terms 	= JRequest::getVar("acceptTerms", 0 , 'post' );
		if(!(bool)$terms ){
			$msg	= _('You MUST pledge to abide by our terms of use');
			$this->setRedirect($welcomeAuth, $msg, 'error');
			return false;	
		}

		// Send registration confirmation mail
		$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$password2= JRequest::getString('password2', '', 'post', JREQUEST_ALLOWRAW);
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
		
		// do a password safety check
	    // so that "0" can be used as password e.g.
		if($password != $password2) {
			$msg	= _('Passwords do not match');
			$this->setRedirect($welcomeAuth, $msg, 'error');
			return false;
		}	
		
		// If there was an error with registration, set the message and display form
		if ( !$user->save() )
		{
			$this->setRedirect( $welcomeAuth, JText::_( $user->getError()), "error" );
			return false;
		}
		//TODO: SEND MESSAGE TO USER
		
		//IF WAS INVITED DELETE INVITE
		if($wasInvited){
			
			$inviteTable->load( (int)$inviteObject->ID );
			$inviteTable->state 	 = 1;
			$inviteTable->acceptdate = date('Y-m-d H:i:s');
			
			if(!$inviteTable->store()){
				JError::raiseError(TUIYO_SERVER_ERROR, _("Could not implement invite") );
				return false;
			}
		}

		// Everything went fine, set relevant message depending upon user activation state and display message
		if ( $useractivation == 1 ) {
			$message  = _( 'Your account has been created, An activation email has been sent to the e-mail provided' );
		} else {
			$message  = _( 'Your account has been created, no activation is required, You may now log-in' );
		}
		
		$welcomeAuthL = JRoute::_(TUIYO_INDEX.'&amp;view=welcome&amp;do=auth', FALSE );
		$this->setRedirect( $welcomeAuthL, $message , "notice");
				
	}
}
