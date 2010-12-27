<?php
/**
 * ******************************************************************
 * TuiyoTableGroups Class/Object for the Tuiyo platform             *
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

defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * TuiyoTableGroups
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableGroups extends JTable
{
	//`groupID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $groupID 			= null;
	//`creatorID` INTEGER UNSIGNED NOT NULL,
	var $creatorID			= null;
	//`gType` VARCHAR(45) NOT NULL,
	var $gType				= null;
	//`longDescription` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
	var $longDescription 	= null;
	//`shortDescription` TEXT(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	var $shortDescription 	= null;
	//`gName` VARCHAR(45) NOT NULL,
	var $gName				= null;
	//`dateCreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $dateCreated		= null;
	//`topicCount` INTEGER UNSIGNED NOT NULL DEFAULT 0,
	var $topicCount			= null;				
	//`memberCount` INTEGER UNSIGNED NOT NULL DEFAULT 0,
	var $memberCount		= null;
	//`banner` VARCHAR(200),
	var $banner				= null;
	//`lastUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIME,
	var $lastUpdated		= null;
	//`logo` VARCHAR(200),
	var $logo				= null;
	//`webpageURl
	var $webpageURL			= null;
	//`isPublished
	var $isPublished		= null;
	//`params
	var $params				= null;
	//catID
	var $catID				= null;

    /**
     * TuiyoTableGroups::__construct()
     * @param mixed $db
     * @return void
     */
    public function __construct($db = null)
    {
        parent::__construct("#__tuiyo_groups","groupID", $db);
    }
    
    /**
     * TuiyoTableGroups::deleteGroup()
     * Deletes a group from the user table
     * @param mixed $groupID
     * @return void
     */
    public function deleteGroup( $groupID = NULL){}
    
    /**
     * TuiyoTableGroups::updateMemberCount()
     * Update member count
     * @param mixed $groupID
     * @return void
     */
    public function updateMemberCount( $groupID = NULL ){}
    
    
    /**
     * TuiyoTableGroups::getGroups()
     * Gets all groups from the group database table
     * @param mixed $userID (optional) creator ID
     * @param mixed $catID (optional) category ID
     * @param bool $isPublic (optional) published, default is true
     * @param integer $limitstart 
     * @param integer $limit
     * @return array of group objects
     */
    public function getGroups($creatorID = NULL, $catID=NULL, $isPublic = true , $limitstart=0, $limit = 20, $order=''){
    	
    	$privacy 	= TuiyoAPI::get('privacy' , null );
    	$user		= TuiyoAPI::get('user' , null );
    	
    	//Vars
    	$dbo 		= $this->_db ;
    	$isPublic 	= (!$isPublic) ? $dbo->quote(0) : $dbo->quote(1) ;
    	$creatorID	= (empty($creatorID) ) ? null: "\nAND g.creatorID=".$dbo->Quote( (int)$creatorID );
    	$orderCond 	= (empty($order)) ? "\nORDER BY g.memberCount DESC" : "\nORDER BY g.".$order." ASC"  ;
    	$categoryID	= (empty($catID)) ? null: "\nAND g.catID=".$dbo->Quote((int)$catID );
    	
    	//Specify and execute query
    	$query 		= "SELECT SQL_CALC_FOUND_ROWS g.*, c.* , 0 as isAdmin, 0 as canJoin, 0 as canShare"
    				. "\nFROM #__tuiyo_groups AS g "
    				. "\nLEFT JOIN #__tuiyo_categories AS c ON g.catID = c.id "
    				. "\nWHERE g.isPublished=".$isPublic
    				. $creatorID
    				. $categoryID
    				. $orderCond
		;
		
		$dbo->setQuery( $query, $limitstart, $limit );
		
		$rows 		= $dbo->loadObjectList( );
		$groups 	= array();
		
		$dbo->setQuery('SELECT FOUND_ROWS();'); 
		
		//Generate response
		foreach($rows as $group ):
			
			$pathThumbLogo 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  ,  $group->logo );
			$group->logo 		= (!empty($pathThumbLogo) && file_exists($pathThumbLogo) && is_file( $pathThumbLogo ) ) ? $group->logo : TUIYO_GROUP_LOGO;
			
			$groups[]	 = $group ;
			
		endforeach;
		
		return (array)$groups;
    }
    
    /**
     * TuiyoTableGroups::getUserGroups()
     * 
     * @param mixed $userID
     * @param mixed $catID
     * @param bool $isPublic
     * @param integer $limitstart
     * @param integer $limit
     * @param string $order
     * @return void
     */
    public function getUserGroups($userID, $catID=NULL, $isPublic = true , $limitstart=0, $limit = 20, $order=''){
 	 	
  		$dbo 	= $this->_db;
    	$query 	= "SELECT g.*, c.id as catId, c.title as Name
					   FROM #__tuiyo_groups_members AS m"
	   			. "\nINNER JOIN #__tuiyo_groups AS g"
	   			. "\nON m.groupID = g.groupID"
    			. "\nLEFT JOIN #__tuiyo_categories AS c"
   			   	. "\nON g.catID = c.id"
    			. "\nWHERE m.userID =".$dbo->quote( (int)$userID )
    			;
    			
    	$isPublic 	= (!$isPublic) ? $dbo->quote(0) : $dbo->quote(1) ;
    	$creatorID	= (empty($creatorID) ) ? null: "\nAND g.creatorID=".$dbo->Quote( (int)$creatorID );
    	$orderCond 	= (empty($order)) ? null : "\nORDER BY g.".$order." ASC"  ;
    	$categoryID	= (empty($catID)) ? null: "\nAND g.catID=".$dbo->Quote((int)$catID );   

		$dbo->setQuery( $query, $limitstart, $limit );

		$rows 		= $dbo->loadObjectList( );
		$groups 	= array();
		
		$dbo->setQuery('SELECT FOUND_ROWS();'); 
		
		//Generate response
		foreach($rows as $group ):
					
			$pathThumbLogo 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  ,  $group->logo );
			$group->logo 		= (!empty($pathThumbLogo) && file_exists($pathThumbLogo)&&is_file( $pathThumbLogo ) ) ? $group->logo : TUIYO_GROUP_LOGO;
			
			$groups[]	 = $group ;
			
		endforeach;
		
		return (array)$groups;		 			
    }
    
    
    /**
     * TuiyoTableGroups::getSingleGroup()
     * 
     * @param mixed $groupID
     * @return void
     */
    public function loadSingleGroup( $groupID ){
    	
    	$dbo 	= $this->_db;
    	$query 	= "SELECT g.*, c.id as catId, c.title as Name
					   FROM #__tuiyo_groups AS g"
    			. "\nLEFT JOIN #__tuiyo_categories AS c"
   			   	. "\nON g.catID = c.id"
    			. "\nWHERE g.groupID =".$dbo->quote( (int)$groupID )
    			;
		$dbo->setQuery( $query );
		
		//echo $dbo->getQuery();
		
		//die;
		$group  = $dbo->loadObject();

		if(!empty($group) && is_object($group)){
			$group->members  	= $this->getGroupMembers( $groupID , $group->creatorID );
			$group->isMember 	= isset($this->isMember) ? $this->isMember : 0 ;
			$group->myMembershipID 	= isset($this->gMemberID) ? (int)$this->gMemberID : NULL ;
			$group->creator  	= $this->creator ;
			
			$pathThumbLogo 	  	=  JPATH_ROOT.DS.str_replace( array("/"), array(DS)  ,  $group->logo );
			$group->logo 		= (!empty($pathThumbLogo) && file_exists($pathThumbLogo)&&is_file( $pathThumbLogo ) ) ? $group->logo : TUIYO_GROUP_LOGO; 

		}
		
		return $group;
    }
    
    /**
     * TuiyoTableGroups::getGroupMembers()
     * Gets all Members from widthing a group
     * @param mixed $groupID
     * @param integer $limitstart
     * @param mixed $limit
     * @return void
     */
    private function getGroupMembers( $groupID, $creatorID, $limitstart=0, $limit = NULL ){
    	
		$dbo 	= $this->_db;
    	$dbo->setQuery( 
			"SELECT m.* FROM #__tuiyo_groups_members AS m ".
		    "\nWHERE m.groupID =".
			$dbo->quote((int)$groupID ) , $limitstart , $limit
		 );
		 
	 	$rows 	= $dbo->loadObjectList();
	 	$members= array();
	 	
	 	//user
	 	$user 	= JFactory::getUser( NULL ); // );
	 	$this->isMember = 0 ;
	 	$this->creator 	= NULL ;
	 	
	 	foreach((array)$rows as $member){
	 		
	 		$mUser 	= TuiyoAPI::get("user", $member->userID );
	 		
	 		$member->data 	= array(
	 			"userID"	=> $mUser->id ,
	 			"avatar"	=> TuiyoUser::getUserAvatar( $mUser->id ),
	 			"username"	=> $mUser->username,
	 			"name"		=> $mUser->name,
	 			"email"		=> $mUser->email
			 ) ;
	 		$member->isAdmin 	= ($mUser->id <> $creatorID ) ? 0 : 1 ;
	 		
	 		//Indicators
	 		if($creatorID === $mUser->id ){
	 			$this->creator  = $member->data ;
	 		}
	 		if($mUser->id === $user->id ){
	 			$this->isMember  = 1 ;
	 			$this->gMemberID = $member->memberID ;
	 		}
	 		$members[] = $member ;
	 		
	 		unset($mUser);
	 	}
    	
    	return $members ;
    }

    /**
     * TuiyoTableGroups::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance) && !empty($instance) && $ifNotExist) {
            if (is_object($instance)) {
                return $instance;
            } else {
                unset($instance);
                TuiyoTableGroups::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableGroups($db);
        }
        return $instance;
    }
}
