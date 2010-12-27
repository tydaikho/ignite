<?php
/**
 * ******************************************************************
 * XML object for the Tuiyo platform                               *
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
  * TuiyoXml
  * 
  * @package tuiyo
  * @author Livingstone Fultang
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoXml{
 	
 	/**
 	 * Prepare an xml object
 	 * TuiyoXml::TuiyoXML()
 	 * @param string $type
 	 * @param mixed $file
 	 * @return void
 	 */
 	public function TuiyoXML($file = NULL, $type="simple" ){

 		return $this->loadXML( $file , $type );
 	}
 	
 	/**
 	 * TuiyoXml::loadXML()
 	 * @param mixed $file
 	 * @return
 	 */
 	private function loadXML( $file , $type="simple"){
 		//XML Parser
 		$this->file = &JFactory::getXMLParser( $type );
 		//Load the file
 		if(empty($file) || !file_exists($file)){
 			trigger_error( sprintf( _("XML:%s is not valid") , $file), E_USER_ERROR );
 			return false;
 		}
 		//Load the xml file
 		return $this->file->loadFile( $file );
 	}
 	
 	/**
 	 * TuiyoXml::getInstance()
 	 * @param string $type
 	 * @param bool $ifNotExist
 	 * @return
 	 */
 	public function getInstance($file, $ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/		
		return  new TuiyoXml( $file )	;	
  	}
 	
 }