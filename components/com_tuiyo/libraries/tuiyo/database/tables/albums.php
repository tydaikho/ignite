<?php
/**
 * ******************************************************************
 * TuiyoTableAlbums Class/Object for the Tuiyo platform             *
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
 * no direct access to this location
 */
defined('TUIYO_EXECUTE') || die('Restricted access');


/**
 * TuiyoTableAlbums
 * @version $Id$
 * @access public
 */
class TuiyoTableAlbums extends JTable
{
	//  `aid` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $aid			= NULL;
	//  `ownerid` INTEGER UNSIGNED NOT NULL,
	var $ownerid 		= NULL;
	//  `coverpic_id` INTEGER UNSIGNED,
	var $coverpic_id	= NULL;
	//  `name` TEXT NOT NULL,
	var $name			= NULL;
	//  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $created 		= NULL;
	//  `description` TEXT,
	var $description 	= NULL;
	//  `location` TEXT,
	var $location 		= NULL;
	//  `slideshow_link` TEXT,
	var $slideshow_link	= NULL;
	//  `last_modified` TIMESTAMP NOT NULL,
	var $last_modified 	= NULL;
	//  `album_type` VARCHAR(45) NOT NULL DEFAULT 'profile' COMMENT 'profile or group?',
	var $album_type		= NULL;
	//  `published` ENUM('0','1') NOT NULL DEFAULT 0,
	var $published 		= NULL;
	//  `photocount` INTEGER UNSIGNED NOT NULL,
	var $photocount		= NULL;
	//  `privacy` VARCHAR(45),
	var $privacy 		= NULL;
	
    /**
     * TuiyoTableAlbums::__construct()
     * Constructs the table class
     * @param mixed $db
     * @return
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_albums", "aid", $db );
    }
    
    /**
     * TuiyoTableAlbums::getAllAlbums()
     * 
     * @param mixed $ownerID
     * @param string $type
     * @param mixed $published
     * @param mixed $privacy
     * @param bool $alphOrder
     * @return
     */
    public function getAllAlbums($ownerID , $type="profile", $published = NULL , $alphOrder = TRUE)
	{
		$dbo 	= $this->_db;
		$query 	= "SELECT a.*, r.url AS coverpic_url ".
		  "\nFROM #__tuiyo_albums AS a".
		  "\nLEFT JOIN #__tuiyo_resources AS r".
		  "\nON a.coverpic_id = r.resourceID".
		  "\nWHERE a.ownerid =".$dbo->quote( (int)$ownerID ).
		  "\nORDER BY a.name ASC"
  		
  		;	
		$dbo->setQuery( $query );
		$albums = $dbo->loadObjectList();
		
		return $albums; 
	}
	
	/**
	 * TuiyoTableAlbums::removeAllPhotosFromAlbum()
	 * Removes all photos from an album before deleting
	 * @param mixed $ownerID
	 * @param mixed $albumID
	 * @param mixed $photoIDs
	 * @return
	 */
	public function removeAllPhotosFromAlbum($ownerID, $albumID, $photoIDs = array()){
		
		$dbo	= $this->_db ;
		$query 	= "UPDATE #__tuiyo_photos p"
				. "\nSET p.aid=0 WHERE p.aid=".$dbo->quote( (int)$albumID )
				. "\nAND p.userid =".$dbo->quote( (int)$ownerID )
		; 
		$dbo->setQuery( $query );
		
		if(! $dbo->query() ){
			JError::raiseError(TUIYO_NOT_MODIFIED , _("Could not empty the album" ) );
			return false;
		}
		return true;
	}
	
	/**
	 * TuiyoTableAlbums::addPhotosToAlbum()
	 * 
	 * @param mixed $ownerID
	 * @param mixed $organizeArray
	 * @return
	 */
	public function addPhotosToAlbum($ownerID,  $organizeArray = array() )
	{
		//if(empty($organizeArray)) return false;
		
		$dbo 	= $this->_db;
		$case 	= '';
		$inCase = array();
		
		foreach( $organizeArray as $albumID=>$caseItem ):
			
			if(!is_array($caseItem) || empty($caseItem)):
				continue;
			endif;
			
			foreach($caseItem as $photoID):
				$case 	.= "\nWHEN $photoID THEN $albumID";
				$inCase[]= $photoID;
			endforeach;
			
		endforeach;
		
		$inCase = implode( ',', $inCase );
		
		$dbo->setQuery("UPDATE #__tuiyo_photos SET aid = CASE pid {$case} END \nWHERE pid IN({$inCase})");
		
		$dbo->query();
		
	}

    /**
     * TuiyoTableAlbums::getInstance()
     * Gets an instance of the TuiyoTableAlbums class
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
                TuiyoTableAlbums::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableAlbums($db);
        }
        return $instance;
    }		
}