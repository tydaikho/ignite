<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                           *
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
 * joomla MOdel
 */
jimport( 'joomla.application.component.model' );

/**
 * TuiyoModelTuiyo
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelTuiyo extends JModel
{
	
	/**
	 * TuiyoModelTuiyo::getApplications()
	 * Gets all applications installed.
	 * @param mixed $published
	 * @return
	 */
	public function getApplications( $published = NULL ){
		
		$dbo	=& $this->_db; 
		$pubCond=  (!is_null($published)) ? "WHERE a.published =".$dbo->Quote( (int)$published ) : null ;
	
		$query 	=  "SELECT a.identifier, a.name, a.extID, a.params, a.folder"
				.  "\nFROM #__tuiyo_applications a"
				.  "\nORDER BY a.name ASC"
				.  $pubCond;
		        ;
  		
  		$dbo->setQuery( $query );
  		$apps 	= $dbo->loadAssocList();
  		
  		return $apps;
	}
	
	/**
	 * TuiyoModelTuiyo::addCustomField()
	 * Adds a new simple custom user field to the database;
	 * @param mixed $fieldData
	 * @param bool $extended
	 * @return
	 */
	public function addCustomField( $fieldData , $extended = FALSE )
	{	
		//Load the fields table, and creat an instance
		$table = TuiyoLoader::table("fields", true);
		
		$table->load( null );
		$table->name 	= !empty( $fieldData['name'] ) ? $fieldData['name'] : trigger_error("Invalid Field Name" , E_USER_ERROR );
		$table->title 	= !empty( $fieldData['label'] ) ? $fieldData['label'] : trigger_error("Invalid Field Title" , E_USER_ERROR );
		$table->type 	= !empty( $fieldData['type'] ) ? $fieldData['type'] : trigger_error("Invalid Field Type" , E_USER_ERROR ); 
		$table->indexed = (int)$fieldData['indexed'];
		$table->visible = (int)$fieldData['visible'];
		$table->required= (int)$fieldData['required'];
		
		//Store the table;
		if(!$table->store()){
			trigger_error( $table->getError(), E_USER_ERROR );
			return false;
		}  
		
		return array(
			"fn" => $table->name,
			"id" => $table->ID,
			"fl" => $table->title,
			"fs" => $table->indexed,
			"fv" => $table->visible,
			"fr" => $table->required,
			"ft" => $table->type
		);
	}
	
	/**
	 * TuiyoModelTuiyo::getCustomFields()
	 * If not buildForm, returns a array list of all custom Fields
	 * @param bool $buildForm
	 * @return array of objects
	 */
	public function getAllCustomFields($buildForm = FALSE){
		
		//Load the fields table, and creat an instance
		$table = TuiyoLoader::table("fields", true);
		$fields= $table->listAll( TRUE );
		
		if(!$buildForm){
			return (array)$fields;
		}
	}
	
	/**
	 * TuiyoModelTuiyo::deleteField()
	 * Deletes a field from the database
	 * @param mixed $fieldID
	 * @return
	 */
	public function deleteField( $fieldID )
	{
		//Load the fields table, and creat an instance
		$table = TuiyoLoader::table("fields", true);
		
		//Load the field;
		$table->load( (int)$fieldID );
		
		//Delete The field
		if(!$table->delete()){
			trigger_error($table->getError(), E_USER_ERROR);
			return false;
		}
		//OK
		return true;
	}
}