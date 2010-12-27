<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
 * ******************************************************************
 */
 
/**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');


class TuiyoRestProxy{
	
	private $metaData = array();
	
	private $loadedModules = null;
	
	public function __construct(){}
	
	public function call($module, $method, $params = NULL ){
		//First you load the module
		//Check the method exists;
		//Check that we have all required parameters
		//Execute the method
		//Return the response
	}
	
	public function hasModule(){}
	
	public function hasMethod(){} 
	
	public function hasValidParams(){}
	
	public function getModuleMetaData( $module ){}
	
	public function getModule(){
		
		//Extract all metadata using ReflectionClass
			//Store Metadata in $metaData array;
		//Save Module 
		    //store in $loadedModules array
		
	}
	
	public function getInstance(){}
	
}