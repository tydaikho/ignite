<?php
/**
 * ******************************************************************
 * TimeLine controller for the tuiyo application                     *
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
 * joomla Controller
 */
jimport('joomla.application.component.controller');
/**
 * Tuiyo Controller
 */
TuiyoLoader::controller('core');

/**
 * TuiyoControllerTimeline
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerTimeline extends JController{
	
	/**
	 * 
	 * The Valid status types
	 * @var unknown_type
	 */
	var $validTypes = array( "status", "idea", "comment", "opinion", "activity", "compliment" , "question" );
	
 	/**
 	 * TuiyoControllerTimeline::__construct()
 	 * @return void
 	 */
 	public function __construct()
	 {
//		if (!JRequest::getCmd( 'view') ) {
//         	JRequest::setVar('view', 'profile');
//      	}
 		TuiyoControllerCore::init("Timeline", false);
		TuiyoEventLoader::preparePlugins("timeline" );
		parent::__construct();	
	}
	
	/**
	 * TuiyoControllerTimeline::getStatus()
	 * Gets and returns a status with specified ID
	 * @return void
	 */
	public function getStatus(){
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only post data") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		/** Check we have a valid token and Check we have a valid token***/
		if( $userID <> $user->id ) JError::raiseError( TUIYO_BAD_REQUEST, "Invalid user ID" );
		if( !JRequest::checkToken( "post" ) ) JError::raiseError(TUIYO_BAD_REQUEST, "Invalid token" );	
		
	}
	
	public function addVote(){
		
		// Check for request forgeries
		JRequest::checkToken( "request" ) or jexit( 'Invalid Token' );
		
		$auth 	 = TuiyoAPI::get( 'authentication' );		//Must be loggedIN
		$auth->requireAuthentication( 'post' );
		$user 	 = TuiyoAPI::get('user');
		
		$storyID = JRequest::getInt("sid", NULL , "post");
		$storyVT = JRequest::getInt("svt", 1, "post" ); //+1 for Like -1 for dislike
		$userID  = $user->id ;
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		
		if(empty($storyID) || empty($storyVT)){
			JError::raiseError(TUIYO_SERVER_ERROR, 'Invalid story ID or story Vote Type');
			return false;
		}
		
		if(!$model->storeVote($userID, $storyID, $storyVT)){
			JError::raiseError(TUIYO_SERVER_ERROR, 'Could not save the vote');
			return false;	
		}
		//After Add Timeline Vote
		$GLOBALS["events"]->trigger( "onAddTimelineVote" , $this );
		
		return $view->encode( array(
			"code" 	=> TUIYO_OK, 
			"userID" => $user->id, 
			"userPic" => TuiyoUser::getUserAvatar($user->id, "thumb35"), 
		));
		
	}
	
	public function removeVote(){
		
		// Check for request forgeries
		JRequest::checkToken( "request" ) or jexit( 'Invalid Token' );
		
		$auth 	 = TuiyoAPI::get( 'authentication' );		//Must be loggedIN
		$auth->requireAuthentication( 'post' );
		$user 	 = TuiyoAPI::get('user');
		
		$storyID = JRequest::getInt("sid", NULL , "post");
		$storyVT = JRequest::getInt("svt", 1, "post" ); //+1 for Like -1 for dislike
		$userID  = $user->id ;
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		
		if(empty($storyID) ){
			JError::raiseError(TUIYO_SERVER_ERROR, 'Invalid story ID or story Vote Type');
			return false;
		}
		
		if(!$model->deleteVote($userID, $storyID, $storyVT)){
			JError::raiseError(TUIYO_SERVER_ERROR, 'Could not save the vote');
			return false;	
		}
		
		return $view->encode( array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"voteT" => $storyID, 
		));
	}
	
	/**
	 * TuiyoControllerTimeline::requireAuthentication()
	 * @param mixed $type
	 * @param mixed $message
	 * @return void
	 */
	private function requireAuthentication($method, $message = NULL)
	{
		
		$SERVER	    = &JRequest::get( 'server' );
		$user 		= &$GLOBALS['API']->get("user" );
		$mainframe  = &$GLOBALS['mainframe'];

		//if user is guest		 
		if($user->joomla->get('guest')){
		    //Login the user?
		    if( !JRequest::checkToken( $method ) ){
				JError::raiseError(TUIYO_BAD_REQUEST, "Invalid token" );
			}
		    session_start() ;
		    //if( !JRequest::checkToken( $method ) ) jexit( 'Invalid Token' );
		    if(isset($SERVER['PHP_AUTH_USER']) && isset($SERVER['PHP_AUTH_PW']) && !isset($SERVER['FORCE_AUTH'])) 
			{
				//Mainframe authentication		
				$options = array();
				$options['remember'] 	= false;
				$options['return'] 		= '';
		
				$credentials = array();
				$credentials['username'] = JRequest::getVar('PHP_AUTH_USER', '', 'server', 'username');
				$credentials['password'] = JRequest::getString('PHP_AUTH_PW','','server', JREQUEST_ALLOWRAW);
		
				//preform the login action
				$error = $mainframe->login($credentials, $options);
				if(!JError::isError($error))
				{
					// Redirect if the return url is not registration or login
					$user = &$GLOBALS['API']->get("user" );
					JRequest::setVar("userID", $user->id );
					
					unset( $SERVER['FORCE_AUTH'] );
					
					return true;
					
				}else{
                    header('WWW-Authenticate: Basic realm="TuiyoTimeline"');
				    header('HTTP/1.0 401 Unauthorized');
				    
				    echo _('Authentication required for this method');
				    
				    jexit(0);
				}
			}
			//Push authentication header
			header('WWW-Authenticate: Basic realm="TuiyoTimeline"');
		    header('HTTP/1.0 401 Unauthorized');
		    
		    echo _('Authentication required for this method');
		    
		    jexit(0);
		    
		}else{
			return true;	
		}	
	}
	
	/**
	 * TuiyoControllerTimeline::setStatus()
	 * @return void
	 */
	public function setStatus(){	
		return $this->addTimelineRow();
	}
	
	public function setDiscussion(){
		return $this->addTimelineRow();
	}
	
	public function addTimelineRow( $type= "status" ){
		
		//Check we have an authenticated user
	 	$this->requireAuthentication( "post" );
			
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );

		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST , _("Invalid request. Method accepts only post data"));
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		
		/** Check we have a valid token and Check we have a valid token***/
		if( empty($user->id) || $user->joomla->get('guest') ){
			JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		}			
			
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
				
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}	
		$validTypes = array( "status", "idea", "comment", "opinion", "activity", "compliment" , "question" );
		
		if(isset($postData['type']) && in_array($postData['type'], $validTypes)){
			$type = $postData['type'];
		}
		
		//3. Model and store status
		$tData = $model->setStatus( $user->id  , $postData , $type );
		
		$GLOBALS["events"]->trigger( "onAddTimelinePost" , $tdata );
		
		$resp["data"]  = array( 
			"status"   => $tData->data,
			"statusID" => $tData->ID,
			"source"   => $tData->source,
			"username" => $user->username, 
			"likes"	   => $tData->likes,
			"dislikes" => $tData->dislikes,
			"isPublic" => $tData->isPublic,
			"time"	   => TuiyoTimer::diff( strtotime( $tData->datetime) )
		);
		//4. Get all other updates since last time!	
	
	
	
		//5. Return results
		$view->encode( $resp );		
	}
	
	/**
	 * TuiyoControllerTimeline::addComment()
	 * Adds a comment to an existing user Activity
	 * @return void
	 */
	public function addComment()
	{
		//
 		$this->requireAuthentication( "post" );
 		
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only post data"));
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		/** Check we have a valid token and Check we have a valid token***/
		if( empty($user->id) || $user->joomla->get('guest') ){
			JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		}			
			
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}		
		
		//3. Model and store status
		$tData = $model->setStatusComment( $user->id  , $postData );
		
		$GLOBALS["events"]->trigger( "onAddTimelineComment" , $this );
		
		$resp["data"]  = array( 
			"status"   => $tData->data,
			"statusID" => $tData->ID,
			"source"   => $tData->source,
			"username" => $user->username, 
			"time"	   => TuiyoTimer::diff( strtotime( $tData->datetime) )
		);
		//5. Return results
		$view->encode( $resp );					
	}
	
	/**
	 * TuiyoControllerTimeline::getComments()
	 * Gest an update of all comments to the specified ID
	 * @return void
	 */
	public function getComments()
	{
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only post data") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		/** Check we have a valid token and Check we have a valid token***/
		if( $userID <> $user->id ) JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		if( !JRequest::checkToken( "post" ) ) JError::raiseError(TUIYO_BAD_REQUEST, _("Invalid token") );			
	}
	
	/**
	 * TuiyoControllerTimeline::getTimeline()
	 * Gets a specified type of Timeline. JSON only!
	 * @return void
	 */
	public function getPublicTimeline( $filterType = null)
	{
		//1. Get Pre-requisites;
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only get data***/
		if( $method !== 'get' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only get request") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		
		$document 	= &$GLOBALS['API']->get("document" );
		$thisUser   = &$GLOBALS['API']->get("user", null );
		$get		= &JRequest::get('get');
		
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => "thisUser ".$thisUser->id, 
			"data" 	=> null, 
			"extra" => null
		);
		
		//3. JSON or nothing!		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}	
		
		if(isset($get['filtertype'])){ //&& in_array($get['filtertype'], $this->validTypes)
			$filterType = $get['filtertype'];
			//echo $get['filtertype'];
		}
		//4. Model to get User status
		$options	  = array(
			"filter"  => $filterType,
			"source" => JRequest::getVar("sourcetype" , null)
		);
		
		//On Timeline Load
		$GLOBALS["events"]->trigger( "onBeforeTimelineLoad" , $this);
		
		$resp["data"] = $model->getPublicTimeline( $thisUser->id, $options );
		
		//On Timeline Load
		$GLOBALS["events"]->trigger( "onAfterTimelineLoad" , $resp['data']);
		
		$resp["page"] = array( 
			"total"	  => (int)$model->getState('total'),
			"limit"	  => (int)$model->getState('limit'),
			"offset"  => (int)$model->getState('limitstart')
		);
		//5. Get all other updates since last time!	
	
		//6. Return results
		$view->encode( $resp );				
	}
	
	public function suggestParticipant()
	{
		//Must be logged In
		$auth 		= TuiyoAPI::get("authentication");
		$auth->requireAuthentication( "post" );
		
		$server 	= JRequest::get("server");		
		$userID		= JRequest::getInt("userID" ) ;
		$salt		= JRequest::getString("suggestSalt"  );

		$method 	= strtolower( $server['REQUEST_METHOD'] );
		//Get the view;		
		
		/** we are dealing with only get data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST , _("Invalid request. Method accepts only POST request") );
		
		$model		= &$this->getModel( "timeline");
		$view 		= &$this->getView("profile", "json");
		
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null,
			"data" 	=> null, 
			"extra" => null
		);
		
		$document 	= &$GLOBALS['API']->get("document" );
		$thisUser   = &$GLOBALS['API']->get("user", null );
		
		//3. JSON or nothing!		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}
		
		$resp["data"] = $model->getSuggestion( (string)$salt , $thisUser->id , 10 );
		
		return $view->encode( $resp );			
	}
	
	public function getGroupTimeline( $filterType= null )
	{
		//1. Get Pre-requisites;
		$server 	= JRequest::get("server");
		$userID		= JRequest::getInt("userID" ) ;
		$groupID	= JRequest::getInt("gid" ) ;
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only get data***/
		if( $method !== 'get' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only get request") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		
		$document 	= &$GLOBALS['API']->get("document" );
		$thisUser   = &$GLOBALS['API']->get("user", null );
		$get		= &JRequest::get('get');
		
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null,
			"data" 	=> null, 
			"extra" => null
		);
		
		//3. JSON or nothing!		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}		
		
		if(isset($get['filtertype'])){
			$filterType = $get['filtertype'];
		}
		
		$source = JRequest::getVar("sourcetype" , null);
		
		$resp["data"] = $model->getGroupTimeline( (int)$groupID , $thisUser->id ,  $filterType , $source );
		$resp["page"] = array( 
			"total"	  => (int)$model->getState('total'),
			"limit"	  => (int)$model->getState('limit'),
			"offset"  => (int)$model->getState('limitstart')
		);
		//5. Get all other updates since last time!	
	
		//6. Return results
		$view->encode( $resp );			
	}
  
	
	
    public function getUserTimeline( $filterType = null){
		
		//1. Get Pre-requisites;
		$server 	= JRequest::get("server");
		$get		= JRequest::get('get');
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		//Profile specific acitivity	
		$profileID	= JRequest::getInt("pid", null );
		$specific 	= JRequest::getInt("ps", 0 );
		$statusID 	= JRequest::getInt("sid", null );
		$profileID 	= (int)$profileID ;
		$specific	= (bool)$specific ;
		
		//Get the view;		
		
		/** we are dealing with only get data***/
		if( $method !== 'get' ) JError::raiseError( TUIYO_BAD_REQUEST , _("Invalid request. Method accepts only get request") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		
		$document 	= &$GLOBALS['API']->get("document" );
		$thisUser   = &$GLOBALS['API']->get("user", null );
		
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => "thisUser ".$thisUser->id, 
			"data" 	=> null, 
			"extra" => null
		);
		
		//3. JSON or nothing!		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}		
    	
		if(isset($get['filtertype'])){
			$filterType = $get['filtertype'];
		}
		//4. Model to get User status
		$options	  = array(
			"statusID"=>$statusID ,
			"filter"  =>$filterType,
			"source" => JRequest::getVar("sourcetype" , null)
		);
		//5. Get all other updates since last check!	
		$resp["data"] = $model->getUserTimeline($profileID, $thisUser->id, $options );
		$resp["page"] = array( 
			"total"	  => (int)$model->getState('total'),
			"limit"	  => (int)$model->getState('limit'),
			"offset"  => (int)$model->getState('limitstart')
		);		
		
		//6. Return results
		$view->encode( $resp );	
		    	
    }
	
	/**
	 * TuiyoControllerTimeline::delStatus()
	 * Deletes an activity requires specified permission
	 * @return void
	 */
	public function delStatus(){
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only post data") );
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		/** Check we have a valid token and Check we have a valid token***/
		if( $userID <> $user->id ) JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		if( !JRequest::checkToken( "post" ) ) JError::raiseError(TUIYO_BAD_REQUEST, _("Invalid token") );			
	}
	
	
	/**
	 * TuiyoControllerTimeline::delComment()
	 * Alias for deleting an comment, 
	 * which in fact is an activity in reply to
	 * @return
	 */
	public function delComment(){
		return $this->delActivity();
	}
	
	/**
	 * TuiyoControllerTimeline::delStatus()
	 * Deletes an activity comment requires specified permission
	 * @return void
	 */
 	public function delActivity()
	 {
 		$this->requireAuthentication( "post" );
 		
		//1. Get Pre-requisites;
		$postData  	= JRequest::get("post" );
		$server 	= JRequest::get("server");
		$userID		= intval( JRequest::getVar("userID" ) );
		$method 	= strtolower( $server['REQUEST_METHOD'] );
		
		/** we are dealing with only post data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST ,_("Invalid request. Method accepts only post data"));
		
		$model		= &$this->getModel("timeline" );
		$view 		= &$this->getView("profile", "json");
		$document 	= &$GLOBALS['API']->get("document" );
		$user 		= &$GLOBALS['API']->get("user" );
		
		/** Check we have a valid token and Check we have a valid token***/
		if( empty($user->id) || $user->joomla->get('guest') ){
			JError::raiseError( TUIYO_BAD_REQUEST, _("Invalid user ID") );
		}			
			
		//2. prepare a standard response Array
  		$resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> null, 
			"extra" => null
		);
		
		if($document->getDOCTYPE() !== "json"){
			$resp["code"] 	= TUIYO_BAD_REQUEST;
			$resp["error"]	= _("Invalid Request format. JSON only");
			//dump
			$view->encode( $resp );
			return false;	
		}
		
		$resp["data"] = $model->deleteActivity( $user->id, $postData['id'] );
		//5. Get all other updates since last time!	
	
		//6. Return results
		$view->encode( $resp );				
 	}
}