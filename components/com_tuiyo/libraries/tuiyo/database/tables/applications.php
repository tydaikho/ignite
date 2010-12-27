<?php
/**
 * ******************************************************************
 * TuiyoTableExtensions Class/Object for the Tuiyo platform       *
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
 * TuiyoTableExtensions
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableApplications extends JTable{
	
	var $extID 			= null;
	
	var $name 			= null;
	
	var $identifier 	= null;
	
	var $icon 			= null;
	
	var $access 		= null;
	
	var $ordering 		= null;
	
	var $published 		= null;
	
	var $installedDate 	= null;
	
	var $lastUpdated	= null;
	
	var $userCount		= null;
	
	/**
	 * TuiyoTableExtensions::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db=null){
		parent::__construct("#__tuiyo_applications", "extID", $db );	
	}
	
	/**
	 * TuiyoTableApplications::getAll()
	 * Gets All applications from the Tuiyo tables All, fields included
	 * @param mixed $published
	 * @return array of apps
	 */
	public function getAll($published = null ){
		
		$db 		= $this->_db;
		$published 	= (!empty($published)&&$published)? 
		              "WHERE a.published =".$db->Quote( (int)1 ) : null;
		$query 		= "SELECT a.* FROM #__tuiyo_applications a"
					. "\n".$published
					. "\nORDER BY a.identifier ASC"
					;
		
		$db->setQuery( $query );
		$rows 		= $db->loadAssocList( );

		return (array)$rows ;
		
	}
	
	/**
	 * TuiyoTableapplications::incrAppUserCount()
	 * Increments the Application userCount by specified value
	 * @param mixed $appID
	 * @param integer $increment
	 * @return void
	 */
	public function incrAppUserCount($appID, $increment = 1){
		
		$db 	= $this->_db;
		$query 	= "UPDATE #__tuiyo_applications"
		. 		  "\nSET usersCount = usersCount+".(int)$increment
		. 		  "\nWHERE extID=".$db->Quote( (int)$appID )
		;
		
		$db->setQuery( $query );
		$db->query( );
		
		return true;
		
	}
	
	/**
	 * TuiyoTableapplications::decrAppUserCount()
	 * Decreses use count Same as above with -1 but ..
	 * @param mixed $appID
	 * @param integer $increment
	 * @return
	 */
	public function decrAppUserCount($appID, $increment = 1){
		
		$db 	= $this->_db;
		$query 	= "UPDATE #__tuiyo_applications"
		. 		  "\nSET usersCount = usersCount-".(int)$increment
		. 		  "\nWHERE extID=".$db->Quote( (int)$appID )
		;
		
		$db->setQuery( $query );
		$db->query( );
		
		return true;
		
	}	
	
	/**
	 * TuiyoTableApplications::getSingleApp()
	 * Gets a single application entry, Note this is not a user APP
	 * @param mixed $appIdentifier
	 * @return
	 */
	public function getSingleApplication( $appIdentifier ){
		
		$db 		= $this->_db;
		$query 		= "SELECT a.* FROM #__tuiyo_applications a"
					. "\nWHERE a.identifier=".$db->Quote( strtolower( $appIdentifier ) )
					. "\nLIMIT 1"
					;
		$db->setQuery( $query );
		$rows 		= $db->loadAssocList( );
		
		//No results
		if(sizeof($rows)<1){	
			return array();
		}
		
		return (array)$rows ;		
	}
	
    /**
     * TuiyoTableExtensions::getInstance()
     * 
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db=null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableApplications::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableApplications( $db  )	;	
		}
		return $instance;	 
	 }		
}