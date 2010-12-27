<?php
/**
 * ******************************************************************
 * TuiyoTableLog Table Class/Object for the Tuiyo platform          *
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
 * TuiyoTableReports
 * 
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableReports extends JTable{
	
	//`reportID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $reportID		= null;
	//`reason` VARCHAR(45) NOT NULL,
	var $reason 		= null;
	//`reporterID` INTEGER UNSIGNED NOT NULL,
	var $reporterID		= null;
	//`resourceID` INTEGER UNSIGNED NOT NULL,
	var $resourceID		= null;
	//`resourceType` VARCHAR(45) NOT NULL COMMENT 'profile, avatar, audio, comment, video, tag ..(anything)',
 	var $resourceType	= null;
	//`ownerID` INTEGER UNSIGNED NOT NULL,
	var $ownerID		= null;
	//`notes` TEXT NOT NULL,
	var $notes			= null;
	//`resolved` BOOLEAN NOT NULL DEFAULT 0,
	var $resolved		= null;
	//`reportDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	var $reportDate		= null;
	//`resolvedDate` DATETIME,
	var $resolvedDate	= null;

	/**
	 * TuiyoTableReports::getInstance()
	 * 
	 * @param mixed $db
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($db = null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableReports::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableReports( $db  )	;	
		}
		return $instance;	 
 	}
	 
	/**
	 * TuiyoTableReports::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( $db ){
		parent::__construct("#__tuiyo_reports", "reportID" , $db );
	}	
	
}