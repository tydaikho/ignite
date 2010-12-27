<?php
/**
 * ******************************************************************
 * TuiyoTablePlugins Class/Object for the Tuiyo platform            *
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

class TuiyoTablePlugins extends JTable{

	//  `pluginID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $pluginID 		= null;
	//  `name` VARCHAR(45) NOT NULL,
	var $name 			= null;
	//  `key` VARCHAR(45)) NOT NULL,
	var $key			= null;
	//  `type` VARCHAR(45) NOT NULL,
	var $type			= null;
	//  `access` VARCHAR(45) NOT NULL DEFAULT 0,
	var $access			= 0 ;
	//  `isPublished` BOOLEAN NOT NULL DEFAULT FALSE,
	var $isPublished	= false;
	//  `isCore` BOOLEAN NOT NULL DEFAULT FALSE,
	var $isCore			= false;
	//  `params` TEXT,
	var $params			= null;


	/**
	 * TuiyoTablePlugins::__construct()
	 *
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db){
		parent::__construct( '#__tuiyo_plugins', 'pluginID', $db );
	}

	 /**
     * TuiyoTablePlugins::getInstance()
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
				TuiyoTablePlugins::getInstance($db , $ifNotExist );
			}
		}else{
			$instance = new TuiyoTablePlugins( $db  )	;
		}
		return $instance;
	 }

	 /**
	  * Loads a specified group of plugins
	  *
	  * TuiyoTablePlugins::getPluginTypeGroup()
	  *
	  * @param mixed $type
	  * @param bool $plublished
	  * @return
	  */
	 public function getPluginTypeGroup( $type , $plublished = true ){

	 	$dbo 	=&JFactory::getDBO();
	 	$query  = "SELECT p.pluginID, p.key, p.params"
	 			. "\nFROM #__tuiyo_plugins p"
	 			. "\nWHERE p.type = ".$dbo->Quote( strtolower( $type ) )
	 			. "\nAND p.isPublished =".$plublished ;
	 			;
		$dbo->setQuery( $query );

		$results = $dbo->loadAssocList();

	 	//Return the group!
	 	return $results;
	 }
}