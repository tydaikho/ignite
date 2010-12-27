<?php
/**
 * ******************************************************************
 * Application controller object for the Tuiyo platform             *
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
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');
/**
 * TuiyoControllerApps
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerApps extends JController
{
	private $_appName 	= null;
	
	private static $_appPaths 	= array();
	
	private $views		= array();
	
	private $started 	= false;
	
	private $userParams  = null;
	
	private $systeParams = null; 
	/**
	 * TuiyoControllerApps::__construct()
	 * @return void
	 */
	function __construct(){
		
		TuiyoControllerCore::init();
		parent::__construct();		
	 			//load the application
		$redirect 	= JRequest::getVar( "redirect", null);
		$app 		= JRequest::getVar( "app" , $redirect );
		$appTask	= JRequest::getVar( "ref" , null );
		$do 		= JRequest::getVar( "do" , null );
		
		if(!empty($app) && strval( strtolower ( $do ) ) !== "add" ){
			$this->startApplication(  $app  );	
		}
	 }
	 
	 /**
	  * TuiyoControllerApps::startApplication()
	  * 
	  * @param mixed $appName
	  * @return
	  */
	 public function startApplication( $appName ){
	 	
	 	if($started > 0){
	 		return true ;
	 	}
		//1. Check App exists 
		if(!$this->_appExists($appName)) 
			trigger_error( _("The requested application does not exists"), E_USER_ERROR);
		
		//and user has permission to run app!
		if(!$this->_authoriseUser( $appName ) ){
			//Redirect to their new app!
			$message = _( "To user this application you will need to add it to your account. "
		 			. "Visit Homepage > Applications ControlPanel > Browse Apps to add this application" );
        			         
			JError::raiseError( TUIYO_SERVER_ERROR , $message );
			
			//$this->redirect();
			return false;
		}
		
		//2. Load the Application
		$this->_loadApplication( ucfirst( $appName) );
		
		//4. Return the body as function of apps
		$this->setAppView( $appName );
		//echo "default app task";
		
	 	return $started++;
	 	
	 }
	/**
	 * Method to display the view
	 * Do NOT call parent::display() from child App
	 * 
	 * @access	public
	 */
	public function display(){
		
		$null = null;
		
		if( empty( $this->_appName) ){
			$view 	= $this->getView("apps", "html");
			$null   = $view->appDirectory();
		}
		return $null;
	}
	
	/**
	 * TuiyoControllerApps::_appExists()
	 * 
	 * @param mixed $appName
	 * @return void
	 */
	private function _appExists( $appName )
	{
		$path 	= TUIYO_APPLICATIONS.DS;
		$app 	= strtolower($appName );
		
		if(!file_exists($path.$app.DS.$app.'.php')) return false;
		//Else, yes the application exists
		$this->_appName	= $appName;
		
		return true;
	}
	
	/**
	 * TuiyoControllerApps::_authoriseUser()
	 * Authorises a User to run a particular application
	 * @return void
	 */
	private function _authoriseUser( $appName ){
		
		$user 	= $GLOBALS['API']->get( "user" );
		$table	= TuiyoLoader::table( "userapps" );
		
		//echo "authorise ".$appName;
		if(!$table->userHasApp( $appName , $user->id )){
			return false;
		}
				
		return true;
		
	}
	
	/**
	 * TuiyoControllerApps::getAppView()
	 * 
	 * @param mixed $name
	 * @param mixed $type
	 * @param mixed $prefix
	 * @param mixed $config
	 * @return
	 */
	private function setAppView($name, $type = "html", $prefix = null, $config = null){
		
		//$this->views 	= array();
		$result 		= false;

		// Clean the view name
		$viewName	 = preg_replace( '/[^A-Z0-9_]/i', '', $name );
		$classPrefix = preg_replace( '/[^A-Z0-9_]/i', '', $prefix );
		$viewType	 = preg_replace( '/[^A-Z0-9_]/i', '', $type );

		// Build the view class name
		$viewClass = $classPrefix.$viewName."View";
		
		//Overite if we already have this class?
		if(isset($this->views[$name]) && is_a($this->views[$name] , $viewClass )){
			unset( $this->views[$name] );
		}
		
		if ( !class_exists( $viewClass ) )
		{
			$file = TUIYO_APPLICATIONS.DS.$this->_appName.DS."views".DS.
			        $viewName.DS."view.".$viewType.".php";
			if(file_exists( $file) ) {
				require_once $file;
				if ( !class_exists( $viewClass ) ) {
					$result = trigger_error(
					 	'View class not found [class, file]:'
						. ' ' . $viewClass . ', ' . $path,
						E_USER_ERROR 
					);
					return $result;
				}
			} else {
				return $result;
			}
		}
		$result = new $viewClass( $config );
		
		$this->views[$name] = $result;
	}
	
	public function getAppModel( $name, $application = NULL )
	{}
	
	
	/**
	 * TuiyoControllerApps::getAppView()
	 * 
	 * @param mixed $name
	 * @param string $type
	 * @param mixed $prefix
	 * @param mixed $config
	 * @return
	 */
	public function getAppView( $name, $type = "html", $prefix = null, $config = null ){
			
		if(array_key_exists($name, $this->views)){
			return $this->views[$name] ;
		}
		//CheckType;
		$document = &JFactory::getDocument();
		$docType  = $document->getType();
		
		$type 	  = empty($type)? $docType : $type ;
		
		//Try loading the view 
		$this->setAppView($name, $type, $prefix , $config);
		
		//Return the view
		return $this->views[$name] ;
	}
	
	/**
	 * TuiyoControllerApps::_loadApplication()
	 * 
	 * @param mixed $appName
	 * @return void
	 */
	public function _loadApplication( $appName )
	{    
        $appName= strtolower( $appName );
        $file 	= TUIYO_APPLICATIONS.DS.$appName.DS.$appName.'.php';
        
        if(!file_exists($file)){
			trigger_error("The required application does not exists", E_USER_ERROR);
			return false;	
		}
		
		require_once( $file);	
		
		return true;
	}
	
	/**
	 * TuiyoControllerApps::getSystemParams()
	 * Gest the gobal system config for the current APP
	 * @return object
	 */
	public function getSystemParams(){
		
		static $static = array();
		
		if(isset($static[$appName])) return $static[$appName] ;
		
        //Require
		$tble 	= TuiyoLoader::table( "userapps" );
		$model	= TuiyoLoader::model( "applications" , true );
			
		$user	= $GLOBALS['API']->get( "user" );
		
		//1. Load a single User Application
		
		$static[$appName]  = $model ;
		
	}
	
	/**
	 * TuiyoControllerApps::getUserParams()
	 * Gets the Parameters for the current application
	 * @param mixed $userID
	 * @return parameter object;
	 */
	public function getUserParams($userID = null){
		
		static $static = array();
		
		if(isset($static[$appName])) return $static[$appName] ;
		
        //Require
		$tble 	= TuiyoLoader::table( "userapps" );
		$model	= TuiyoLoader::model( "applications" , true );	
	}
	
	/**
	 * TuiyoControllerApps::add()
	 * Adds an application to a user account
	 * @return void
	 */
	public function add()
	{
		$tble 	= TuiyoLoader::table("userapps");	
		$post	= JRequest::get( 'post' );
		$user	= $GLOBALS['API']->get( "user" );
		$model	= $this->getModel( "applications" );
		$appName= strval($post['app']);
		
		//Check The user Token
		if(!JRequest::checkToken() || $user->id <> (int)$post['uid']){
			trigger_error("Invalid Token. Access Denied", E_USER_ERROR);
			return false;
		}
		
		//1a. Check that user does not already have App!
		//    and that there is actually an application with this id
		if(!$tble->userHasApp($appName, $user->id ) && $this->_appExists( $appName ) )
		{	
			//1b. Load the application
			$newApp = $model->getSingleApplication( $appName );
			//load an empty fields
			$tble->load( null ); 
			$tble->appName 		= $newApp->name ;
			$tble->appID 		= $newApp->id; 
			$tble->userID  		= $user->id ;
			$tble->hasTimeline 	= (int)$post['hasTimeline'];
			$tble->hasSearch 	= (int)$post['hasSearch'];
			$tble->hasProfile 	= (int)$post['hasProfile'];
			$tble->hasNotifications = (int)$post['hasNotifications'];	
				
			//2. Update settings
			if(empty($tble->appName)){ 
				trigger_error( _("Invalid Application Name. App Not added") , E_USER_ERROR ); 
					return false;
			}

			//3. Add the applicaton to the app Table
			if(!$tble->store()){
				trigger_error($tble->getError(), E_USER_ERROR );
				return false;
			}
			//4. Increment the application userCount;
			$tble->incrAppUserCount($newApp->id , 1 );		
			//4b. Publish Activity Story;
			$uActivity = TuiyoAPI::get("activity", null );
			$uStoryLine = sprintf( _('%1s added the application %2s to %3s profile'), "{*thisUser*}" , $newApp->title , "{*thisGSP1a*}" );
			$uActivity->publishOneLineStory( $user, $uStoryLine , $appName );
			
		}

		
		//5.Redirect to their new app!
		$redirectMessage = sprintf( _("The %s application has been added to your account. You could configure the application from the application control panel on your dashboard. Enjoy!" ) , $post['app'] );
		                 
		$redirectURL	 = JRoute::_(TUIYO_INDEX.'&amp;view=apps&amp;app='.$appName, false ) ;
		
		$this->setRedirect( $redirectURL , $redirectMessage , "notice"  );
		$this->redirect();
		
	}
	
	
	/**
	 * TuiyoControllerApps::remove()
	 * Removes an application from a user profile
	 * @return void
	 */
	public function remove()
	{
		$tble 	= TuiyoLoader::table("userapps");	
		$post	= JRequest::get( 'post' );
		$user	= $GLOBALS['API']->get( "user" );
		$model	= $this->getModel( "applications" );
		$appName= strval($post['app']);
		
		//Check The user Token
		if(!JRequest::checkToken() || $user->id <> (int)$post['uid']){
			trigger_error(_("Invalid Token. Access Denied"), E_USER_ERROR);
			return false;
		}
		
		//1a. Check that user does not already have App!
		//    and that there is actually an application with this id
		if($tble->userHasApp($appName, $user->id ) && $this->_appExists( $appName ) )
		{	
			if(!$tble->uninstallUserApp( $appName, $user->id )){
				trigger_error(_("Could not remove the application"), E_USER_ERROR );
				return false;
			}
			//Trigger after application unistall events
			TuiyoEventLoader::preparePlugins( "admin" );
			$GLOBALS["events"]->trigger( "onApplicationUnistall" , $appName  );
			
			//4. Increment the application userCount;
			$tble->decrAppUserCount($appName , 1 );
		}
		
		//5.Redirect to their new app!
		$redirectMessage = sprintf( _("The %s application has been un-installed from your account, and all data removed. ") , $appName );
		$redirectURL	 = JRoute::_( TUIYO_INDEX.'&view=profile&do=homepage' );
		
		$this->setRedirect( $redirectURL , $redirectMessage , "notice"  );
		$this->redirect();
	}
	

	/**
	 * Authorises a user to run the given controller!
	 * TuiyoControllerCore::authorise()
	 * 
	 * @return void
	 */
	public function authorise()
	{
		global $API;
		$user = $API->get( 'user' );
	}	

}