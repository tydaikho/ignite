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
 * TuiyoModelGroups
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelGroups extends JModel{
	
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
	 * TuiyoModelGroups::getPopularGroups()
	 * Proxy function to getGroups with popular sorting
	 * @return
	 */
	public function getPopularGroups(){		
		
		return $this->getGroups( NULL , NULL, "popular"); 
	}
	
	
	/**
	 * TuiyoModelGroups::getPopularGroupsAmongsFriends()
	 * Proxy method to getGroups with friends sorting
	 * @return void
	 */
	public function getPopularGroupsAmongsFriends(){
		

		
	}
	
	/**
	 * TuiyoModelGroups::getRecentGroups()
	 * Proxy function to getGroups with newest sorting
	 * @return void
	 */
	public function getRecentGroups()
	{
		return  array_reverse( $this->getGroups( NULL , NULL, "recent") ); 
	}
	
	/**
	 * TuiyoModelGroups::getUserGroups()
	 * Gets all user groups
	 * @param mixed $userID
	 * @return void
	 */
	public function getUserGroups( $userID, $categoryID = NULL){
		
		$gTable 	= TuiyoLoader::table("groups", true );

		$limitstart = $this->getState('limitstart');
		$limit 		= $this->getState('limit');
		
		$userID 	= !empty($userID) ? (int)$userID :  NULL ;
		$catID 		= !empty($categoryID) ? (int)$categoryID :  NULL ;
		
		$groups 	= $gTable->getUserGroups( $userID , $catID , true , $limitstart , $limit , "memberCount" );
		$dbo 		= $gTable->_db ;
		
		//Set the total count	
		$this->_total= $dbo->loadResult();
		$this->setState('total' , $this->_total );
		
		return $groups ;
	}
	
	
	public function getGroupLogo(){}
	
	public function setGroupLogo(){}
	
	/**
	 * TuiyoModelGroups::getGroups()
	 * Gets sorted groups data
	 * @param mixed $userID
	 * @param string $sort
	 * @return array of objects
	 */
	public function getGroups($userID = NULL , $categoryID = NULL, $sort = 'recent' ){
		
		$gTable 	= TuiyoLoader::table("groups", true );
		$gSort 		= array(
			"recent"	=>"dateCreated" , 
			"popular"	=>"memberCount" 
		);
		$limitstart = $this->getState('limitstart');
		$limit 		= $this->getState('limit');
		
		$userID 	= !empty($userID) ? (int)$userID :  NULL ;
		$catID 		= !empty($categoryID) ? (int)$categoryID :  NULL ;
		$sorter 	= in_array($sort, $gSort) ? (string)$gSort[$sort] : NULL ;
		
		$groups 	= $gTable->getGroups( $userID , $catID , true , $limitstart , $limit , $sorter );
		$dbo 		= $gTable->_db ;
		
		//Set the total count	
		$this->_total= $dbo->loadResult();
		$this->setState('total' , $this->_total );
		
		return $groups ;
	}
	
	/**
	 * TuiyoModelGroups::storeGroup()
	 * Update the group settings
	 * @param mixed $postData
	 * @param mixed $userID
	 * @return void
	 */
	public function storeGroup( $postData , $userID , $isNew = true ){
		
		$gTable 	= TuiyoLoader::table("groups", true);
		$validate 	= TuiyoAPI::get('validate');
		$auth		= TuiyoAPI::get('authentication');
		
		//Must be logged In
		$auth->requireAuthentication();
		$gTable->load( ($isNew) ? null : $postData['groupID'] );
		
		if(!$gTable->bind( $postData ) ){
			JError::raiseError(TUIYO_SERVER_ERROR, $gTable->getError());
			return false;
		}
		$gTable->groupID		= ($isNew) ? null : $gTable->groupID ;
		$gTable->creatorID 		= (int)$userID ;
		$gTable->isPublished 	= 1;
		$gTable->memberCount 	= 1;
		
		//Now Validate before saving
		if(!$gTable->store() ){
			JError::raiseError(TUIYO_SERVER_ERROR, $gTable->getError());
			return false;
		}
		
		//Increment Category Count
		if($isNew){
			$gcTable = TuiyoLoader::table('categories',  true );
			$gmTable = TuiyoLoader::table('groupsmembers', true );
		
			//Add new member to group;
			$gmTable->load( NULL );
			$gmTable->lastSeen	= date('Y-m-d H:i:s');
			$gmTable->privacy	= '{}';
			$gmTable->params 	= '{}';
			$gmTable->groupID 	= $gTable->groupID ;
			$gmTable->userID 	= (int)$userID ;
			if(!$gmTable->store()){
				$gTable->delete();
				JError::raiseError(TUIYO_SERVER_ERROR, $gmTable->getError());
				return false;
			}
			//increment user group
			//$gcTable->load( (int)$gTable->catID );
			//$gcTable->groupCount = $gcTable->groupCount+1;
			//if(!$gcTable->store() ){
				//$gmTable->delete();
				//$gTable->delete();
				//JError::RaiseError(TUIYO_SERVER_ERROR, $gcTable->getError());
				//return false;
			//}
		}
		return $gTable;
	}
	
	/**
	 * TuiyoModelGroups::joinGroup()
	 * Adds a new group Member
	 * @param mixed $groupID
	 * @return void
	 */
	public function joinGroup( $groupID ){
		
		$gTable 	= TuiyoLoader::table("groups", true);
		$gmTable 	= TuiyoLoader::table('groupsmembers', true );
		
		$validate 	= TuiyoAPI::get('validate');
		$auth		= TuiyoAPI::get("authentication" );
		
		//Must be logged IN
		$auth->requireAuthentication();
		//Esisting user OBject ;
		$user		= $GLOBALS['API']->get("user");
		

		
		if(!$gTable->load( (int)$groupID )){
			JError::raiseError(TUIYO_SERVER_ERROR , $gTable->getError());
			return false;
		}

		$gmTable->load( null );
		$gmTable->groupID = (int)$gTable->groupID ;
		$gmTable->userID  = (int)$user->id ;
		$gmTable->lastSeen	= date('Y-m-d H:i:s');
		$gmTable->privacy	= '{}';
		$gmTable->params 	= '{}';	
		
		if(!$gmTable->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, $gmTable->getError());
			return false;
		}
		
		$gTable->memberCount  = (int)$gTable->memberCount + 1 ;
		if(!$gTable->store()){
			$gmTable->delete();
			JError::raiseError(TUIYO_SERVER_ERROR, $gmTable->getError());
			return false;
		}					

		unset($gmTable);
		unset($gTable);
		
		return true ;
	}
	
	public function leaveGroup( $groupID , $memberID ){
		
		$gTable 	= TuiyoLoader::table("groups", true);
		$gmTable 	= TuiyoLoader::table('groupsmembers', true );
		
		$validate 	= TuiyoAPI::get('validate');
		$auth		= TuiyoAPI::get("authentication" );
				
		//Must be logged IN
		$auth->requireAuthentication();
		//Esisting user OBject ;
		$user		= $GLOBALS['API']->get("user");

		if(!$gTable->load( (int)$groupID )){
			JError::raiseError(TUIYO_SERVER_ERROR , $gTable->getError());
			return false;
		}
		if(!$gmTable->load( (int)$memberID )){
			JError::raiseError(TUIYO_SERVER_ERROR , $gmTable->getError());
			return false;
		}
		if($gmTable->userID <> $user->id ){
			JError::raiseError(TUIYO_SERVER_ERROR , _("Idenity mismatch. You can only unsuscribe yourslef"));
			return false;
		}
		//Now delete!
		if(!$gmTable->delete()){
			JError::raiseError(TUIYO_SERVER_ERROR , $gmTable->getError());
			return false;
		}
		
		//Les 1 member
		$gTable->memberCount = (int)$gTable->memberCount - 1 ;
		$gTable->memberCount = ($gTable->memberCount < 0) ? 0 : $gTable->memberCount ;
		$gTable->store();
				
		return true;
	}	
	
	/**
	 * TuiyoModelGroups::deleteGroup()
	 * Deletes user groups
	 * @param mixed $groupID
	 * @return
	 */
	public function deleteGroup( $groupID ){
		
		$gTable 	= TuiyoLoader::table("groups", true);
		$gmTable 	= TuiyoLoader::table('groupsmembers', true );
		
		$validate 	= TuiyoAPI::get('validate');
		$auth		= TuiyoAPI::get("authentication" );
				
		//Must be logged IN
		$auth->requireAuthentication();
		
		//Esisting user OBject ;
		$user		= $GLOBALS['API']->get("user");

		if(!$gTable->load( (int)$groupID )){
			JError::raiseError(TUIYO_SERVER_ERROR , $gTable->getError());
			return false;
		}
		if(!$gTable->delete()){
			JError::raiseError(TUIYO_SERVER_ERROR , $gTable->getError());
			return false;
		}
		$gmTable->deleteAllMembers( (int)$groupID );
		
		return true ;
	}	
	
	/**
	 * TuiyoModelGroups::getGroup()
	 * Gets a group from the databse
	 * @param mixed $groupID
	 * @param mixed $userID
	 * @return
	 */
	public function getGroup( $groupID ){
		
		$user	= TuiyoAPI::get( "user", NULL  );
		$gTable = TuiyoLoader::table("groups", true);
		$gData  = $gTable->loadSingleGroup( (int)$groupID );
		
		//1. Check that group Exists
		if(!is_object($gData) || !isset($gData->groupID) ||$gData->groupID < 1 ){
			return false;
		}
		//2. Is user Createor?
		$gData->isAdmin = ($gData->creatorID <> $user->id ) ? 0 : 1 ;
		
		return $gData ;
	}
	

	/**
	 * TuiyoModelGroups::__construct()
	 * Constructor and sets important variables
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
	 * TuiyoModelGroups::getCategories()
	 * @param bool $published
	 * @return
	 */
	public function getCategories( $published = TRUE, $sort = TRUE ){
		
		$MODEL 		= TuiyoLoader::model("categories", true);
		$catAssoc 	= $MODEL->getCategories();
		
		return $catAssoc;
	}
	
	
}