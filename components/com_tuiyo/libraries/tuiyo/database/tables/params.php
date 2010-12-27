<?php
/**
 * ******************************************************************
 * TuiyoTableParams Table Class/Object for the Tuiyo platform          *
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
 
  defined('TUIYO_EXECUTE') || die;
  
 /**
  * TuiyoTableParams
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoTableParams extends JTable{

 	/**
 	 * TuiyoTableParams::getGroup()
 	 * @param mixed $key
 	 * @param mixed $userID
 	 * @return
 	 */
 	public function getParamGroup( $key, $userID )
    {
        $dbo 	= $this->_db;
 	 	$query 	= "SELECT p.* FROM #__tuiyo_params p"
 	 			. "\nWHERE application=".$dbo->Quote( $key )
 	 			. "\nAND userID=".$dbo->Quote( (int)$userID )
 	 			;
		$dbo->setQuery( $query );
		$r		= $dbo->loadAssocList();

		return (array)$r; 
    } 	
 	
 	/**
 	 * TuiyoTableParams::__construct()
 	 * @param mixed $db
 	 * @return void
 	 */
 	public function __construct( $db )
	 {
 		parent::__construct("#__tuiyo_params" , "paramsID" , $db );
 	 }
 	 
 	 /**
 	  * TuiyoTableParams::updateTable()
 	  * @param mixed $key
 	  * @param mixed $userID
 	  * @param mixed $data
 	  * @return
 	  */
 	 public function updateTable($key, $userID, $data ){
 	 	
 	 	$dbo 	= $this->_db;
 	 	$query 	= "UPDATE #__tuiyo_params p"
 	 			. "\nSET data = ".$dbo->Quote( $data )
 	 			. "\nWHERE application=".$dbo->Quote( $key )
 	 			. "\nAND userID=".$dbo->Quote( (int)$userID )
 	 			;
		$dbo->setQuery( $query );
		$r		= $dbo->query();
		
		return true ;
 	 }
    
	/**
	 * TuiyoTableParams::getInstance()
	 * @param mixed $db
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($db = NULL, $ifNotExist = TRUE )
	 {
		/** Creates new instance if none already exists ***/
		//static $instance = array();
		
		$instance = new TuiyoTableParams( $db  )	;	

		return $instance;	 
	 }
 	
 }