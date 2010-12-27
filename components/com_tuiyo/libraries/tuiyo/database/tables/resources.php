<?php
/**
 * ******************************************************************
 * All Uploaded Resrouces Class/Object for the Tuiyo platform       *
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
 * TuiyoTableResources
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableResources extends JTable{

	//`resourceID` INTEGER UNSIGNED NOT NULL,
	var $resourceID 			= null;
	//`userID` INTEGER UNSIGNED NOT NULL,
	var $userID					= null;
	//`dateAdded` DATE ,
	var $dateAdded				= null;
	//`tags` VARCHAR(100),
	var $tags					= null;
	//`size` INTEGER UNSIGNED,
	var $size					= null;
	//`rating` VARCHAR(45),
	var $rating					= null;
	//`album` VARCHAR(100),
	var $album					= null;
	//`artist` VARCHAR(100),
	var $artist					= null;
	//`author` VARCHAR(100),
	var $author					= null;
	//`composer` VARCHAR(100),
	var $composer				= null;
	//`contentType` VARCHAR(45),
	var $contentType			= null;
	//`copyright` VARCHAR(45),
	var $copyright				= null;
	//`creator` VARCHAR(45),
	var $creator				= null;
	//`dateCreated` DATE ,
	var $dateCreated			= null;
	//`dateLastModified` DATE ,
	var $dateLastModified		= null;
	//`description` VARCHAR(200),
	var $description			= null;
	//`dimension` VARCHAR(45),
	var $dimension				= null;
	//`exposureProgram` VARCHAR(45),
	var $exposureProgram		= null;
	//`exposureBias` VARCHAR(45),
	var $exposureBias			= null;
	//`exposureTime` VARCHAR(45),
	var $exposureTime			= null;
	//`fileName` VARCHAR(100),
	var $fileName				= null;
	//`flashMode` VARCHAR(100),
	var $flashMode				= null;
	//`focalLength` VARCHAR(100),
	var $focalLength			= null;
	//`folder` VARCHAR(255),
	var $folder					= null;
	//`folderPath` VARCHAR(255),
	var $filePath				= null;
	//`genre` VARCHAR(45),
	var $genre					= null;
	//`isAttachment` BOOLEAN,
	var $isAttachement			= null;
	//`isDeleted` BOOLEAN,
	var $isDeleted				= null;
	//`language` VARCHAR(45),
	var $language				= null;
	//`lightSource` VARCHAR(45),
	var $lightSource			= null;
	//`apperture` VARCHAR(45),
	var $apperture				= null;
	//`owner` VARCHAR(45),
	var $owner					= null;
	//`producers` VARCHAR(100),
	var $producers				= null;
	//`path` VARCHAR(100),
	var $path					= null;
	//`senderName` VARCHAR(45),
	var $senderName				= null;
	//`senderEmail` VARCHAR(80),
	var $senderEmail			= null;
	//`type` VARCHAR(80),
	var $fileType					= null;
	//`url` VARCHAR(200),
	var $url					= null;
	//`verticalResolution` VARCHAR(45),
	var $verticalResoultion		= null;
	//`videoCompression` VARCHAR(45),
	var $videoCompression		= null;
	//`wordCount` VARCHAR(45),
	var $wordCount				= null;
	//`year` VARCHAR(45),
	var $year					= null;
	//`isPublic` BOOLEAN NOT NULL,
	var $isPublic				= null;
	//
	var $fileTitle				= null;

	//photos fields			
	
	/**
	 * TuiyoTableResources::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db = null)
	{
		parent::__construct("#__tuiyo_resources", "resourceID", $db );
		$this->url = JURI::getPath( true );	
	}
	
	/**
	 * TuiyoTableResources::saveFile()
	 * 
	 * @param mixed $fileData
	 * @param mixed $type
	 * @return void
	 */
	public function saveFile($fileData, $type){
		//1. Validate Type [NOT AGAIN!!]
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;	
		$user			= $GLOBALS["API"]->get("user", null);
		$fileName		= $user->id."_".str_replace(array(" ","(",")","-","&","%",",","#" ), "", $fileData["name"] ); 
		
		//Check we know who we are dealing with
		if($user->joomla->get("guest")){
			JFile::delete( $fileCache );
			trigger_error("User must be logged in", E_USER_ERROR);
			return false;
		}

		//2. Bind Type Default Parts
		$this->fileTitle= JFile::stripExt( $fileData['name'] );
		$this->fileName = $fileName ;
		$this->fileType	= JFile::getExt( $fileCache );
		$this->userID	= $user->id;
		$this->author	= $user->username;
		$this->owner	= $user->username;

		$this->fileType		= strtoupper( $this->fileType);
		$this->contentType	= strtoupper( $type );
		$this->dateAdded	= date('Y-m-d');
		$this->dateLastModified = date('Y-m-d' , filemtime( $fileCache ) );
		
		$this->size		= filesize  ( $fileCache  );
		
		//3. Call User Function per Type
		$userFunc = array( 
			"avatar"=> "saveAvatarFile",
			"audio"	=> "saveAudioFile",
			"photos"=> "savePhotoFile",
			"video" => "saveVideoFile",
			"archive"=>"saveArchiveFile",
			"wallpaper"=>"saveWallPaper",
			"gavatar"=>"saveGroupAvatar"
		);
		
		//4. Call user function
		return call_user_func( array( $this, $userFunc[$type]), $fileData );
	}
	
	/**
	 * 
	 * TuiyoTableResources::saveImageFile()
	 * Adds new photo resources to the photos Table
	 * 
	 * @param mixed $fileData
	 * @return void
	 */
	public function savePhotoFile($fileData){
		//1. Move the file to the actual Photos Directory. i.e files/avatar/62/file.jpg
		$photosTable	= TuiyoLoader::table("photos" , true );
		
		$user			= $GLOBALS["API"]->get("user", null);
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;
		$filePath		= TUIYO_FILES.DS."photos".DS.$user->id.DS;
		$newFilePath		= $filePath.$this->fileName;
		$newThumbFilePath   = $filePath."thumb".DS.$this->fileName ;
		
		//If we cannot create a folder
		if(!JFolder::exists( $filePath)){
			JFolder::create( $filePath );
			JPath::setPermissions( $filePath );
		}
		if(file_exists($newFilePath)){
			JFile::delete( $fileCache );
			return true;
		}
		//If we cannot copy the file
		if(!JFile::copy($fileCache , $newFilePath )){
			trigger_error( "Could not copy the file", E_USER_ERROR );
			return false;
		}
		JFile::delete( $fileCache );
		
		//2. Complete the File Information
		$this->filePath	= JPath::clean( $filePath );
		$this->url		= $this->url.str_replace( array(JPATH_ROOT , DS) , array("","/") , $newFilePath );
		$this->dimension = json_encode( getimagesize( $newFilePath ) );
		
		//3. Save the Object in the Database
		if(!$this->store()){
			trigger_error( $this->getError(), E_USER_ERROR);
			return false;
		}
		
		//4. Add to the Photos Table;
		$photosTable->src_original_id 	= (int)$this->resourceID; 
		$photosTable->src_thumb_id		= (int)$this->createPhotoThumb( 100 );
		$photosTable->userid			= (int)$user->id;
		$photosTable->comment_count		= (int)0 ;
		$photosTable->aid				= (int)0 ;
		$photosTable->last_modified		= date("Y-m-d H:i:s");
		
		if(!$photosTable->store()){
			trigger_error( $photosTable->getError(), E_USER_ERROR);
			return false;
		}
		
		//Add MultiLine Story
			//Check if any photos where uploaded in the last 5 minutes;
		$canCreateActivity = $this->canCreateNewActivity( $user->id );
		
	    if(!is_object($canCreateActivity)){
			$activity 	= TuiyoAPI::get("activity" );
			$root 		= JURI::root();
			$actTitle 	= "{*thisUser*} uploaded pictures to {*thisGSP1a*} profile";
			$actResrces = array( 
				array(
					"type"	=> "image",
					"furl"	=> $this->url,
					"url"	=> $root.substr( str_replace( array(JPATH_ROOT , DS) , array("","/") , $newThumbFilePath ) , 1 )
				) 
			);
			$photoCount = count($actResrces);
			$activity->publishMultiLineStory($user, $actTitle, "<i>$photoCount photo(s) uploaded</i>", "photos", NULL, NULL, NULL, $actResrces );
		}else{
			$activity 	 = TuiyoAPI::get("activity" );
			$actTitle 	 = "{*thisUser*} uploaded pictures to {*thisGSP1a*} profile";
			$root 		 = JURI::root();
			$actResrces1 = json_decode( $canCreateActivity->resources , TRUE);
			$actResrces2 = array( 
				array(
					"type"	=> "image",
					"furl"	=> $this->url,
					"url"	=> $root.substr( str_replace( array(JPATH_ROOT , DS) , array("","/") , $newThumbFilePath ) , 1 )
				) 
			);
			$actResrces3 = array_merge($actResrces1 , $actResrces2 );
			$photoCount2 = count($actResrces3);
			
			//$canCreateActivity->sharewith   = '["%p00%"]'; //@TODO check privacy per user
			$canCreateActivity->resources 	= json_encode( $actResrces3 );
			$canCreateActivity->body 		= "<i class=\"uploadCount\" >$photoCount2 photo(s) uploaded</i>";
			
			$canCreateActivity->store();
			
		}
		
		return true;		
	}
	
	/**
	 * 
	 * TuiyoTableResources::lastUploadNotRecent()
	 * Checks if the last photoupload activity is within the 5 min treshold
	 * 
	 * @param mixed $userID
	 * @return void
	 */
	public function canCreateNewActivity($userID){
		
		$tTable	=& TuiyoLoader::table("timelinetmpl", TRUE );
		$dbo 	= $this->_db;
		$query 	= "SELECT template, datetime FROM #__tuiyo_timeline "
		        . "\nWHERE userID=".$dbo->quote( (int)$userID )
	 			. "\nAND source = 'photos'"
				. "\nORDER BY datetime DESC LIMIT 1"
				;
	 	$dbo->setQuery( $query );
	 	
	 	$row  	= $dbo->loadObjectList();
	 	
	 	if(sizeof($row) < 1) return false;
	 	
	 	$now 	= time();
	 	$time 	= strtotime($row[0]->datetime);
	 	
	 	if($now < ($time + 3600)){
	 		
 		    //print_R($tTable);
	 		$ltTable = new TuiyoTableTimelinetmpl( $this->_db );
		    $ltTable->load( (int)$row[0]->template );
	 		
	 		return $ltTable;
	 	}
	}
	
	
	/**
	 * TuiyoTableResources::loadImageFile()
	 * 
	 * @param mixed $fileId
	 * @return void
	 */
	public function loadPhotoFile($fileId = null){}
	
	/**
	 * TuiyoTableResources::saveAudioFile()
	 * 
	 * @param mixed $fileData
	 * @return void
	 */
	public function saveAudioFile($fileData){
		//1. Move the file to the actual Avatar Directory. i.e files/avatar/62/file.jpg
		$user			= $GLOBALS["API"]->get("user", null);
		
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;
		$filePath		= TUIYO_FILES.DS."music".DS.$user->id.DS;
		$newFilePath	= $filePath.$this->fileName;
		//If we cannot create a folder
		if(!JFolder::exists( $filePath)){
			JFolder::create( $filePath );
			JPath::setPermissions( $filePath );
		}
		if(file_exists($newFilePath)){
			JFile::delete( $fileCache );
			return true;
		}
		//If we cannot copy the file
		if(!JFile::copy($fileCache , $newFilePath )){
			trigger_error( "Could not copy the file", E_USER_ERROR );
			return false;
		}
		JFile::delete( $fileCache );
		//2. Complete the File Information
		$this->filePath	= JPath::clean( $filePath );
		$this->url		= $this->url.str_replace( array(JPATH_ROOT , DS) , array("","/") , $newFilePath );
		
		//Tags
		$newFilePath = fopen($newFilePath, "r");
		fseek($newFilePath, -128, SEEK_END);
		$tag = fread($newFilePath, 3);
              
		if ($tag == "TAG") {
			$data["song"] = trim(fread($newFilePath, 30));
			$this->artist = trim(fread($newFilePath, 30));
			$this->album  = trim(fread($newFilePath, 30));
			$this->year = trim(fread($newFilePath, 4));
			//$data["genre"] = $genre_arr[ord(trim(fread($newFilePath, 1)))];
		} 
              
  		fclose($newFilePath);
		
		//3. Save the Object in the Database
		if(!$this->store()){
			trigger_error( $this->getError(), E_USER_ERROR);
			return false;
		}
		//2. Update the User Table with the current user Avatar Details. I.e Avatar id!
		return true;		
	}
	
	/**
	 * TuiyoTableResources::loadMp3File()
	 * Loads all user audio files in the resource table
	 * @param mixed $fileId
	 * @return void
	 */
	public function loadAllAudioFiles($userID , $fileIds = array(), $format = FALSE ){
		
		$userID = !empty($userID ) ? (int)$userID : $GLOBALS["API"]->get("user", null)->id ;
		$filter = !empty($fileIds)? "\nAND r.resourceID IN (".implode( "," , $fileIds ).")" : "" ;
		$dbo 	= $this->_db;
		
		$query 	= "SELECT r.artist, r.composer, r.fileName, r.album, r.url, r.year, r.fileTitle, r.rating"
				. "\nFROM #__tuiyo_resources r"
				. "\nWHERE r.userID  = ".$dbo->quote( (int)$userID )
				. "\nAND r.contentType = 'AUDIO'"
				. $filter
				;
				
		$dbo->setQuery( $query );
		$files 	= $dbo->loadObjectList( );
		
		//Adjust Parameters
		if($format){
			$formated = array();
			foreach($files as $item){
				
			}
			$files = $formated ;
		}
		return (array)$files ;
	}
		

		
	/**
	 * TuiyoTableResources::saveAvatarFile()
	 * 
	 * @param mixed $fileData
	 * @return void
	 */
	public function saveAvatarFile($fileData){
		//1. Move the file to the actual Avatar Directory. i.e files/avatar/62/file.jpg
		$user			= $GLOBALS["API"]->get("user", null);
		
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;
		$filePath		= TUIYO_FILES.DS."avatars".DS.$user->id.DS;
		$newFilePath	= $filePath.$this->fileName;
		//If we cannot create a folder
		if(!JFolder::exists( $filePath)){
			JFolder::create( $filePath );
			JPath::setPermissions( $filePath );
		}
		if(file_exists($newFilePath)){
			JFile::delete( $newFilePath ); //Just delete the file
			//return true;
		}
				
		//If we cannot copy the file
		if(!JFile::copy($fileCache , $newFilePath )){
			trigger_error( "Could not copy the file", E_USER_ERROR );
			return false;
		}
		
		JFile::delete( $fileCache );
		//2. Complete the File Information
		$this->filePath	 = JPath::clean( $filePath );
		$this->url		 = $this->url.str_replace( array(JPATH_ROOT , DS) , array("","/") , $newFilePath );	
		$this->dimension = json_encode( getimagesize( $newFilePath ) );
		 
		//3. Save the Object in the Database
		if(!$this->store()){
			trigger_error( $this->getError(), E_USER_ERROR);
			return false;
		}
		
		//print_R("stored okay"); die;
		
		//2. Update the User Table with the current user Avatar Details. I.e Avatar id!
		$thumbs = array(
			"thumb200" 	 => $this->createAvatarThumb( 200 ),
			"thumb70"	 => $this->createAvatarThumb( 70 ),
			"thumb35"	 => $this->createAvatarThumb( 35 )
		);
		
		$paramsTable = TuiyoAPI::get( "params" );
		$paramsTable->loadParams( "user.avatar" , $this->userID );
		
		$query = "DELETE FROM #__tuiyo_params "
		       . "\nWHERE userID='".(int)$this->userID
			   . "'\nAND application='user.avatar'"
			   ;
		$this->_db->setQuery( $query );	   
  		$this->_db->query();
		
		$tableOfParams = TuiyoLoader::table( "params" );
		$tableOfParams->load( null );
		$tableOfParams->userID = $this->userID;
		$tableOfParams->data   = json_encode( $thumbs );
		$tableOfParams->application = "user.avatar";
		
		if(!$tableOfParams->store()){
			trigger_error( $thumbX->getError(), E_USER_ERROR);
			return false;
		}
	
		
		return true;
	}
	
	
	/**
	 * TuiyoTableResources::saveGroupAvatar()
	 * Saves a group Avatar;
	 * @param mixed $fileData
	 * @return
	 */
	public function saveGroupAvatar( $fileData ){
		
		$user			= $GLOBALS["API"]->get("user", null);
		$groupID		= JRequest::getInt("groupID", null );
		
		//Must have a groupID
		if(!isset($groupID)||$groupID<1){
			JError::raiseError( TUIYO_SERVER_ERROR , _("Invalid group id specified"));
			return false;
		}
	
		
		$gModel 		= TuiyoLoader::model("groups", true );
		$gData 			= $gModel->getGroup( $groupID );

		//If is already a member
		if(!$gData || $gData->isMember < 1 || $gData->isAdmin < 1 || empty($gData->groupID) || $gData->groupID < 1 ){
			JError::raiseError( TUIYO_SERVER_ERROR , _("You do not have permission to modify this group"));
			return false;
		}		
		
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;
		$filePath		= TUIYO_FILES.DS."groups".DS.$user->id.DS;
		$newFilePath	= $filePath.$this->fileName;
		
		//If we cannot create a folder
		if(!JFolder::exists( $filePath)){
			JFolder::create( $filePath );
			JPath::setPermissions( $filePath );
		}
		if(file_exists($newFilePath)){
			JFile::delete( $fileCache );
			return true;
		}
		//If we cannot copy the file
		if(!JFile::copy($fileCache , $newFilePath )){
			trigger_error( "Could not copy the file", E_USER_ERROR );
			return false;
		}
		JFile::delete( $fileCache );
		//2. Complete the File Information
		$this->filePath	 = JPath::clean( $filePath );
		$this->url		 = $this->url.str_replace( array(JPATH_ROOT , DS) , array("","/") , $newFilePath );	
		$this->dimension = json_encode( getimagesize( $newFilePath ) );
		 
		//3. Save the Object in the Database
		if(!$this->store()){
			trigger_error( $this->getError(), E_USER_ERROR);
			return false;
		}
		
		//2. Update the User Table with the current user Avatar Details. I.e Avatar id!
		$thumbs = array(
			"thumb200" 	 => substr( $this->createAvatarThumb( 200 ), 1 ), 
			"thumb70"	 => substr( $this->createAvatarThumb( 70 ), 1 ), 
			"thumb35"	 => substr( $this->createAvatarThumb( 35 ), 1 ) 
		);
		
		$data = array( 
			"groupID"	=> $groupID,
			"banner" 	=> json_encode( $thumbs ),
			"logo"		=> $thumbs["thumb70"]
		);
		
		$gModel->storeGroup( $data, $user->id , false );
		
		return true;
	}
	
	
	/**
	 * TuiyoTableResources::createPhotoThumb()
	 * Creates and saves thumbnails for photo albums
	 * @param mixed $width
	 * @return void
	 */
	private function createPhotoThumb( $width )
	{
		$filePath		= TUIYO_FILES.DS."photos".DS.$this->userID.DS;
		$userAvatar		= array();
		$imageMani 		= TuiyoAPI::get( "imagemanipulation" );
		$thumbX 		= clone $this;
		$thumbXDir		= $filePath."thumb".DS ;
		$thumbXtarget 	= $thumbXDir.$this->fileName ;
		$thumbSource 	= $this->filePath.$this->fileName ;
		
		if(!JFolder::exists( $thumbXDir)){
			JFolder::create( $thumbXDir );
			JPath::setPermissions( $thumbXDir );
		}
		
	    if( $imageMani->resizeImage( $thumbSource, $thumbXtarget, $width, $width, true ) ){
	    	$thumbX->resourceID = null;
	    	$thumbX->filePath 	= JPath::clean( $thumbXDir );
	    	$thumbX->url		= JURI::getPath( true ).str_replace( array(JPATH_ROOT , DS) , array("","/") , $thumbXtarget );
	    	$thumbX->fileTitle  = $thumbX->fileTitle."_".$width."x".$width  ;
			//3. Save the Object in the Database
			if(!$thumbX->store()){
				trigger_error( $thumbX->getError(), E_USER_ERROR);
				return false;
			}
	    }	
	    $thumbID = $thumbX->resourceID ;
	    $thumbX  = null;
	    
		return (int)$thumbID ;		
	}
	
	/**
	 * TuiyoTableResources::createAvatarThumb()
	 * Creates and saves a thumb for the avatar image.
	 * @param mixed $width
	 * @return
	 */
	private function createAvatarThumb( $width ){
		
		$filePath		= TUIYO_FILES.DS."avatars".DS.$this->userID.DS;
		$userAvatar		= array();
		$imageMani 		= TuiyoAPI::get( "imagemanipulation" );
		$thumbX 		= clone $this;
		$thumbXDir		= $filePath."thumb".$width.DS ;
		$thumbXtarget 	= $thumbXDir.$this->fileName ;
		$thumbSource 	= $this->filePath.$this->fileName ;
		
		if(!JFolder::exists( $thumbXDir)){
			JFolder::create( $thumbXDir );
			JPath::setPermissions( $thumbXDir );
		}
		
	    if( $imageMani->resizeImage( $thumbSource, $thumbXtarget, $width, $width, true ) ){
	    	$thumbX->resourceID = null;
	    	$thumbX->filePath 	= JPath::clean( $thumbXDir );
	    	$thumbX->url		= JURI::getPath( true ).str_replace( array(JPATH_ROOT , DS) , array("","/") , $thumbXtarget );
	    	$thumbX->fileTitle  = $thumbX->fileTitle."_".$width."x".$width  ;
			//3. Save the Object in the Database
			if(!$thumbX->store()){
				//echo $thumbX->getError(); die;
				trigger_error( $thumbX->getError(), E_USER_ERROR);
				return false;
			}
	    }	
	    $URL 	= $thumbX->url;
	    $thumbX = null;
	    
		return $URL ;
	}
	
	
	/**
	 * TuiyoTableResources::saveArchiveFile()
	 * 
	 * @param mixed $fileData
	 * @return
	 */
	public function saveArchiveFile($fileData){
		//1. Move the file to the actual Avatar Directory. i.e files/avatar/62/file.jpg
		$user			= $GLOBALS["API"]->get("user", null);
		
		$fileCache		= JPATH_CACHE.DS.basename( $fileData['name'] ) ;
		$filePath		= TUIYO_FILES.DS."archives".DS.$user->id.DS;
		$newFilePath	= $filePath.$this->fileName;
		//If we cannot create a folder
		if(!JFolder::exists( $filePath)){
			JFolder::create( $filePath );
			JPath::setPermissions( $filePath );
		}
		if(file_exists($newFilePath)){
			JFile::delete( $fileCache );
			return true;
		}
		//If we cannot copy the file
		if(!JFile::copy($fileCache , $newFilePath )){
			trigger_error( _("Could not copy the file"), E_USER_ERROR );
			return false;
		}
		JFile::delete( $fileCache );
		//2. Complete the File Information
		$this->filePath	 = JPath::clean( $filePath );
		$this->url		 = $this->url.str_replace( array(JPATH_ROOT , DS) , array("","/") , $newFilePath );	
		$this->dimension = json_encode( getimagesize( $newFilePath ) );
		 
		//3. Save the Object in the Database
		if(!$this->store()){
			trigger_error( $this->getError(), E_USER_ERROR);
			return false;
		}
		//2. Update the User Table with the current user Avatar Details. I.e Avatar id!
		return $this->url;
	}	
	
	/**
	 * 
	 * TuiyoTableResources::suggestResource()
	 * Suggests users or group from string names
	 * 
	 * @param mixed $string
	 * @param mixed $userID
	 * @param integer $limit
	 * @return Array(
	 * 		Object{
	 * 			"rID"=>  'the resource ID',
	 * 			"rType"=>'friend' or 'group'
	 *          "rName"=>'the resouce name'
	 *  	}
	 * )
	 */
	public function suggestResource($string, $userID, $limit = 10 , $searchGroups = TRUE ){
		
		$dbo 	= $this->_db ;
		$salt 	= (string)$string ;
		$groups = ($searchGroups) 
				? "\nUNION SELECT g.groupID as rID, g.gName as rName, 'group' as rType"
				. "\nFROM #__tuiyo_groups_members AS m"
				. "\nINNER JOIN #__tuiyo_groups AS g"
				. "\nON m.groupID = g.groupID"
				. "\nLEFT JOIN #__tuiyo_categories AS c"
				. "\nON g.catID = c.id"
				. "\nWHERE m.userID =".$dbo->quote( (int)$userID )
				. "\tAND g.gName LIKE ".$dbo->quote( "%{$salt}%" )			
				: null;
				
;		$query 	= "SELECT u.id as rID, CONCAT(u.name, ' (', u.username,')') as rName , 'friend' as rType"
				. "\nFROM #__tuiyo_friends f"
				. "\nINNER JOIN #__users AS u ON (u.id = f.thatUserID AND f.thisUserID =" .$dbo->quote( (int)$userID )
				. "\tOR u.id = f.thisUserID AND f.thatUserID =" .$dbo->quote( (int)$userID ). ")"
				. "\nLEFT JOIN #__tuiyo_users AS p ON p.userID = u.id"
				. "\nWHERE (f.thisUserID = ".$dbo->quote( (int)$userID )
				. "\tOR f.thatUserID = " .$dbo->quote( (int)$userID )
				. "\t) AND (u.username LIKE " .$dbo->quote( "%{$salt}%" )
				. "\tAND f.state = 1"
				. "\tOR u.name LIKE ".$dbo->quote( "%{$salt}%" )." )"
				. $groups ;
				
				
		$dbo->setQuery( $query, 0 , $limit ); 
		
		$results = (array)$dbo->loadObjectList();
		
		//echo $dbo->getQuery();
		//echo $dbo->getQuery();
		$public = new stdClass ;
		$public->rID 	= '00';
		$public->rType 	= 'friend';
		$public->rName  = '@everyone';
		$resultsize 	= count($results);
		
		$rows 			= array_merge( $results , array( $resultsize=>$public ) );		
		
		//Post Query Manip
		return $rows ;
		
	}
	
	/**
	 * TuiyoTableResources::loadVideoFile()
	 * 
	 * @param mixed $fileId
	 * @return void
	 */
	public function loadVideoFile($fileId = null){}
		
	/**
	 * TuiyoTableResources::saveVideoFile()
	 * 
	 * @param mixed $fileData
	 * @return void
	 */
	public function saveVideoFile( $fileData ){}
	
    /**
     * TuiyoTableResources::getInstance()
     * 
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db=null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableResources::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableResources( $db  )	;	
		}
		return $instance;	 
	 }		
}