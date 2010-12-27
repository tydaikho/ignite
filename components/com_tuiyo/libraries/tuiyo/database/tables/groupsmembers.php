<?php
/**
 * ******************************************************************
 * TuiyoTableGroupsMembers Class/Object for the Tuiyo platform      *
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
  * TuiyoTableGroupsMembers
  * @package Tuiyo For Joomla
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoTableGroupsMembers extends JTable{
 	
	//`memberID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $memberID 		= null;
	//`userID` VARCHAR(45) NOT NULL,
	var $userID			= null;
	//`joinDate` VARCHAR(45) NOT NULL,
	var $joinDate		= null;
	//`lastSeen` DATETIME NOT NULL,
	var $lastSeen		= null;
	//`rank` VARCHAR(45) NOT NULL,
	var $rank			= null;
	//`params` VARCHAR(45) NOT NULL,
	var $params			= null;
	//`privacy` VARCHAR(45) NOT NULL,
	var $privacy		= null;
	//`groupID` VARCHAR(45) NOT NULL,
	var $groupID		= null;
	//PRIMARY KEY (`memberID`),
	//UNIQUE INDEX `UNIQUE-PAIR`(`groupID`, `userID`)	
 	
 	/**
 	 * TuiyoTableGroupsMembers::__construct()
 	 * @param mixed $db
 	 * @return
 	 */
 	public function __construct($db)
    {
    	parent::__construct("#__tuiyo_groups_members", "memberID", $db );
    }
    
    /**
     * TuiyoTableGroupsMembers::deleteAllMembers()
     * Delete all members from a user group
     * @param mixed $groupID
     * @return void
     */
    public function deleteAllMembers( $groupID ){
    	
    	$dbo 	= $this->_db ;
    	$dbo->setQuery( "DELETE FROM #__tuiyo_groups_members WHERE groupID=".$dbo->quote(  (int)$groupID ) );
	
    	return $dbo->query();
    }
 	
    /**
     * TuiyoTableGroupsMembers::getInstance()
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
                TuiyoTableGroupsMembers::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableGroupsMembers($db);
        }
        return $instance;
    }
 	
 }