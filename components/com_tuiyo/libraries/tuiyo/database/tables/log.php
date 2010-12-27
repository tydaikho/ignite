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
 * TuiyoTableLog
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableLog extends JTable
{
	
	//`logID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $logID 		= null;
	//`dateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	var $dateTime 	= null;
	//`ip` VARCHAR(45) NOT NULL,
	var $id			= null;
	//`client` TEXT NOT NULL,
	var $client 	= null;
	//`title` VARCHAR(45) NOT NULL,
	var $title		= null;
	//`notes` TEXT NOT NULL,
	var $notes		= null;
	//`related` INTEGER UNSIGNED NOT NULL,
	var $related	= null;
	//`caseID` INTEGER UNSIGNED NOT NULL,
	var $caseID		= null;
	//`mentioned` TEXT NOT NULL,
	var $mentioned	= null;
	//`actionType` VARCHAR(45) NOT NULL,
	var $actionType	= null;
	
    /**
     * TuiyoTableLog::getInstance()
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
				TuiyoTableLog::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableLog( $db  )	;	
		}
		return $instance;	 
	 }
	 	
	/**
	 * TuiyoTableLog::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( $db){
		 parent::__construct("#__tuiyo_log" , "logID" , $db );
	}
	
}