<?php
/**
 * ******************************************************************
 * Uploaded File manager object for the Tuiyo platform              *
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
 * No direct access to these files
 */
defined('TUIYO_EXECUTE') || die;

/**
 * joomla File management libraries
 */
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');
jimport('joomla.filesystem.archive');

/**
 * TuiyoUploads
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoUploads
{
	
	/**
	 * The Maximum file size per type in bytes
	 * Do not change this
	 */
	private $_maxFileSize = array(
		"avatar"	=>	6553600,
		"audio"		=>	10000000,
		"photos"	=>	1048576,
		"wallpaper"	=>	1048576,
		"gavatar"   =>	6553600,		
	);	
		
	/**
	 * The ultimate max file size in bytes
	 * Do not change this (set at 25MB)
	 */
	private $_postMaxSize = 26214400;	
		
	/**
	 * The allowed file extensions
	 * jpg, gif, png, jpeg, mp3, doc, 
	 **/
	private $_extWhitelist 	= array("mp3","jpg", "gif", "png", "jpeg", "zip");
	
	/**
	 * Characters allowed in the file name 
	 * (in a Regular Expression format)
	 */
	private $_validChars	= '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';
	
	/**
	 * The upload file type
	 */
	private $_fileType		= null;	
	
	/**
	 * The Last uploaded File
	 */
	private $_lastUploadedItem	= null;		
	
	/**
	 * Max File Length
	 */
	private $_maxNameLength = 100;
	

	/**
	 * TuiyoUploads::__contstruct()
	 * 
	 * @return void
	 */
	public function __construct( $type ){
		//Set File Type
		$this->_fileType = $type;
	}


    /**
     * TuiyoUploads::checkItemPermission()
     * 
     * @return
     */
    public function getPermission()
    {}

    /**
     * TuiyoUploads::getItemUrl()
     * 
     * @return
     */
    public function getItemUrl()
    {}

    /**
     * TuiyoUploads::saveItem()
     * 
     * @return
     */
    public function saveItem( $fData , $sData )
    {
    	//Check Upload Method
    	if ($_SERVER['REQUEST_METHOD'] !== "POST"){
    		trigger_error( _("Method Accepts only POST"), E_USER_ERROR);
			return false;
    	}
		
    	//Check the file
   		if(!isset($fData) || empty($this->_fileType )) {
			trigger_error(_("No uploaded files detected"), E_USER_ERROR);
			return false;
		} elseif (isset($fData["error"]) && $fData["error"] != 0) {
			trigger_error($fData["error"], E_USER_ERROR );
			return false;
		} elseif (!isset($fData["tmp_name"]) || !@is_uploaded_file($fData["tmp_name"])) {
			trigger_error(_("Invalid uploaded resource."), E_USER_ERROR );
			return false;
		} else if (!isset($fData['name'])){
			trigger_error(_("File has no name"), E_USER_ERROR);
			return false;
		}
    	//Check User Upload Limit;
    	//Check File upload limit;
    	//Check the passed Data
    	//Validate the file
		$fName = preg_replace('/[^'.$this->_validChars.']|\.+$/i', "", basename($fData['name']));
		if (strlen($fName) == 0 || strlen($fName) > $this->_maxNameLength ) {
			trigger_error(_("Invalid file Name."), E_USER_ERROR );
			return false;
		}
    	//move the file to the cache
		$targetCache = JPATH_CACHE.DS.basename( $fData['name'] );
		
    	if(!move_uploaded_file($fData['tmp_name'], $targetCache )) {
			trigger_error(_("Upload Failed"), E_USER_ERROR);
			return false;
	    }
    	//Load the resources table
    	$resourceTable =& TuiyoLoader::table("resources", true);
    	
    	$this->_lastUploadedItem = $resourceTable->saveFile($fData, $this->_fileType );
    	
		return true;
    }
    
    /**
     * TuiyoUploads::getLastUploaded()
     * 
     * @return void
     */
    public function getLastUploaded(){
    	return $this->_lastUploadedItem->url ;
    }
    

    /**
     * TuiyoUploads::checkUploadLimit()
     * 
     * @return
     */
    public function checkUploadLimit()
    {
    	//Check Overall Limit
		$multiplier 	= ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

		if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$this->postMaxSize && $this->postMaxSize) {
			trigger_error(_("Exceeded Maximum file size"), E_USER_ERROR );
		}
		//Check Type limit
		$fileSize = @filesize($_FILES["Filedata"]["tmp_name"]);
		if (!$fileSize || $fileSize > $this->_maxFileSize[$this->_fileType]) {
			trigger_error(_("File exceeds the maximum allowed size"), E_USER_ERROR);
			return false;
		}
		if ($fileSize <= 0) {
			trigger_error(_("File size outside allowed lower bound"), E_USER_ERROR);
			return false;
		}		
		
    }
    
    /**
     * Produces an archive of files 
     * 
     * TuiyoUploads::archiveFiles()
     * 
     * @param mixed $userID
     * @param mixed $files
     * @return link on success, raises error on failure
     */
    public function archiveFiles($userID, $files ){
   		
	    //move the file to the cache
		$targetCache 	= JPATH_CACHE.DS;
		$targetFolder	= $targetCache.DS.$this->_randomCode(4); 
	 	$targetName	 	= $targetFolder.DS.$this->_randomCode(4).".archive.zip";
		 
		$archiveFiles 	= array();
		$archiveZip 	= new ZipArchive();

		//Create Folder;
		if( JFolder::create( $targetFolder) ){		
			//create the file and throw the error if unsuccessful
			if ($archiveZip->open($targetName, ZIPARCHIVE::CREATE )!== true ) {
				trigger_error(_("Could not open $targetName archive"), E_USER_ERROR);
				return false;
			}
			
			//Archive the files
			foreach($files as $file){
				
				$name = JFile::getName( $file );
				$dest = $targetFolder.DS.$name;
				 
				if(!JFile::copy( $file , $dest ) ){
					trigger_error(_("Could not archive files"), E_USER_ERROR);
					return false;
				}
				//add the File
				$archiveZip->addFile($dest, $name);
			}
		}
		$archiveZip->close();
		
		//If the achive exists! copy it to user resource!
		if(JFile::exists( $targetName )){
			if(JFile::move($targetName, $targetCache.DS.basename( $targetName ) )){
				JFolder::delete( $targetFolder );
				
				$fData 	= array(
					"name" => basename( $targetName )
				);
		    	//Save to the resources table
		    	$resourceTable =& TuiyoLoader::table("resources", true);
		    	$resourceLink  =& $resourceTable->saveFile($fData, $this->_fileType );
		    	
				return $resourceLink;
	    	}
		}
		return false;
    }

    /**
     * TuiyoUploads::downloadItem()
     * 
     * @return
     */
    public function downloadItem()
    {}

    /**
     * TuiyoUploads::downloadItems()
     * 
     * @return
     */
    public function downloadItems()
    {}
    
    
    /**
     * TuiyoUploads::getErrors()
     * 
     * @return void
     */
    public function getErrors(){}
    
    /**
     * TuiyoUploads::_randomCode()
     * 
     * @return void
     */
    private function _randomCode($length){
	
		$code = md5(uniqid(rand(), true));
		if ($length != "") 
			return substr($code, 0, $length);
		else 
			return $code;

    }

}
