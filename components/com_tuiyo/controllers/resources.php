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
 * joomla Controller
 */
jimport('joomla.application.component.controller');


/**
 * TuiyoControllerResources
 * No Authentication Required!!
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoControllerResources extends JController{
	
	/**
	 * Stores the session Data
	 * see {TuiyoControllerResources::_reEstablishSession()}
	 * */
	var $sData 	= array();
	
	/**
	 * Maximum file name size
	 */
	var $maxFileNameLength = 260;
	
	/**
	 * This is simply because Joomla does not 
	 * Allow you to have a controller without a view
	 * Unless it sets a redirect!
	 * 
	 * TuiyoControllerResources::__constructor()
	 * 
	 * @return void
	 */
	public function __construct(){
		//Set the View Intricately
		if (!JRequest::getCmd( 'view') ) {
         	JRequest::setVar('view', 'profile' );
      	}
      	//Construct the parent now
		parent::__construct();
	}
	
	/**
	 * TuiyoControllerResources::inc()
	 * @param string $identifier
	 * @param string $type
	 * @param string $size
	 * @return
	 */
	public function inc($identifier='', $type='', $size=''){
		
		$itemStore = array();
		$resource  = &$this;
		
		if( !($resource instanceof self) ){
			$resource  = self::getInstance();
		}
		//Tuiyo TakeOver
		JRequest::setVar('format', 'stream');
		JRequest::setVar('tmpl', 'component');
		
		$data 		= JRequest::getVar("data" , null );
  		$segments 	= explode('.', trim($data) );
        $count 		= sizeof( $segments );
		
		//Methods
		$validMethods = array(
			"avatar"  => "_getAvatar",
			"audio"	  => "_getAudio",
			"doc"	  => "_getDocument",
		);
		
		if($count > 0 ){      
			if(array_key_exists($segments[0], $validMethods)){	
				$type  = $segments[0];
				$args  = $segments ;			
				//Get the file and return it;
				call_user_method($validMethods[$type] , $resource , $args );
			}
		}
		jexit(0);
	}
	/**
	 * Uploader Form
	 * Enter description here ...
	 */
	public function getUploader(){
		
		$auth 	 	= TuiyoAPI::get( 'authentication' );		//Must be loggedIN
		$auth->requireAuthentication( 'post' );
		
		$user 	 	= TuiyoAPI::get('user');
		$jView 		= $this->getView('profile', "html");
		
		$jView->assignRef("user", $user);
		$jView->uploader();
		
	}
	
	/**
	 * Simple Method to return XML doc from feed URL
	 * TuiyoControllerResources::getFeedXML()
	 * @return void
	 */
	public function getFeedXML( ){
		
		
		$feedURL 	= JRequest::getVar("url" );
		$xmlView 	= $this->getView("profile" , "xml");
		$xmlData 	= TuiyoAPI::getUrl( $feedURL  );
		
		//Returns an XML file
		$xmlView->returnX( $xmlData );
			
		jexit(0);
	}
	
	/**
	 * TuiyoControllerResources::_getAvatar()
	 * @param mixed $args
	 * @return void
	 */
	private function _getAvatar($args =null){
		
		$view  =& $this->getView("profile", "stream");
	    $sizes = array(
	    	"square" =>array("w"=>70, "h"=>70),
	    	"profile"=>array("w"=>202, "h"=>202)
		);
		
		//Verify Identity and permission of requesting user
		
		$params["method"] = (int)$args[0];
		$params["userID"] = ( !isset( $args[1] ) ) ? null : (int)$args[1];
		$params["size"]   = ( !isset( $args[2] ) || !array_key_exists($args[2], $sizes) ) ? "square" : $args[2] ;
		
		$user  = TuiyoAPI::get("user", null );
		$pic   = $user->getProfilePicture( $params["userID"] );
	    $psize = strtolower( strval( $params["size"] ) );
	    
		$imgData = array(
			"s" => $pic,
			"w" => $sizes[$psize]["w"],
			"h" => $sizes[$psize]["h"],
			"q" => 85
		);
		return $view->returnI( $imgData );
	}
	
	/**
	 * TuiyoControllerResources::members()
	 * 
	 * Gets a list of all members of a site;
	 * @return
	 */
	public function members(){
		
		$auth	= TuiyoAPI::get('authentication');
		$user 	= TuiyoAPI::get('user', null);
		
		$view 	= $this->getView("profile" , "html" );
		$model  = $this->getModel("resources");
		
		$system = $GLOBALS['mainframe'];
		
		//$auth->requireAuthentication();
		
		$members= $model->getAllMembers( ); 
		$pages 	= $model->getState( 'pagination' );
		
		$view->assignRef( "user" ,  $user);
		$view->assignRef( "pagination" , $pages );
		
		$system->setPageTitle( _("Members Lists") );
		$system->getPathway()->addItem( _("Members")  );
		
		return $view->displayMembersList( $members );
		
	}
	
	/**
	 * TuiyoControllerResources::getOnlineMembers()
	 * Returns a list of all online mements
	 * @return void
	 */
	public function getOnlineMembers(){
	//Get JSON view
  		$view 	= $this->getView("profile", "json");
  		$model 	= $this->getModel( "resources" );
	  	$olUsers= $model->getOnlineUsers();
	  	
	  	
	  	//AssignRef
	  	$view->assignRef( "onlineusers" , $olUsers );
	  	
	  	//Prepare JSON
        $resp 	= array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> $olUsers,
			"html"	=> $view->onlineMembersHTML() 
		);
		
		$view->encode( $resp );		
	}
	
	/**
	 * TuiyoControllerResources::_getDocument()
	 * 
	 * @param mixed $args
	 * @return void
	 */
	private function _getDocument( $args = null){
		//Verify Identity and permission of requesting user
		$view =& $this->getView("profile", "stream");
		$docData = array(
			"s" => TUIYO_FILES.DS."avatars".DS."62".DS."62_74_673938001_bbdc3847fb_o.jpg",
			"w" => 202,
			"h" => 202,
			"q" => 85
		);
		return $view->returnD( $docData );
	}
	
	/**
	 * TuiyoControllerResources::getInstance()
	 * 
	 * @param bool $ifNotExist
	 * @return
	 */
	private function getInstance($ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoControllerResources::getInstance(  $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoControllerResources()	;	
		}
		return $instance;	 
	 }
	 
	/**
	 * Saves the newly uploaded resources
	 * TuiyoControllerResources::uploadResource()
	 * 
	 * @param mixed $fileData
	 * @return
	 */
	public function uploadResources( $fileData = null ){
		
		//echo "here"; die;
		
		//Get JSON view
  		$view = $this->getView("profile", "json");
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> _("file uploaded"), 
		);
		//Get Session Information
		$model 	=	$this->getModel( "resources" );
		$sData	= 	"";
		$fType	=   JRequest::getVar("resourceType", null);
		$user	= 	TuiyoAPI::get( "user" , null);
		
		//Check we know who we are dealing with
		$user	= $GLOBALS["API"]->get("user", null);
		
		if($user->joomla->get("guest")){
			
			$jsid 		=	JRequest::getVar("jsid");
			$jsname 	=	JRequest::getVar("jsname");

			$store  	= &JSessionStorage::getInstance('database'); 
			$sdata 		= $store->read( $jsid );
			
			$session =& JFactory::getSession();
			$session->destroy();
			
			session_id( $jsid );
			session_decode( $sdata );
			session_start();
			
			$session->restart();
			
			$user	= $GLOBALS["API"]->get("user", null);
		
			//if the user is still a guess, raise the error
			if($user->joomla->get('guest')){
				trigger_error( _("unable to determine the user session") , E_USER_ERROR );
				return false;
			}
		}
		
		if(empty($fType)){
			$fileExtension = pathinfo( $_FILES["Filedata"]['name'] , PATHINFO_EXTENSION );
			switch ( strtolower( $fileExtension) ):
	              case "gif": 
	              case "jpg":
	              case "jpeg":
	              case "png": $fType = "photos";	break;
	              case "mp3": $fType = "audio"; 	break;
            endswitch;
			if(empty($fType)){
				trigger_error(_('Invalid file type'), E_USER_ERROR);
				return false;
			}
		}
		
		//print_R($user); die;
		
		//Get the resources class
		TuiyoLoader::import("user.uploads");
		
		$uploads 	  = new TuiyoUploads( $fType );
		
		if(!$uploads->saveItem($_FILES["Filedata"], $sData)){
			trigger_error($uploads->getErrors(), E_USER_ERROR);
			return false;
		}
		$resp["data"]  = $uploads->getLastUploaded();
		
		//return response
		return $view->encode( $resp );
	}
	
	/**
	 * TuiyoControllerResources::getEmbedable()
	 * Gets embedable content from external sites
	 * @param mixed $fileData
	 * @return
	 */
	public function getEmbedable( $returnJSON = FALSE ){
		//Get JSON view
		$view 		= $this->getView("profile", "json");
		
		$provider 	= JRequest::getVar("provider", null );
		$rURL		= JRequest::getVar("url" , null );
		$callback 	= JRequest::getVar("callback","callback");
		$provider 	= strtolower( $provider );
		$providers  = array(
			"polldady"		=> "http://polldaddy.com/oembed",
			"opera"			=> "http://my.opera.com/service/oembed",
			"polleverywhere"=> "http://www.polleverywhere.com/services/oembed/",
			"clearspring"	=> "http://widgets.clearspring.com/widget/v1/oembed/",
			"emberapp"		=> "http://emberapp.com/services/oembed",
			"virb"			=> "http://virb.com/services/oembed/1.0/request"
        );
        
        if(empty($provider) || !array_key_exists($provider , $providers ) || empty($rURL)){
        	JError::raiseError(TUIYO_SERVER_ERROR, "Invalid Provider");
        	return false;
        }
        
        $requestURL = $providers[$provider]."?format=json&url=".urlencode( $rURL ) ;
        
        $content 	= TuiyoAPI::getURL( $requestURL );
        $isJSON 	= @json_decode( $content , true );
        
        //Providers needing more processing
        $badProviders = array("emberapp", "clearspring", "polleverywhere" ); //,"polldady"
        
        if( in_array($provider , $badProviders) ) :
        	switch($provider) :
        		case "polleverywhere":
        			$isJSON['iframe_src'] = $rURL ;
        			$content = json_encode( $isJSON );
        		break;
        		case "emberapp":
        			$content = json_encode( $isJSON["oembed"] );
        		break;
        		case "clearspring":
	        		echo "$callback(".$content .")"; 
   					jexit(0);
        		break;
        	endswitch;
        endif;
        
        //If its an internal call
        if($returnJSON){
        	return $isJSON;
        }
        
        if(is_array($isJSON)){
        	echo "$callback(".$content .")"; 
   			jexit(0);
        }else{
			echo "$callback(".json_encode(array(            
						"version" => 1.0,
			            "type" => "photo",
			            "width" => 270,
			            "height" => 330,
			            "title" => "Click here to visit",
			            "url" => "components/com_tuiyo/files/timeline-link-error.png", //$rURL ,
			            "author_url"  => JRoute::_( TUIYO_INDEX ),
			            "provider_name"  => $provider ,
			            "provider_url"  => JRoute::_( TUIYO_INDEX ),
			            "thumbnail_url"  => "components/com_tuiyo/files/timeline-link-error.png",
			            "thumbnail_width"  => 270,
			            "thumbnail_height"  => 330
			)).")"; 
   			jexit(0);
        }
    	JError::raiseError(TUIYO_SERVER_ERROR, "Could not get feed");
    	return false;        
	}
	
	/**
	 * TuiyoControllerResources::changeTitle()
	 * Renames a file on the resource table.
	 * @return
	 */
	public function changeTitle(){
		
		$auth		= $GLOBALS['API']->get("authentication");
		$viewJSON 	= $this->getView("profile", "json");
		$model		= $this->getModel( "resources" );
		$postData 	= JRequest::get('post');
		
		//Resp DATA
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> _("files title changed"), 
		);
		
		//Force Authentication
		$auth->requireAuthentication();
		$model->rename( (int)$postData['id'], $postData );
			
		//view JSON	
		return $viewJSON->encode( $resp );
	}
	
	/**
	 * TuiyoControllerResources::getSessionId()
	 * 
	 * @return
	 */
	public function getSessionId(){
		
		$session 	= &JFactory::getSession();
		$sessionID 	= (!JRequest::checkToken("request"))? array() : array( 
			"sid"	=>	$session->getId(),
			"sname"	=>	$session->getName(),
			"psid"	=> 	session_id()
		);
		//Post Params
		$sessionID["post"] = array( 
			"PHPSESSID"			=> $sessionID["psid"],
			$sessionID["sname"] => $sessionID["sid"]	
		); 
		
		$viewJSON 	= $this->getView("profile", "json");
	
		return $viewJSON->encode( $sessionID );
	}
	
	/**
	 * TuiyoControllerResources::display()
	 * @return null
	 */
	public function display(){
		return null;
	}
	
	
	/**
	 * Deletes resources
	 * TuiyoControllerResources::deleteResources()
	 * 
	 * @param mixed $ids
	 * @return void
	 */
	public function deleteResources( $ids = null ){
		//Get JSON view
  		$view = $this->getView("profile", "json");
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> _("files deleted"), 
		);
		$model 	=	$this->getModel( "resources" );
		$user	= 	TuiyoAPI::get( "user" , null);
		$fids	= 	JRequest::getVar( "fid" );
		
		//check that user can edit?
		if(!JRequest::checkToken()){
			trigger_error("Invalid User Token" , TUIYO_SERVER_ERROR );
			return false;
		}
		
		//Now delete every single file!
		//TODO Remember to delete photo from Album, including thumbs, if its avatar;
		
		foreach($fids as $key=>$fid){
			if($model->userCanDelete( $user->id, $fid )) {
				//Send to Model
				if(!$model->delete( $fid )){
					trigger_error("Could not delete files" , E_USER_ERROR );
					return false;
				}
				$resp["extra"][]	= $fid;
			}
		}
		$view->encode( $resp );	
	}
	
	/**
	 * Packages and downloads resources
	 * TuiyoControllerResources::downloadResources()
	 * 
	 * @return void
	 */
	public function downloadResources(){
		
		//Get JSON view
  		$view = $this->getView("profile", "json");
        $resp = array(
			"code" 	=> TUIYO_OK, 
			"error" => null, 
			"data" 	=> "", 
		);
		
		//Get Session Information
		$model 	=	$this->getModel( "resources" );
		$sData	= 	"";//$model->getSessionData( );
		$user	= 	TuiyoAPI::get( "user" , null);
		$fids	= 	JRequest::getVar( "fid" );
		
		//Get the resources class
		TuiyoLoader::import("user.uploads");
		
		$myFiles = new TuiyoUploads( "archive" );
		$queue 	 = array();
		
		foreach($fids as $key=>$fid ){
			if($model->userCanDelete( $user->id, $fid )) {
				$queue[] = $model->getFilePath( $fid );
			}
		}
		
		$resourceLink  = $myFiles->archiveFiles($user->id, $queue );
		$resp["extra"] = $resourceLink; 
			//1. If user owns files add to archive queue;
			//2. Archives the files
			//3. Output response with download link saved in cache!
			//4. Download file from cache!
		
		return $view->encode( $resp );
	}
	
	
	/**
	 * TuiyoControllerResources::getUserNamesLike()
	 * Method for providing autocomplete list by UserName
	 * @return void
	 */
	public function getUserNamesLike()
	{	
		//Must be logged In
		$auth 	 	= TuiyoAPI::get('authentication');
		
		$auth->requireAuthentication( "post" );
		
		$server 	= JRequest::get("server");		
		$userID		= JRequest::getInt("userID" ) ;
		$salt		= JRequest::getString("suggestSalt"  );

		$method 	= strtolower( $server['REQUEST_METHOD'] );
		//Get the view;		
		
		/** we are dealing with only get data***/
		if( $method !== 'post' ) JError::raiseError( TUIYO_BAD_REQUEST , _("Invalid request. Method accepts only POST request") );
		
		$model		= &$this->getModel( "resources");
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
		
		$resp["data"] = $model->getUsersLike( (string)$salt , $thisUser->id , 10 );
		
		return $view->encode( $resp );	
	}
	
	/**
	 * TuiyoControllerResources::_response()
	 * @param mixed $JSON
	 * @return void
	 */
	private function _response( $arrayToJSON )
	{}
	
}