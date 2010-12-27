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
 * joomla CModel
 */
jimport( 'joomla.application.component.model' );
/**
 * users uploads
 */
TuiyoLoader::import("user.uploads");

/**
 * TuiyoModelResources
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelResources extends JModel{

    /**
     * Total number of items
     * @var integer
     */
    public $_total = null;

    /**
     * The Pagination object
     * @var object
     */
    public $_pagination = null;
		
	/**
	 * Gets the session data to re-establish
	 * Who is uploading the file
	 * 
	 * TuiyoModelResources::getSessionData()
	 * 
	 * @param mixed $sessionID
	 * @return void
	 */
	public function getSessionData( $sessionID = null ){
		
		$session	= JFactory::getSession( );
 		$token 		= JRequest::getVar( 'PHPSESSID' );
 		
 		$dbo 		=&JFactory::getDBO(); 
 		$query 		= "SELECT * FROM #__session"
 					. "\nWHERE session_id = ".$dbo->Quote( $token )
		  			;
 					
		$dbo->setQuery( $query );
 		$sData = $dbo->loadAssocList();
 		
 		return $sData;
	}
	
	/**
	 * TuiyoModelResources::getUsersLike()
	 * 
	 * @param mixed $salt
	 * @param mixed $userID
	 * @param integer $limit
	 * @return
	 */
	public function getUsersLike( $salt , $userID , $limit =10 ){
		
		$rTable = TuiyoLoader::table("resources");
		
		//Search for Users only. Not groups
		return $rTable->suggestResource( (string)$salt, (int)$userID, (int)$limit , false );
		
	}
	
	/**
	 * Gets the real path of a resource ID
	 * TuiyoModelResources::getFilePath()
	 * 
	 * @param mixed $fileID
	 * @return
	 */
	public function getFilePath( $fileID ){
		
		$dbo		=& JFactory::getDBO();
		$query		= "SELECT Concat( s.filePath , s.fileName )"
					. "\nFROM #__tuiyo_resources s"
					. "\nWHERE s.resourceID =".$dbo->Quote( (int)$fileID )
					;
		$dbo->setQuery( $query  );
		
		$return = $dbo->loadResult();
		//Return true or false
		return $return;
	}
	
	/**
	 * Loads all user uploaded resources
	 * 
	 * TuiyoModelResources::getMyResources()
	 * 
	 * @param mixed $userID
	 * @param mixed $type : AVATAR || IMAGE || AUDIO || VIDEO.
	 * @return
	 */
	public function getMyResources( $userID , $type = null ){
		
		$dbo 		=&	JFactory::getDBO();
		$type		= 	strtoupper( trim( $type ) );
		$where		= 	" s.userID =".$dbo->Quote( (int)$userID );
		$where		= 	(empty($type))? $where : $where." AND s.contentType =".$dbo->Quote( $type );
										
		$query 		=	"SELECT s.resourceID as id, s.dateAdded as date, 
						s.size as size, s.contentType as type, 
						s.fileName as name, s.fileTitle as title, s.url as uri " 
					.   "\nFROM #__tuiyo_resources s"
					.	"\nWHERE $where"
					;
		//Set the Qeury
		$dbo->setQuery( $query );
		$rData		= $dbo->loadAssocList();
		
		//return
		return $rData;
	}
	
	/**
	 * Checks if a user can delete a file!
	 * 
	 * TuiyoModelResources::userCanDelete()
	 * 
	 * @param mixed $userID
	 * @param mixed $fid
	 * @return boolean true on canDelete
	 */
	public function userCanDelete( $userID, $fid ){
		
		$dbo		=& JFactory::getDbo();
		$query		= "SELECT COUNT(1) "
					. "\nFROM #__tuiyo_resources s"
					. "\nWHERE s.resourceID =".$dbo->Quote( (int)$fid )
					. "\nAND s.userID =".$dbo->Quote( (int)$userID )
					;
		$dbo->setQuery( $query  );
		
		$return = (bool)$dbo->loadResult();

		//Return true or false
		return $return;
	}
	
	/**
	 * TuiyoModelResources::rename()
	 * Renames a resource in the database
	 * @param mixed $fileID
	 * @param mixed $postData
	 * @return
	 */
	public function rename($fileID, $postData){
		
		//Load the resources table
		$userData 	   =& JFactory::getUser();
    	$resourceTable =& TuiyoLoader::table("resources", true);
    	
    	$resourceTable->load( (int)$fileID  );
    	
    	if($userData->id <> $resourceTable->userID ){
    		JError::raiseError(TUIYO_UNAUTHORISED, _("You do not have permission to change this resource") );
    		return false;
    	}
    	//Rename the files
    	$resourceTable->fileTitle = (isset($postData['newTitle'])) ? strval( $postData['newTitle']) : $resourceTable->fileTitle;
    	
    	//Update the resource
    	if(!$resourceTable->store()){
    		JError::raiseError(TUIYO_SERVER_ERROR, _("An error occured whilst saving the resource") );
    		return false;
    	}
    	
    	return true;
	}
	
	/**
	 * TuiyoModelResources::getOnlineUsers()
	 * Generates a detailed array list of all users presently online
	 * @return ONLINE FRIENDS ONLY!!
	 */
	public function getOnlineUsers()
	{
		$userAPI 	   =& TuiyoAPI::get("user" , null );
		$usersTable    =& TuiyoLoader::table("users" , true );
		$friendsModel  =& TuiyoLoader::model("friends" , true );


		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart' );
		
		$usersList 		=& $usersTable->getAllOnlineUsers(TRUE, FALSE, $limitstart , $limit );
		
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $usersTable->_db ;
		$this->_total		= $dbo->loadResult();
		
		$pageNav 			= new JPagination( $this->_total, $limitstart, $limit );
		
		$this->pageNav 		= $pageNav->getPagesLinks();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		$this->setState('pagination' ,  $this->pageNav );
		
		//Its important that this be called after pagination is processed!		
		$myFriendsIds	= (array)$friendsModel->generateIDs( $userAPI->id );
		
		//add Avatar object
		foreach((array)$usersList as $key=>$user ): 
			
			if(!in_array($user->id , $myFriendsIds) || $user->id === $userAPI->id ){ //If not a friend or is me?
				unset( $usersList[$key] ) ;
				continue;
			};	
			$user->avatar 		= &TuiyoUser::getUserAvatar( $user->id );

		endforeach;
		
		return $usersList ;		
	}

    /**
     * Gets all the newest users from the DB
     * @param unknown_type $limit
     */
    public function getNewestUsers( $limit=30 ){
    	
    	$uTable 	= TuiyoLoader::table("users",true);
    	$fUsers 	= $uTable->getFeaturedUsers( $limit, true, false);
    	
    	foreach((array)$fUsers as $key=>$user ): 
				
			$user->avatar 		= &TuiyoUser::getUserAvatar( $user->id );

		endforeach;
    	
    	return $fUsers;
    	
    }
	
	/**
	 * TuiyoModelResources::getAllMembers()
	 * Gets a lists of all members
	 * @param mixed $filterOptions
	 * @return void
	 */
	public function getAllMembers($filterOptions = array()){
		
		$userAPI 		=& TuiyoAPI::get("user" , null );
		$usersTable 	=& TuiyoLoader::table("users" , true );
		$friendsModel 	=& TuiyoLoader::model("friends", true );
		
		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart' );
		
		$usersList 		=& $usersTable->getAllUsers(TRUE, FALSE, $limitstart , $limit );
		
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $usersTable->_db ;
		$this->_total		= $dbo->loadResult();
		
		$pageNav 			= new JPagination( $this->_total, $limitstart, $limit );
		
		$this->pageNav 		= $pageNav->getPagesLinks();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		$this->setState('pagination' ,  $this->pageNav );
		
		//Its important that this be called after pagination is processed!		
		$myFriendsIds	= (array)$friendsModel->generateIDs( $userAPI->id );
		
		//add Avatar object
		foreach((array)$usersList as $user ): 
			
			$user->isUserFriend = (!in_array($user->id , $myFriendsIds)&& $user->id <> $userAPI->id ) ? FALSE : TRUE ;	
			$user->avatar 		= &TuiyoUser::getUserAvatar( $user->id );

		endforeach;
		
		return $usersList ;
		
	}
	
	/**
	 * TuiyoModelResources::__construct()
	 * Model Consstructor
	 * @return void
	 */
	public function __construct()
	{
	    parent::__construct();
	
	    global $mainframe, $option;
	
	    // Get pagination request variables
	    $limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	    $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
	
	    // In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
	    $this->setState('limit', $limit ); //$limit
	    $this->setState('limitstart', $limitstart);
	    
	    $this->pageNav  = NULL ;
	}	
	
	/**
	 * TuiyoModelResources::delete()
	 * 
	 * @param mixed $userID
	 * @param mixed $fileIDs
	 * @return
	 */
	public function delete( $fileID ){
		
		//Load the resources table
    	$resourceTable =& TuiyoLoader::table("resources", true);
    	$resourceTable->load( (int)$fileID  );
    	$resourceTableOld = clone $resourceTable ;

    	//Delete the file from the server
    	if(isset($resourceTable->filePath)){
    		$fileSource = $resourceTable->filePath.$resourceTable->fileName ;
			//joomla !!
    		jimport('joomla.filesystem.file');
    		jimport('joomla.filesystem.path');
    		
    		JPath::setPermissions( $fileSource , 0777 , 0777 );
    		
			//now attempt to delete the database entry!
			if(!$resourceTable->delete()){
				trigger_error(sprintf(_("Could not remove FILE:%s from DB"), $resourceTable->resourceID ), E_USER_ERROR );
				return false;
			}  
			
			//echo $photosTableOld->contentType;
			
			if($resourceTableOld->contentType == "PHOTOS"){
				$photosTable = &TuiyoLoader::table("photos" , true );
				$photosTable->deleteItem( (int)$resourceTableOld->resourceID );
			}  		
			
			if(!JFile::delete( $fileSource )){
    			trigger_error( sprintf(_("Could not delete %s" ), $resourceTableOld->fileName ), E_USER_ERROR );
    			return false;
    		}	
    	}
    	
    	unset($resourceTableOld );
    	unset($resourceTable);
    	
		return true;
	}
} 