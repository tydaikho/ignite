<?php
/**
 * ******************************************************************
 * A sample plugin to send a welcome message                        *
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
  * Send a welcome message on profile create
  * @package Tuiyo For Joomla
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoPluginSystem extends TuiyoEventsListener{
 	
 	
 	public function onProfileHomePageBuild( $args ){
 		
		$document 	= $GLOBALS['API']->get("document");
		//$document->enqueMessage("This is a sample notice from the onProfileHomePageBuild event of the Welcome Plugin" , "notice" );
				
 	}
 	
	public function onAfterProfileDraw($args)
	{
		$document 	= $GLOBALS['API']->get("document");
		//$document->enqueMessage("This is a sample notice from the onAfterProfileDraw event of the Welcome Plugin" , "notice" );
	}
		
 }