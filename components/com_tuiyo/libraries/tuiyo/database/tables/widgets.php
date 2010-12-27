<?php
/**
 * ******************************************************************
 * TuiyoWidgetsTable Class/Object for the Tuiyo platform                           *
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
 * TuiyoTableWidgets
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableWidgets extends JTable{
	
	//CREATE TABLE `joomla`.`jos_tuiyo_widgets` (
	//`ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $ID 		= NULL;
	//`parent` INTEGER UNSIGNED NOT NULL,
	var $parent 	= NULL;
	//`userID` INTEGER UNSIGNED NOT NULL,
	var $userID		= NULL;
	//`identifier` VARCHAR(45) NOT NULL,
	var $identifier = NULL;
	//`title` TEXT NOT NULL,
	var $title 		= NULL;
	//`size` INTEGER UNSIGNED NOT NULL COMMENT 'column size percentile',
	var $size 		= NULL;
	//`type` VARCHAR(45) NOT NULL COMMENT 'page, tab, column, widget',
	var $type		= NULL;
	//`file` TEXT NOT NULL COMMENT 'e.g gshark.xml',
	var $file_xml 	= NULL;
	//`params` TEXT NOT NULL COMMENT 'json configuration',
	var $params		= NULL;
	
	var $ordering   = NULL;
	//PRIMARY KEY (`ID`)
	//)
	//ENGINE = MyISAM	
	
	/**
	 * TuiyoTableWidgets::__construct()
	 * @param mixed $db
	 * @return void
	 */
	public function __construct($db){
		parent::__construct("#__tuiyo_widgets" , "ID", $db );	
	}
	
    /**
     * TuiyoTableWidgets::getInstance()
     * Gets and instance of TuiyoTableWidgets
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
				TuiyoTableWidgets::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableWidgets( $db  )	;	
		}
		return $instance;	 
	 }	
	 
	 /**
	  * TuiyoTableWidgets::deleteTab()
	  * Deletes a Tab from the database
	  * @param mixed $tabID
	  * @param mixed $userID
	  * @return TRUE if succeded
	  */
	 public function deleteTab( $tabID, $userID )
	 {
	 	$dbo 	= $this->_db;
	 	$tabID	= $dbo->Quote( (int)$tabID );
	 	$userID = $dbo->Quote( (int)$userID );
	 	
	 	$query1 = "SELECT  w3.ID as delID".
			      "\nFROM #__tuiyo_widgets as t1".
			      "\nLEFT JOIN #__tuiyo_widgets as c2 on c2.parent = t1.ID".
			      "\nLEFT JOIN #__tuiyo_widgets as w3 on w3.parent = c2.ID".
			      "\nWHERE t1.ID = {$tabID} AND t1.userID = {$userID}".
			      "\nUNION".
			      "\nSELECT c2.ID as delID".
			      "\nFROM #__tuiyo_widgets as t1".
			      "\nLEFT JOIN #__tuiyo_widgets as c2 on c2.parent = t1.ID".
			      "\nLEFT JOIN #__tuiyo_widgets as w3 on w3.parent = c2.ID".
			      "\nWHERE t1.ID = {$tabID} AND t1.userID = {$userID}".
			      "\nUNION".
			      "\nSELECT {$tabID} as delID FROM #__tuiyo_widgets"
		;
		$dbo->setQuery( $query1 ); //First we select all IDS to be deleted
		
		$delIDs = $dbo->loadObjectList();
		$dINs  	= array();
		
		foreach($delIDs as $row ){
			if(!empty($row->delID)) $dINs[] = $row->delID ;
		}
		//Now we can delete everything
		if(!empty($dINs)){
			$INs 	= implode(",", $dINs);
			$query2	= "DELETE FROM #__tuiyo_widgets"
			        . "\nWHERE ID IN(".$INs.")"
			        . "\nAND userID ={$userID}"
			        ;
   			$dbo->setQuery( $query2 );
   			if($dbo->query() === FALSE ){
   				JError::raiseError( TUIYO_SERVER_ERROR, $dbo->stderr( ) );
   				return false;
   			}
		}
		return TRUE;
	 }
	 
	 
	 public function getWidgets( $userID ){
	 	
	 	$dbo 	= $this->_db;
	 	$userID = $dbo->quote( (int)$userID );
	 	$query 	= "SELECT"
				. "\nt1.ID as tabID, t1.title as tabTitle, c2.ID as colID, c2.size as colSize, w3.ID as widgetID,"
				. "\nw3.file_xml as widgetURL, w3.title as widgetTitle, w3.params as widgetParams"
				. "\nFROM #__tuiyo_widgets as t1"
				. "\nLEFT JOIN #__tuiyo_widgets as c2 on c2.parent = t1.ID"
				. "\nLEFT JOIN #__tuiyo_widgets as w3 on w3.parent = c2.ID"
				. "\nWHERE t1.userID = {$userID} AND t1.type NOT IN( 'column','widget')"
	 	;
	 	
	 	$dbo->setQuery( $query );
	 	$widgets = $dbo->loadObjectList();
	 	
	 	return (array)$widgets;
	 	
	 }
}