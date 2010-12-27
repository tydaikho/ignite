<?php
/**
 * ******************************************************************
 * Profile controller for the tuiyo application                     *
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
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');

/**
 * TuiyoControllerProfile
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerProfile extends JController
{
	private $_pathway = null;
	
	public  $events   = null;
	
	/**
	 * TuiyoControllerProfile::__construct()
	 * Constructs the task;
	 * @return void
	 */
 	 public function __construct(){
		
		/** Do we need user to be logged in? **/		
		TuiyoControllerCore::init( "Profile", false );
		TuiyoEventLoader::preparePlugins( "profile" );
		
		//Parent constructor or break
		parent::__construct();	
		
	 }
	 
	 /**
	  * TuiyoControllerProfile::display()
	  * Redirects to the welcome page if no task is specified.
	  * @return void
	  */
	 public function display(){
	 	
	 	$redirect = JRoute::_(TUIYO_INDEX."&amp;view=welcome");
	 	
	 	$this->setRedirect( $redirect );
	 	$this->redirect();
	 	
	 }
	 
    /**
     * TuiyoControllerProfile::getFeed()
     * Displays a user feed
     * @return void
     */
    public function getFeed(){
    	$view 	= $this->getView('profile', "feed");
    	$view->showFeed();
    }	
	
	public function viewStatus()
	{
		$statusID = JRequest::getInt('id', null );
		if(!empty($statusID) || (int)$statusID>0){
			$GLOBALS['mainframe']->addMetaTag( "sid" , (int)$statusID );
		}
		return $this->view();
	} 
	 
	 /**
	  * TuiyoControllerProfile::getSocialForm()
	  * Get social Field Form;
	  * @return void
	  */
	 public function getSocialForm()
	 {
		$doc  	= JFactory::getDocument();
		$view 	= $this->getView( 'profile' , "json" );
		$model	= $this->getModel('profile');
		
		$auth	= $GLOBALS['API']->get('authentication');
		
		//Request authentication
		$auth->requireAuthentication();
		
		$user 	= $GLOBALS['API']->get( 'user' , null );
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => false, 
			"data" 	=> $model->buildSocialBookForm( true ),
			"extra"	=> false 
		);
		
		//Return response;
		return $view->encode( $resp );		
			 	
	 }
	 
	/**
	 * Method to display the users profile
	 * Profile ID must be specified, or else redirected to own profile
	 * @access	public
	 */
	public function view( $tpl = null )
	{
		//1. Requirements
		$thisuser 	= $GLOBALS['API']->get( 'user' );
		
		$redirect 	= JRoute::_(TUIYO_INDEX."&amp;view=welcome");
		$thatuser 	= TuiyoUser::getUserFromRequest();
		$thatuser	= !is_object($thatuser) ?  $thisuser : $thatuser ;
		
		$profileID	= $thatuser->id;
		$view 		= $this->getView('profile', "html" );
		$fModel 	= $this->getModel('friends');
		$pModel 	= $this->getModel('profile' );

		
		//3. Check Privacy Settings;
		$uPrivacy 	= &$GLOBALS['API']->get( 'privacy' );
		$tPrivacy 	= array(
			"canViewProfile" 	=> $uPrivacy->canViewProfile( $thatuser->id , $thisuser->id ),
			"canViewProfileEx"	=> $uPrivacy->canViewExtendedProfile( $thatuser->id , $thisuser->id ),
			"canViewProfileSb"	=> $uPrivacy->canViewProfileInformation( $thatuser->id , $thisuser->id ),
			"canViewProfileFrs"	=> $uPrivacy->canViewProfileFriends( $thatuser->id, $thisuser->id ),
			"canRateProfile"	=> $uPrivacy->canRateUser( $thatuser->id, $thisuser->id ), 
			"canAddAsFriend"	=> $uPrivacy->canAddAsFriends( $thatuser->id, $thisuser->id )
		);
		
		//If guest profile?
		if($thatuser->joomla->get('guest')){
			$this->setRedirect( $redirect );
			$this->redirect();
		}
		//4. Load Extended Profile,
		$uFriends 	= $fModel->getFriendLists($thatuser->id, $thisuser->id, 1 );
		//Bread crumbs and Page Title		
		
		$GLOBALS['mainframe']->addMetaTag( "pid" , $thatuser->id );	
		
		//Assign Variables
		$view->assignRef("friends",   $uFriends );
		$view->assignRef("privacy",   $tPrivacy );
		$view->assignRef("thisuser" , $thisuser );
		$view->assignRef("thatuser",  $thatuser );
		$view->assignRef("controller",$this );
		
		$pModel->incrementViews( $thatuser->id , +1 );
		
		//onProfileView
		$GLOBALS["events"]->trigger( "onProfileView" , $this );
		
		$view->display( $tpl , $thatuser->id , $tPrivacy );
	}
	
	/**
	 * TuiyoControllerProfile::settings()
	 * Gets the application settings pages
	 * @return json
	 */
	public function settings()
	{
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');

		//Request authentication
		$auth->requireAuthentication( "request" );

		$view 	 	= $this->getView('profile', "html");
		$model 		= $this->getModel( 'profile' );
		$plugModel 	= $this->getModel('applications');
		
 		$user  		= $GLOBALS['API']->get( 'user' );
		$styleDir 	= TUIYO_STYLEDIR;	
		
		$contact 	= $user->getUserContact( );
		$avatars 	= $model->getUserAvatars( );
		$style   	= $model->getTemplateParams( );
		
		$myPlugins  = $plugModel->getAllUserPlugins( $user->id , "services" ,true );
		$plugins	= $plugModel->getAllSystemPlugins("services", true , false , true , $user->id );
		
		$view->assignRef("plugins", $plugins);
		$view->assignRef("contact", $contact);
		$view->assignRef("avatars", $avatars);
		$view->assignRef("style", $style);
		$view->assignRef("user", $user );
		$view->assignRef("myplugins", $myPlugins);
		$view->settings(  );	
	}
	
	/**
	 * TuiyoControllerProfile::myFiles()
	 * @return void
	 */
	public function filemanager()
	{
		$auth = $GLOBALS['API']->get('authentication');		
		$auth->requireAuthentication( "request" );
		
		$user 		= $GLOBALS['API']->get( 'user' );
		$document 	= $GLOBALS['API']->get("document");
				
		$view 		= $this->getView('profile', "html" );
		$resources 	= TuiyoLoader::model( "resources" , true );
				;
		//Get the HTML
		$tmplPath 		= TUIYO_VIEWS.DS."profile".DS."tmpl" ;
		$tmplVars 		= array(
			"styleDir"	=>TUIYO_STYLEDIR,
			"user"	  	=>$user 
		);
		
		$myfiles 		= $resources->getMyResources( $user->id );	
			
		$view->assignRef("myfiles" , $myfiles );
		$view->assignRef("user" , $user );
		$view->filemanager(  );
		
	}
	
	/**
	 * TuiyoControllerProfile::myFiles()
	 * @return void
	 */
	public function activity()
	{
		
		$document 	= $GLOBALS['API']->get("document");
		$user 		= $GLOBALS['API']->get( 'user' );
		$view 		= $this->getView('profile', "json" );
				
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
				
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] = TUIYO_BAD_REQUEST;
			$resp["error"]= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}
		//Get the HTML
		$tmplPath 		= TUIYO_VIEWS.DS."profile".DS."tmpl" ;
		$tmplVars 		= array(
			"styleDir"	=>TUIYO_STYLEDIR,
			"user"		=>$user,
			"sharewith" =>array("p00"=>"@everyone"),
			"canPost"	=>(!$user->joomla->get('guest') ) ? 1: 0 			
		);
		$resp['data'] 	= $document->parseTmpl("activity" , $tmplPath , $tmplVars);
		
		$view->encode( $resp );	
	}
	
	/**
	 * TuiyoControllerProfile::homepage()
	 * @return void
	 */
	public function homepage()
	{
		global $API , $mainframe;		
		//TuiyoAPI::get("ini");
		
		$doc  	= JFactory::getDocument();
		$view 	= $this->getView('profile' , $doc->getType() );
		
		$auth	= $API->get('authentication');
		//Request authentication
		$auth->requireAuthentication( "request" );
		$user 	= $API->get( 'user' , null );
		
		$bc 	= &$mainframe->getPathway();
		$pt		= &$mainframe->setPageTitle( sprintf(_("%s | Homepage"),$API->get('user')->name ) );
				
	
		$view->setLayoutExt('tpl');
		$view->setLayout('homepage');
		$view->assignRef("user" , $user );
		
		//print_R( $GLOBALS['TUIYO_CACHE']->getStores( ) );
		
		$bc->addItem( $API->get('user')->name );
		
		//onProfileHomePageBuild
		$GLOBALS["events"]->trigger( "onProfileHomePageBuild"  );
		
		//parent::display( false );
		
		$view->homepage( );		
	}
	
	/**
	 * Authorises a user to run the given controller!
	 * TuiyoControllerCore::authorise()
	 * @return void
	 */
	public function authorise()
	{
		global $API;
		
		$user = $API->get( 'user' );
			
	}
	
	public function rateProfile()
	{
		$data 		= JRequest::get( "post" );
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');
		
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect( TUIYO_PROFILE_INDEX );	
		}
		$model 	 	= $this->getModel("profile");
		$view	 	= $this->getView('profile', "json" );
		
		//Request authentication
		$auth->requireAuthentication( "request" );
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> $model->setProfileRating( $data ), 
			"extra" => null
		);
		
		return $view->encode( $resp );
	}
	
	/**
	 * TuiyoControllerProfile::appControlPanel()
	 * @return
	 */
	public function appControlPanel()
	{
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');
		
		if($document->getDOCTYPE() !== "json"){
			$GLOBALS['mainframe']->redirect(TUIYO_PROFILE_INDEX);	
		}
		$view = $this->getView('profile', "json" );
		//Request authentication
		$auth->requireAuthentication( "request" );		
		$user = $GLOBALS['API']->get( 'user' );
		
		//Response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
		//getUserApplications;
		
		$appModel = $this->getModel("applications" );
		$prfModel = $this->getModel("profile" );
		//Get the HTML
		$tmplPath 		= TUIYO_VIEWS.DS."profile".DS."tmpl" ;
		$tmplVars 		= array(
			"user"			=> $user,
			"appdirectory" 	=> $appModel->getApplicationExtendedList( TRUE ),
			"userApps"		=> $appModel->getAllUserApplications( $user->id ),
			"styleDir"		=> TUIYO_STYLEDIR,
			"livePath"		=> TUIYO_LIVE_PATH
		);
		
		$resp['data'] 	= $document->parseTmpl("controlpanel" , $tmplPath , $tmplVars);
		
		return $view->encode( $resp );
	}
	
	/**
	 * TuiyoControllerProfile::extender()
	 * Renders and profile Extender
	 * @param mixed $profileID
	 * @param mixed $extender
	 * @return
	 */
	public function extender($profileID = NULL, $extender=NULL  )
	{
		//You can view an extender directly by calling
		//view=profile&do=extender&name=twitter, Will sho just your twitter stream!
		$action 	= $this->getTask();
		$action 	= strtolower($action);
		$method 	= strtolower(__FUNCTION__ ); 
		
		//Reconstruct profile!
		if($action  === $method){
			return $this->renderExtenalProfile( );
		}
		
		//echo __FUNCTION__ ;
		if(!is_null($extender) && !empty($extender)){
			
			$extender		= strtolower( $extender );
			$xtProfile 		= ucfirst($extender)."ProfileController";
			$xtProfileCntrl	= $extender.".controllers.".$extender."profile";
			
			//Import the profile extender component
		 	TuiyoLoader::import( $xtProfileCntrl , "component" );
		 	
			if(!class_exists($xtProfile)){
				JError::raiseError( TUIYO_SERVER_ERROR , $xtProfile." not found" );
				return false;	
			}
			
			$xtProfileObject= new $xtProfile();
			
			if(!method_exists( $xtProfileObject, "render" ) ){
				JError::raiseError( TUIYO_SERVER_ERROR , $xtProfile."::render() not found" );
				return false;	
			}
			 
		 	$externalProfile = $xtProfileObject->render();
		 	
		 	//clean up
		 	unset($xtProfileObject);
		 	
			return $externalProfile; 
		}
	}
	
	private function renderExtenalProfile(){
		
		$profileID 		= JRequest::getVar("pid" , (int)$profileID );
		$extender 		= JRequest::getVar("name", $extender );
		$extenderType 	= JRequest::getVar("type", $type );
		
		echo "build Extender";
		return;
	}
	
	
	/**
	 * Saves profile settings to the params table
	 * TuiyoControllerProfile::saveSettings()
	 * @return void
	 */
	public function saveParams(){
		
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
		);
		//Request authentication
		$auth->requireAuthentication( "post" );
		
		$user = $GLOBALS['API']->get( 'user', null );
		$post = JRequest::get( "post" );
		$key  = $post["pkey"];
		$key  = (empty($key)) ? $post["paramKey"] : $key ;
		
		//Primary validation
		if(!isset($key) || empty($key) ) trigger_error( "INVALID PARAM KEY", E_USER_ERROR ); 
		if(!JRequest::checkToken() || $user->id <> (int)$post["userid"] ) trigger_error( "INVALID USER TOKEN", E_USER_ERROR );
		
		//We have post data;
		$params 	= $GLOBALS['API']->get("params", $key , $user->id );
		$params->loadParams( $key ,  $user->id );
		
		//print_R($post);
		
		$savedP 	= $params->storeUserParams( $key , $user->id  , $post );		
		
		//Response
		if($document->getDOCTYPE() !== "json"){
			$document->enqueMessage( $resp["message"] , ( empty($resp["error"]) ) ? "message" : "error" );
			return $this->settings();	
		}
		
		$view = $this->getView('profile', "json" );
		
		return $view->encode( $resp );
	}
	
	
	/**
	 * TuiyoControllerProfile::saveTemplateParams()
	 * Saves a template parameter;
	 * @return
	 */
	public function saveTemplateParams(){
		
		jimport('joomla.filesystem.file');
		jimport('joomla.client.helper');
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$document 	= $GLOBALS['API']->get("document");
		$auth		= $GLOBALS['API']->get('authentication');
		
		$resp = array(
			"code" 	=> TUIYO_OK, 
			"msg"	=> _("Your template parameters have been saved"),
			"error" => null, 
		);
		//Request authentication
		$auth->requireAuthentication();
		
		$user 		= $GLOBALS['API']->get( 'user', null );
		$postParams	= JRequest::getVar('params', array(), 'post', 'array');
		//Else return JSON
		
		$view 		= $this->getView('profile', "json" );
		$model 		= $this->getModel('profile');
		
		//Save in current Template        
		$params = array(
			'template' 	=> $GLOBALS['mainframe']->getTemplate(),
			'directory'	=> JPATH_THEMES
		);		
		// check
		$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
		$template	= JFilterInput::clean($params['template'], 'cmd');
		
		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');
		
		$ftp 		= JClientHelper::getCredentials('ftp');	
		$file 		= TUIYO_STYLES.DS.$user->id.DS.$template.".ini" ; 
	
		if(JFile::exists($file)){
			JFile::write($file);
		}
		
		if ( count( $postParams) )
		{
			$registry 	= new JRegistry();
			$registry->loadArray( $postParams );
			$iniTxt 	= $registry->toString();

			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
				JError::raiseNotice('SOME_ERROR_CODE', JText::_('Could not make the template parameter file writable'));
				return false;
			}
			
			//Write the file
			$return = JFile::write($file, $iniTxt );

			// Try to make the params file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
				JError::raiseNotice('SOME_ERROR_CODE', JText::_('Could not make the template parameter file unwritable'));
				return false;
			}

			if (!$return) {
				$resp = array(
					"code" 	=> TUIYO_SERVER_ERROR, 
					"msg"	=> _("Could not save the template parameters"),
					"error" => null, 
				);
			}
		}		
		
		return $view->encode( $resp );
	}

	
	/**
	 * Edits user settings
	 * TuiyoControllerProfile::edit()
	 * @return void
	 */
	public function saveEdit(){
		
		//Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$view 	= $this->getView('profile', "json" );
		$user 	= $GLOBALS['API']->get( 'user', null );
		$auth 	= $GLOBALS['API']->get( 'authentication' );
		
		//clean request
		$post = JRequest::get( 'post' );
		
		//Request authentication
		$auth->requireAuthentication( "post" );
		
		//if issed delete
		if(isset($post['deleteUser'])){
			if( (bool)$post['deleteUser']){
				return $this->_deleteUserProfile( $postData );
			}
		}
		//if isset suspend
		if(isset($post['suspendUser'])){
			if((bool)$post['suspendUser']){
				return $this->_suspendUserProfile( $postData );
			}
		}

		$post['username']	= JRequest::getVar('username', '', 'post', 'username');
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// preform security checks
		if ($user->id == 0 || (int)$post["jid"] <> $user->id ) {
			trigger_error('Access Forbidden', E_USER_ERROR );
			return;
		}
		
		// we don't want users to edit certain fields so we will unset them	
		unset($post['gid']);
		unset($post['block']);
		unset($post['usertype']);
		unset($post['username']);
		unset($post['registerDate']);
		unset($post['activation']);

		// store data
		//$userCtrl 	= $this->getController( );
		$profileModel 	= $this->getModel( 'profile' );

		if (!$profileModel->storeJoomlaUser( $post )) {
			trigger_error($profileModel->getError(), E_USER_ERROR );
			return false;
		}
		//Now handle the Tuiyo Save Edit
		if(!$profileModel->storeTuiyoUser( $post )){
			trigger_error("Could not save user Data", E_USER_ERROR );
			return false;	
		}
		
		//Response
  		$resp = array(
			"code" 	  => TUIYO_OK, 
			"error"   => null,
			"callback"=> "" 
		);	
		
		return $view->encode( $resp );
	}
	
	/**
	 * Completely deletes a user from the Joomla site
	 * TuiyoControllerProfile::_deleteUserProfile()
	 * @param mixed $userID
	 * @return void
	 */
	private function _deleteUserProfile( $postData ){
		
		$profileModel 	= $this->getModel( 'profile' );
		//1. first, check that the user can delete the profile!
		//2. Check that users are allowed to delete their profile automatically
		     //If not send a profile deletion requiest to the admin
  		//3. Start profile delete routine
  		if(!$profileModel->deleteProfile( $postData ) ){
  			trigger_error("Could not Delete the User profile" , E_USER_ERROR );
  			return false;
  		}
  			//TODO: Remember to trigger on profile Delete Events
		//4. Delete User from Joomla Tables
		//5. Send Deletion notice to admins
		//6. Logout User 
		//7. Redirect to home page
		echo "delete profile";
		
		
		return true;
	}
	
		
	/**
	 * Suspends the profile. User can still login
	 * TuiyoControllerProfile::_suspendUserProfile()
	 * @param mixed $userID
	 * @return void
	 */
	private function _suspendUserProfile( $postData ){
		
		$profileModel 	= $this->getModel( 'profile' );
		//1. first, check that the user can suspend the profile!
		//2. Check that users are allowed to suspend their profile automatically
		     //If not send a profile suspension requiest to the admin
  		//3. Start profile delete routine
  		if(!$profileModel->suspendProfile( $postData ) ){
  			trigger_error( "Could not suspend User profile" , E_USER_ERROR );
  			return false;
  		}
  			//TODO: Remember to trigger on profile Delete Events
		echo "suspended profile";
		
		return ;
	}	
}