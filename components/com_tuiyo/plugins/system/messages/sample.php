<?php
/**
 * ******************************************************************
 * Sample Profile Plugin                                            *
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


class TuiyoPluginSample extends TuiyoEventsListener{
	
	public function onProfileDelete( $args = null ){
		
		$document 	= $GLOBALS['API']->get("document");
		$document->enqueMessage( "A pofile has just been created" , "notice" );
		
		//var_dump( $args );
	}
	
}