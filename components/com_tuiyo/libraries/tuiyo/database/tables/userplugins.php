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

class TuiyoTableUserPlugins extends JTable{
	
	//  `pluginID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $id 			= null;
	//  `name` VARCHAR(45) NOT NULL,
	var $name 			= null;
	//  `type` VARCHAR(45) NOT NULL,
	var $type			= null;
	//which user
	var $userid 		= null;
	//  `access` VARCHAR(45) NOT NULL DEFAULT 0,
	var $privacy		= 0 ;
	//  `isCore` BOOLEAN NOT NULL DEFAULT FALSE,
	var $lastupdated	= false;
	//  `params` TEXT,	
	var $params			= null;
	
	
	/**
	 * TuiyoTablePlugins::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db){
		parent::__construct( '#__tuiyo_userplugins', 'id', $db );
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $service
	 * @param unknown_type $userID
	 */
	public function deleteService($service, $userID){
		
		$dbo 	= $this->_db ;
		$query 	= "DELETE  FROM #__tuiyo_userplugins WHERE userid=".$dbo->Quote( (int)$userID )." AND name=".$dbo->Quote((string)$service);
		
		$dbo->setQuery( $query );
		
		//echo $dbo->getQuery();
		
	 	if(!$dbo->query()){
	 		return false;
	 	}
	 	return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param interger $userID
	 * @param boolean $incParams
	 */
	public function loadUserPlugins($userID, $incParams = false, $type="service"){
		
		$dbo 	= $this->_db ;
		$query 	= "SELECT * FROM #__tuiyo_userplugins as p WHERE p.userid=".$dbo->Quote( (int)$userID )." AND p.type=".$dbo->Quote((string)$type);
		
		$dbo->setQuery( $query );
		
		$plugins = $dbo->loadAssocList();
	
		return $plugins;
		
	}
	
	public function findUserPlugin($userID, $plugin){
		
		$dbo	= $this->_db;
		$query 	= "SELECT * FROM #__tuiyo_userplugins as p WHERE p.userid=".$dbo->Quote( (int)$userID )." AND p.name=".$dbo->Quote((string)$plugin);
		
		$dbo->setQuery( $query );
		
		$plugins = $dbo->loadAssocList();
		
		return isset($plugins[0])?$plugins[0]:array();
		
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
				TuiyoTableUserPlugins::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableUserPlugins( $db  )	;	
		}
		return $instance;	 
	 }	
}