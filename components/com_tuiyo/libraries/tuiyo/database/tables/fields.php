<?php 
/**
 * ******************************************************************
 * TuiyoTableGroups Class/Object for the Tuiyo platform             *
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
 * TuiyoTableFields
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableFields extends JTable{ 
	
	//DROP TABLE IF EXISTS `jos_tuiyo_fields`;
	//CREATE TABLE `jos_tuiyo_fields` (
	//  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
	var $ID 			= NULL;
	//  `name` varchar(100) NOT NULL,
	var $name 			= NULL;
	//  `title` varchar(100) NOT NULL,
	var $title 			= NULL;
	//  `descr` text,
	var $descr 			= NULL;
	//  `type` varchar(100) NOT NULL,
	var $type			= NULL;
	//  `maxlength` int(11) DEFAULT NULL,
	var $maxlength		= NULL;
	//  `size` varchar(20) DEFAULT NULL,
	var $size 			= NULL;
	//  `required` enum('0','1') NOT NULL DEFAULT '0',
	var $required		= NULL;
	//  `ordering` int(11) NOT NULL,
	var $ordering		= NULL;
	//  `defaultvalue` varchar(255) DEFAULT NULL,
	var $defaultvalueu	= NULL;
	//  `visible` enum('0','1') NOT NULL DEFAULT '1',
	var $visible		= NULL;
	//  `validation` text,
	var $validation		= NULL;
	//  `attributes` text,
	var $attributes		= NULL;
	//  `indexed` enum('0','1') DEFAULT '1',
	var $indexed		= NULL;
	//  `linkfieldvalue` varchar(255) DEFAULT NULL,
	var $linkfieldvalue = NULL;
	//  `tablename` varchar(90) DEFAULT NULL,
	var $tablename		= NULL;
	//  `options` text,
	var $options		= NULL;
	//  `editable` enum('0','1') NOT NULL DEFAULT '0',
	var $editable		= NULL;
	//  PRIMARY KEY (`id`,`name`)
	//) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
	
    /**
     * TuiyoTableFields::__construct()
     * Constructs the class
     * @param mixed $db
     * @return void
     */
    public function __construct( $db )
    {
        parent::__construct("#__tuiyo_fields","ID", $db);
    }
    
    /**
     * TuiyoTableFields::listAll()
     * Returns a list of all fields in DB
     * @param bool $simpleList
     * @return array of objects
     */
    public function listAll( $simpleList = FALSE )
	{
		$dbo 	= $this->_db;
		$cols 	= ($simpleList)? "f.ID as id, f.name as fn,f.type as ft, f.indexed as fs, f.visible as fv, f.required as fr, f.title as fl" : "f.*";
		$query 	= "SELECT $cols FROM #__tuiyo_fields f"
				. "\nORDER BY f.ordering ASC";
		
		$dbo->setQuery( $query );
		$rows 	= $dbo->loadObjectList();
		
		return $rows;	
    }
    
    /**
     * TuiyoTableFields::getInstance()
     * Gets an instance of the Tablefields class
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
                TuiyoTableFields::getInstance($db, $ifNotExist);
            }
        } else {
            $instance = new TuiyoTableFields($db);
        }
        return $instance;
    }
}