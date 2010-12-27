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
 * TuiyoModelPhotos
 * @version $Id$
 * @access public
 */
class TuiyoModelPhotos extends JModel{
    
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
	 * TuiyoModelPhotos::getPhotos()
	 * An array of all photos belonging to userID
	 * @param mixed $userID
	 * @param mixed $albumID
	 * @param mixed $limit
	 * @param mixed $published
	 * @param bool $newFirst
	 * @return
	 */
	public function getPhotos($userID, $albumID = NULL, $published = NULL, $newFirst = TRUE, $uselimit = TRUE , $overiteLimit = NULL )
	{
		$limit		= ($uselimit) ? $this->getState('limit') : NULL ;
		$limit 		= !is_null($overiteLimit) ? (int)$overiteLimit : $limit ;
		
		$limitstart = $this->getState('limitstart' );
		
		//1. Get all Photos
		$photosTable = TuiyoLoader::table("photos", true );
		$photos		 = $photosTable->getAllPhotos($userID, $albumID, $limitstart , $published, $newFirst , $limit );
		
		//print_R( $photos );
		
		//1b. Paginate?
		jimport('joomla.html.pagination');
		
		$dbo 				= $photosTable->_db ;
		$this->_total		= $dbo->loadResult();
		
		$pageNav 			= new JPagination( $this->_total, $limitstart, $limit );
		$root				= JURI::root();
		
		$this->pageNav 		= $pageNav->getPagesLinks();
		
		//Set the total count
		$this->setState('total' , $this->_total );
		$this->setState('pagination' ,  $this->pageNav );
				
		
		//2. Check the existence of each photo!
		foreach($photos as $photo):
			
			$photo->date_added 		= TuiyoTimer::diff( strtotime ($photo->date_added ));
			$photo->last_modified 	= TuiyoTimer::diff( strtotime ($photo->last_modified));
			
			$photo->src_original 	= $root.substr($photo->src_original, 1 ) ;
			$photo->src_thumb 		= $root.substr($photo->src_thumb, 1 ) ;
		
		endforeach;
		
		
		return (array)$photos;
	}
	
	/**
	 * TuiyoModelPhotos::getSinglePhoto()
	 * Returns a single photo for editing or viewing
	 * @param mixed $userID
	 * @param mixed $photoID
	 * @param bool $incComments
	 * @return void
	 */
	public function getSinglePhoto($userID, $photoID, $incComments = TRUE )
	{}
	
	/**
	 * TuiyoModelPhotos::getAlbums()
	 * An array of all albums belonging to ownerID
	 * @param mixed $ownerID
	 * @param string $type
	 * @param mixed $published
	 * @param mixed $privacy
	 * @param bool $alphOrder
	 * @return
	 */
	public function getAlbums( $ownerID , $type="profile", $published = NULL , $alphOrder = TRUE , $includePhotos = FALSE )
	{
		$aTable 	= TuiyoLoader::table("albums", TRUE );
		$albums 	= $aTable->getAllAlbums($ownerID, $type, $published, $alphOrder );
		
		//POST Query modifications?
		if($includePhotos){
			foreach( (array)$albums as $album ):
				$album->photos = $this->getPhotos( $ownerID , $album->aid , NULL, TRUE, TRUE,  5 );
			endforeach;
		}
		
		return (array)$albums;
	}
	
	/**
	 * TuiyoModelPhotos::getSingleAlbum()
	 * Returns a single Album, including all photos
	 * @param mixed $ownerID
	 * @param mixed $albumID
	 * @return void
	 */
	public function getSingleAlbum($ownerID, $albumID, $includePhotos = FALSE )
	{
		$aTable		= TuiyoLoader::table("albums", TRUE );
		
		//Load
		$aTable->load( (int)$albumID );
				
		//1. Check if this is a new album;
		if(empty($aTable->aid) || (int)$aTable->aid < 0 ){
			JError::raiseError(TUIYO_SERVER_ERROR, _("Unspecified album" ) );
			return false;
		}
		
		//2.include photos?
		if($includePhotos){
			$aTable->photos = $this->getPhotos( $owner , $aTable->aid );
		}
		
		return $aTable ;
	}
	
	/**
	 * TuiyoModelPhotos::editAlbum()
	 * Create, Modifies or Deletes and Album 
	 * @param mixed $userID
	 * @param mixed $albumID
	 * @param mixed $isNew
	 * @return
	 */
	public function editAlbum( $userID, $albumID = NULL, $isNew = NULL )
	{	
		$aTable		= TuiyoLoader::table("albums", TRUE );
		$post		= JRequest::get("post");
		$albumID 	= JRequest::getVar("aid", $albumID );
		$user 		= TuiyoAPI::get("user", (int)$userID );
		
		//1. Check if this is a new album;
		if(!empty($albumID) || (int)$albumID > 0 ){
			
			$aTable->load( (int)$albumID );
			//Check User has permission to edit Album
			if($aTable->ownerid <> (int)$userID){
				JError::raiseError(TUIYO_SERVER_ERROR, _("You do not  have permission to edit This album" ) );
				return false;
			}
			//TODO: Check if album is being deleted and delete
		}else{
			//set is New = True;
			$isNew = TRUE;
		}
		//2. Modify the variables
		$aTable->ownerid	= (int)$user->id ;
		$aTable->name 		= trim( $post['name'] );
		$aTable->location 	= trim( $post['location'] );
		$aTable->description= trim( $post['description'] );
		$aTable->published 	= intval( $post['published'] );
		$aTable->privacy 	= intval( $post['privacy'] );
		$aTable->last_modified = date("Y-m-d H:i:s");
		$aTable->album_type = "profile";
		$aTable->photocount = $aTable->photocount ;
	
		//3 Story Album
		if(!$aTable->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, $aTable->getError());
			return false;
		}
		
		if( $isNew ){
			//4b. Publish Activity Story;
			$uActivity 	= TuiyoAPI::get("activity", null );
			$aLink 		= JRoute::_(TUIYO_INDEX.'&view=photos&album='.$aTable->aid );
			$uStoryLine = sprintf( _('%1s created a <a href="%2s">new album</a> titled <span class="subTitle">%3s</span> <i class="subDescr">%4s</i> '), "@".$user->username , $aLink , $aTable->name, $aTable->description  );
			$uActivity->publishOneLineStory( $user, $uStoryLine , "photos");
		}
		
		//Unset a few strings;
		unset($aTable->_db);
		unset($aTable->_tbl_key);
		unset($aTable->_tbl);
		unset($aTable->_errors);
		
		return $aTable; //OK
	}
	
	/**
	 * TuiyoModelPhotos::addPhotosToAlbum()
	 * Sorts and adds photos to albums
	 * @param mixed $userID
	 * @return
	 */
	public function addPhotosToAlbum( $userID )
	{
		$aTable		=&	TuiyoLoader::table("albums", TRUE );
		$postData	=&	JRequest::get("post");
		$albumID 	=&	JRequest::getInt("aid" , NULL );
		
		//1. Check if this is a new album;
		if(!empty($albumID) || (int)$albumID > 0 ){
			
			$aTable->load( (int)$albumID );
			//Check User has permission to edit Album
			if($aTable->ownerid <> (int)$userID){
				JError::raiseError(TUIYO_SERVER_ERROR, _("You do not have permission to edit This album" ) );
				return false;
			}		
			$aTable->photocount = count( $postData['inAlbum'][(string)$albumID] );
		}else{
			JError::raiseError(TUIYO_SERVER_ERROR, _("Album not specified" ) );
			return false;			
		}	

		//2. Add photos to albums
		$aTable->addPhotosToAlbum($userID, $postData['inAlbum'] );	
		
		//3 Story Album
		if(!$aTable->store()){
			JError::raiseError(TUIYO_SERVER_ERROR, $aTable->getError());
			return false;
		}
		
		$aTable->photoIDs = $postData['inAlbum'][(string)$albumID] ;
		
		return $aTable ;
	}
	
	public function removePhotoFromAlbum()
	{}
	
	public function getPhotoComments()
	{}
	
	/**
	 * TuiyoModelPhotos::deleteAlbum()
	 * Delete Album
	 * @param mixed $userID
	 * @param mixed $albumID
	 * @return void
	 */
	public function deleteAlbum($userID, $albumID)
	{
		$aTable		= TuiyoLoader::table("albums", TRUE );
		$post		= JRequest::get("post");
		$albumID 	= JRequest::getVar("aid", $albumID );
		
		//1. Check if this is a new album;
		if(!empty($albumID) || (int)$albumID > 0 ){
			
			$aTable->load( (int)$albumID );
			//Check User has permission to edit Album
			if($aTable->ownerid <> (int)$userID){
				JError::raiseError(TUIYO_SERVER_ERROR, _("You do not have permission to edit This album" ) );
				return false;
			}
		}
		//2. Delete Photos from albums. i.e set aid to 0;
		$aTable->removeAllPhotosFromAlbum( (int)$userID , (int)$albumID );
		
		$aTableOld = clone $aTable;
		
		//3 Story Album
		if(!$aTable->delete()){
			JError::raiseError(TUIYO_SERVER_ERROR, $aTable->getError());
			return false;
		}
		//OK
		return $aTableOld ; 
	}
	
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
		
}

