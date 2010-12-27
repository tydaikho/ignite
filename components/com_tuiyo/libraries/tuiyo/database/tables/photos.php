<?php
/**
 * ******************************************************************
 * TuiyoTablePhotos Class/Object for the Tuiyo platform             *
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
 * TuiyoTablePhotos
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTablePhotos extends JTable
{
	//	`pid` 			  INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, Picture ID
	var $pid			= NULL;  
	//	`aid`			  INTEGER UNSIGNED NOT NULL, album ID
	var $aid 			= NULL;  
	//	`userid` 		  INTEGER UNSIGNED NOT NULL,
	var $userid 		= NULL;
	//	`src_thumb_id` 	  INTEGER UNSIGNED NOT NULL, resource ID of thumbnail;
	var $src_thumb_id	= NULL;
	//	`src_original_id` INTEGER UNSIGNED NOT NULL,
	var $src_original_id= NULL; 
	//	`date_added`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $date_added		= NULL;
	//	`comment_count`   INTEGER UNSIGNED NOT NULL,
	var $comment_count	= NULL;
	//	`last_modified`   TIMESTAMP NOT NULL,
	var $last_modified  = NULL;
	//	`tags` 			  TEXT,	
	var $tags			= NULL;


    /**
     * TuiyoTablePhotos::__construct()
     * @param mixed $db
     */
    public function __construct($db = null)
    {
        return parent::__construct("#__tuiyo_photos", "pid", $db );
    }
    
    
    /**
     * TuiyoTablePhotos::getAllPhotos()
     * Returns an array of photo objects 
     * @param mixed $userID
     * @param mixed $albumID (optional)
     * @param mixed $limit
     * @param mixed $published
     * @param bool $newFirst
     * @return
     */
    public function getAllPhotos($userID, $albumID = NULL, $limitstart = NULL , $published = NULL, $newFirst = TRUE, $limit = 30)
	{
		$dbo 		= $this->_db;
		$albumID	= (!is_null($albumID)&&strval($albumID)<>'') ? "\nAND p.aid=".$dbo->quote( (int)$albumID ) : NULL ;
		
		$order		= ($newFirst) ? " p.date_added DESC" : " p.date_added ASC";
		
		$query 		= "SELECT SQL_CALC_FOUND_ROWS p.aid, p.pid, p.date_added, p.userid, p.comment_count, so.url AS src_original, 
		               st.url AS src_thumb, p.last_modified, p.tags, so.fileTitle AS caption"
					. "\nFROM #__tuiyo_photos AS p"
					. "\nRIGHT JOIN #__tuiyo_resources AS so ON p.src_original_id = so.resourceID"
					. "\nRIGHT JOIN #__tuiyo_resources AS st ON p.src_thumb_id = st.resourceID"
					. "\nWHERE p.userid = ".$dbo->quote( (int)$userID )
					. $albumID	
					. "\nORDER BY $order"
					;
		
		//Set Query
		$dbo->setQuery( $query , $limitstart , $limit );
		
		$photos 	= $dbo->loadObjectList();
		
		$dbo->setQuery('SELECT FOUND_ROWS();'); 		
		
		return (array)$photos ;
	}
	
	public function deleteItem( $resourceID , $pid = NULL ){
		
		$dbo 	= $this->_db;
		$where 	= !is_null($pid) 
				? "\nWHERE id =".$dbo->quote( intval($pid ) )
				: "\nWHERE src_thumb_id =".$dbo->quote( intval($resourceID ) )
				. "\nOR src_original_id =".$dbo->quote( intval($resourceID ) )
				;
		$dbo->setQuery( "DELETE FROM #__tuiyo_photos ".$where ); 
		
		//echo $dbo->getQuery();
		
		if(!$dbo->query()){
			//Do Nothing for now
		}
		return true;
	}

    /**
     * TuiyoTablePhotos::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return object
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
                TuiyoTablePhotos::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTablePhotos($db);
        }
        return $instance;
    }
}
