<?php
/**
 * ******************************************************************
 * TuiyoTableCategories Class/Object for the Tuiyo platform   *
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

defined('TUIYO_EXECUTE') || die('Restricted access');

/**
 * TuiyoTableGroupsCategories
 * @package Tuiyo For Joomla
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableCategoriesMaps extends JTable{
	
	//  `catID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	var $id 			= null;
	//	`parentID` INTEGER UNSIGNED,
	var $resourceid		= 0;
	//	`cName` VARCHAR(100) NOT NULL,
	var $ownerid		= null;
	//left
	var $maptype		= null;
	//right
	var $categoryid		= null;
	//Stores the category parameters
	var $params 		= null;

	
	/**
	 * TuiyoTableGroupsCategories::__construct()
	 * @param mixed $db
	 * @return
	 */
	public function __construct($db)
	{
		parent::__construct("#__tuiyo_categories_maps" , "id", $db );
	}
	
	
	public function deteleMap( $mapID ){}
	
	public function addMap(){}
	
	public function mapToCategory($resourceid, $ownerid){
		
		$dbo 	= $this->_db;
		$query 	= "SELECT * FROM #__tuiyocategories_maps as m WHERE m.resourceid=".$dbo->Quote( (int) $resourceid )
				. "\n AND m.ownerid=".$dbo->Quote( (int)$ownerid );
				
		$dbo->setQuery( $query );
		
		$rows 	= $dbo->loadObjectList();
		
		return $rows;
		
	}
	

    /**
     * TuiyoTableGroupsCategories::getInstance()
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
    {
        /** Creates new instance if none already exists ***/
        static $instance = array();

        if (isset($instance) && !empty($instance) && $ifNotExist) {
            if (is_object($instance)) {
                return $instance;
            } else {
                unset($instance);
                TuiyoTableCategoriesMaps::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableCategoriesMaps($db);
        }
        return $instance;
    }
}