<?php
/**
 * ******************************************************************
 * TuiyoTableGroupsActivity Class/Object for the Tuiyo platform     *
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
 * TuiyoGroupsActivity
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoGroupsActivity extends JTable
{

	//`activityID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $activityID 	= null;
	//`memberID` INTEGER UNSIGNED NOT NULL,
	var $memberID		= null;
	//`userID` INTEGER UNSIGNED NOT NULL,
	var $userID			= null;
	//`userName` VARCHAR(45) NOT NULL,
	var $userName		= null;
	//`inReplyTo` INTEGER UNSIGNED,
	var $inReplyTo		= null;
	//`aType` VARCHAR(45) NOT NULL DEFAULT 'topic' COMMENT 'comment, like, resource, dislike',
	var $aType			= null;
	//`groupID` INTEGER UNSIGNED NOT NULL,
	var $groupID		= null;
	//`attachment` INTEGER UNSIGNED,
	var $attachment		= null;
	//`dateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $dateTime		= null;
	//PRIMARY KEY (`activityID`)
  
    /**
     * TuiyoGroupsActivity::__construct()
     * @param mixed $db
     * @return
     */
    public function __construct($db)
    {
    	parent::__construct("#__tuiyo_groups_activity" , "activityID" , $db );
    }

    /**
     * TuiyoGroupsActivity::getInstance()
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
                TuiyoGroupsActivity::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoGroupsActivity($db);
        }
        return $instance;
    }
}
