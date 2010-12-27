<?php
/**
 * Handles Database Table manipulations
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
 
 class TuiyoTable extends TuiyoDatabase{
 	
 	private $_rowID  		=  NULL ;
 	
 	private $_tableNAME 	= '';
 	
 	/**
 	 * TuiyoTable::TuiyoTable()
 	 * A represantation of a Database Table Object
 	 * 
 	 * @param string $tableName
 	 * @param string $tablePrimaryKey
 	 * @return
 	 */
 	public function TuiyoTable($tableName = '', $tablePrimaryKey = '')
 	{}
 	
 	/**
 	 * TuiyoTable::setError()
 	 * Sets TuiyoTable execution errors, and Handler	
 	 * 
 	 * @param mixed $error
 	 * @return bool TRUE on Error Stored and FALSE on No errors Found
 	 */
 	public function setError($error)
 	{}
 	
 	/**
 	 * TuiyoTable::getColumns()
 	 * Gets all table columns as an array in an array Representation
 	 * 
 	 * @return
 	 */
 	public function getColumns()
 	{}
 	
 	/**
 	 * TuiyoTable::getTableObject()
 	 * Returns a child table object representation
 	 * 
 	 * @return
 	 */
 	public function getTableObject()
 	{}
 	
 	/**
 	 * TuiyoTable::storeRow()
 	 * Saves table row in both DB and cache for subsequent manipulation
 	 * Can be overwritten by child
 	 * 
 	 * @return bool TRUE on success / False on failure to store
 	 */
 	public function storeRow()
 	{}
 	
 	/**
 	 * TuiyoTable::deleteRow()
 	 * Deletes a row with specified id. Can be overwirtten by child
 	 * 
 	 * @param mixed $rowID
 	 * @return bool TRUE on success / FALSE on failure to delete
 	 */
 	public function deleteRow($rowID = NULL)
 	{}
 	
 	/**
 	 * TuiyoTable::insertRow()
 	 * Inserts a new row into table representation and saves in DB
 	 * 
 	 * @param bool $new
 	 * @return
 	 */
 	public function insertRow($new = TRUE )
 	{}
 	
 	/**
 	 * TuiyoTable::loadRow()
 	 * Loads Row with specified ID into tabular object represantation
 	 * 
 	 * @param mixed $rowID
 	 * @return
 	 */
 	public function loadRow($rowID = NULL)
 	{}
 	
 	/**
 	 * TuiyoTable::countRows()
 	 * Counts the rows in a specified Table. Note is different from Affected Rows
 	 * 
 	 * @param string $tableName
 	 * @return
 	 */
 	public function countRows($tableName = '')
 	{}
 	
 	/**
 	 * TuiyoTable::getErrors()
 	 * Gets all execution errors for last run table manipulation process
 	 * 
 	 * @return
 	 */
 	public function getErrors()
 	{}
 	
 	/**
 	 * TuiyoTable::checkTable()
 	 * Check Table is safe for storage / Logs errors with setErrors
 	 * 
 	 * @param string $tableName
 	 * @return
 	 */
 	public function checkTable($tableName = '')
 	{}
 	
 	/**
 	 * TuiyoTable::addColumn()
 	 * Adds a new Column to a Table. 
 	 * 
 	 * @param mixed $columnName
 	 * @param mixed $params array or all params e.g array('AUTO INCREMENT', 'DATE TIME')
 	 * @return
 	 */
 	public function addColumn( $columnName, $params = array())
 	{}
 	
 	/**
 	 * TuiyoTable::removeColumn()
 	 * 
 	 * @param mixed $columnName
 	 * @return
 	 */
 	public function removeColumn( $columnName )
 	{}
 	
 	/**
 	 * TuiyoTable::refreshTable()
 	 * 
 	 * @return
 	 */
 	public function refreshTable()
 	{}
 	
 	/**
 	 * TuiyoTable::update()
 	 * 
 	 * @param mixed $columnName
 	 * @param mixed $set
 	 * @param mixed $where
 	 * @return
 	 */
 	public function update($columnName, $set = array() , $where = array() )
 	{}
 	
 	/**
 	 * TuiyoTable::dropTable()
 	 * Drops the current table if TRUE else the Specified tableName. Good Practive to check table before deleting
 	 * 
 	 * @param bool $currentTable, set to TRUE to delete the current Table
 	 * @param string $tableName
 	 * @return bool TRUE on success / FALSE on errors. Errors Stored.
 	 */
 	public function dropTable($currentTable = TRUE, $tableName = '')
	{}
	
	/**
	 * TuiyoTable::getCurrentTable()
	 * 
	 * @return current Table Name if loaded
	 */
	public function getCurrentTable()
	{}
	
	/**
	 * TuiyoTable::createTable()
	 * Creates a new database table if non exists already with same name. Example
	 * $this->createTable('Tuiyotable', array('Tuiyotable_id','INT 10 AUTOINCREMENT NOT NULL'))
	 * 
	 * @param mixed $tableName
	 * @param array $columnDefinitions
	 * @return void
	 */
	public function createTable($tableName, $columnDefinitions = array('fieldName'=>'attributes'), $type='TEMPORARY', $ifNotExist = FALSE )
	{}
	
	
	/**
	 * TuiyoTable::columnExists()
	 * 
	 * @param mixed $columnName
	 * @return bool TRUE if found or FALSE if absent
	 */
	public function columnExists($columnName)
	{}
	
 	
 }