<?php
/**
 * Handles Database Methods
 *
 * @copyright  2008 tuiyo Platform
 * @license    http://platform.tuiyo.com/license   BSD License
 * @version    Release: $Id$
 * @link       http://platform.tuiyo.com/
 * @author 	   livingstone[at]drstonyhills[dot]com 
 * @access 	   Public 
 * @since      1.0.0 alpha
 * @package    tuiyo
 */
 
class TuiyoDatabase{
	/**
	 * DBMS type
	 */
	private $_type    		= 'mysql';

	/**
	 * errors
	 */
	private $_errors  		= '';
	
	/**
	 * error Handle
	 */
	public $errorHandle  	= '';
	
	/**
	 * Last set Query
	 */
	private $_query   		= '';
	
	/**
	 * Last run Result
	 */
	private $_result  		= '';
	
	/**
	 * DBMS connection resource id
	 */
	private $_resourceID 	= '';
	
	/**
	 * DBMS connection resource id
	 */
	public $isConnected 	= FALSE;
	
	

	/**
	 * TuiyoDatabase::__constructor()
	 * TuiyoDatabase constructor
	 * 
	 * @param string $dbmsType / Databse type / Capitalized
	 * @return void
	 */
	public function TuiyoDatabase( $dbmsType = 'MYSQL')
	{}
	
	/**
	 * TuiyoDatabase::getDBO()
	 * Get and Instance of the database objects Only creates a new object if existing one does not Exists
	 * 
	 * @return
	 */
	public function getDBO()
	{}
	
	/**
	 * TuiyoDatabase::connect()
	 * Connects to the databse using the default DBMS
	 * 
	 * @param string $name database name
	 * @param string $server default is localhost
	 * @param string $username if not provided default is used
	 * @param string $password not stored in the class
	 * @return bool TRUE on success and FALSE on failure
	 */
	public function connect($name = '', $server = 'localhost', $username = '', $password = '')
	{}
	
	/**
	 * TuiyoDatabase::_setDatabase()
	 * Private function called by Class Only. Please DO NOT USE
	 * 
	 * @param string $name database name
	 * @param string $server default is localhost
	 * @param string $username if not provided default is used
	 * @param string $password not stored in the class
	 * @return bool TRUE on success and FALSE on failure
	 */
	private function _setDatabase($name, $server, $username, $password )
	{}
	
	/**
	 * TuiyoDatabase::getResourceID()
	 * Returns the datbase resource ID
	 * 
	 * @return bool FALSE if not connected / ID if found
	 */
	public function getResourceID()
	{}
	
	/**
	 * TuiyoDatabase::getTable()
	 * Gets and Entire Table Representation Class
	 * 
	 * @return  bool FALSE if not found / object Table if found
	 */
	public function getTable()
	{}
	
	
	/**
	 * TuiyoDatabase::tableExists()
	 * Checks if a table with the specified name exists in the given database
	 * 
	 * @param mixed $tableName
	 * @return bool  TRUE if table exists or FALSE if not found
	 */
	public function tableExists($tableName)
	{}
	
	/**
	 * TuiyoDatabase::information()
	 * Gets Current Database Information 
	 * 
	 * @return bool FALSE on error / informative Array if found
	 */
	public function information()
	{}
	
	/**
	 * TuiyoDatabase::getCurrentUser()
	 * Checks the current Databse User
	 * 
	 * @return bool FALSE if not connect / User name if found
	 */
	public function getCurrentUser()
	{}
	
	/**
	 * TuiyoDatabase::getLastQuery()
	 * Returns Last executed Query
	 * 
	 * @return string Query represantion
	 */
	public function getLastQuery()
	{}
	
	/**
	 * TuiyoDatabase::getLastResult()
	 * Returns results from last Executed Query
	 * 
	 * @param mixed $resourceID (option) database connection resource
	 * @return array result representation, 
	 */
	public function getLastResult( $resourceID = NULL , $convertToArray = TRUE )
	{}
	
	/**
	 * TuiyoDatabase::doQuery()
	 * Executes the currently stored query, which could be overidden as arg
	 * 
	 * @param mixed $query (optional) defaults to setQuery result
	 * @param int $limit (optional) helpful for pagination
	 * @param bool $cacheResult (option) defaults TRUE for result storing
	 * @return
	 */
	public function doQuery( $query='' , $limit = NULL , $cacheResult = FALSE)
	{}
	
	/**
	 * TuiyoDatabase::setQuery()
	 * Sets Query for subsequent execution
	 * 
	 * @param mixed $query represantion for later execution
	 * @param int $limit for pagination,
	 * @param bool $runNow auto run query after execution, get results with getLastResult
	 * @return
	 */
	public function setQuery( $query , $limit=NULL, $runNow = FALSE)
	{}
	
	/**
	 * TuiyoDatabase::getAffectedRows()
	 * Returs the affected rows from last Query
	 * 
	 * @param bool $fromLastQuery defaults to true
	 * @param string if $fromLastQuery is FALSE provide query to check affected ROWS
	 * @return
	 */
	public function getAffectedRows($fromLastQuery = TRUE, $query='')
	{}
	
	/**
	 * TuiyoDatabase::loadResultObject()
	 * Loads results from the last run query as an object representation
	 * 
	 * @param string $query option query to run
	 * @param int $limit for auto pagination of results
	 * @param bool $fieldNameAsKey user field name as key
	 * @return bool FALSE on error / Object Results on success
	 */
	public function loadResultObject($query='', $limit = NULL , $fieldNameAsKey = TRUE )
	{}
	
	/**
	 * TuiyoDatabase::loadResultArray()
	 * Loads Results from last run Query in an array representation
	 * 
	 * @param string $query option query to run
	 * @param int $limit 
	 * @param bool $fieldNameAsKey if false, field Names are represnted as Numbers
	 * @return
	 */
	public function loadResultArray($query='', $limit = NULL , $fieldNameAsKey = TRUE )
	{}
	
	
	/**
	 * TuiyoDatabase::arrayToJSON()
	 * Converts an entire array Represantion to valid JSON
	 * 
	 * @param mixed $arrayToJSON the array to convert to JSON
	 * @return bool FALSE on error / valid encoded JSON on success
	 */
	public function arrayToJSON( $arrayToJSON )
	{}
	
	/**
	 * TuiyoDatabase::arraytoXML()
	 * Converts and array represantion to valid XML with Keys as Tag Names
	 * 
	 * @param mixed $arrayToXML array to convert
	 * @return bool FALSE on error / valid XML on success
	 */
	public function arraytoXML( $arrayToXML )
	{}
	
	/**
	 * TuiyoDatabase::getErrors()
	 * Returns any errors while database execution
	 * 
	 * @param bool $showSQL
	 * @return
	 */
	public function getErrors($showSQL = TRUE )
	{}
	
	
	/**
	 * TuiyoDatabase::_setErrors()
	 * set any errors while database execution
	 * 
	 * @param mixed $error
	 * @return
	 */
	private function _setErrors($error)
	{}
	
	
}
