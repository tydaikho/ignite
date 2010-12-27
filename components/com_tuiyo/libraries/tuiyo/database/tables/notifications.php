<?php
/**
 * ******************************************************************
 * TuiyoTableNotifications Table  Class/Object for the Tuiyo platform                              *
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
 * TuiyoTableNotifications
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableNotifications extends JTable{

	//`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	var $id 		= null;
	//`title` text CHARACTER SET latin1 NOT NULL,
	var $title 		= null;
	//`template` text CHARACTER SET latin1,
	var $template	= null;
	//`link` varchar(100) NOT NULL,
	var $link		= null;
	//`application` varchar(50) NOT NULL DEFAULT 'Profile',
	var $linktitle 	= null;
	
	var $application= null;
	//`status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0 = unread, 1= read , 2=solved if in moderation queue',
	var $status 	= null;
	//`type` varchar(100) NOT NULL,
	var $type 		= null;  //Template name e.g newUserReport
	//`userid` int(11) NOT NULL,	
	var $userid 	= null;
	//`noticetime` TIMESTAMP NOT NULL
	var $noticetime = null;
	
	public function __construct($db){
		parent::__construct("#__tuiyo_notifications", "id", $db );
	}	
	
	/**
	 * TuiyoTableNotifications::getUserNotifications()
	 * Gest a list of all user notifications
	 * @param mixed $userID
	 * @return
	 */
	public function getUserNotifications( $userID , $limitstart = 0, $limit = 5)
	{
		$dbo 	= $this->_db ;
		$query 	= "SELECT SQL_CALC_FOUND_ROWS n.* FROM #__tuiyo_notifications n WHERE n.userid = ".$dbo->quote( (int)$userID )
				. "\nORDER BY n.noticetime DESC"
				; 
		$dbo->setQuery( $query , $limitstart , $limit);		
		$notices= $dbo->loadObjectList();
		
	 	$dbo->setQuery('SELECT FOUND_ROWS();'); 
		
		return $notices ;
	}
	
	/**
	 * TuiyoTableNotifications::getInstance()
	 * Gets an instance of the TuiyoNotification Table
	 * @param mixed $db
	 * @param bool $ifNotExits
	 * @return
	 */
	public function getInstance($db=null, $ifNotExits = true){
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableNotifications::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableNotifications( $db  )	;	
		}
		return $instance;
	}	
}