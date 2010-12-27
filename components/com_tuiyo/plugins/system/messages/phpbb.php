<?php
/**
 * ******************************************************************
 * A sample plugin                                                  *
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
 * Profile Interface 
 */
 TuiyoLoader::import("events.interfaces.iprofile");
 
 /**
  * phpbbProfilePlug
  * 
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoPluginPhpBB extends TuiyoEventsListener{
 	
 	
 	/**
 	 * phpbbProfilePlug::onProfileUpdate()
 	 * 
 	 * @param mixed $args
 	 * @return void
 	 */
 	public function onProfileBuild( TuiyoControllerProfile $args = NULL ){
 		echo "profile Just Built";
 	}
 	
 }
 
