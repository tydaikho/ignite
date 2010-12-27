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

interface iApplication{
	
	/**
	 * onApplicationAdd()
	 * 
	 * @param mixed $args
	 * @return
	 */
	public function onApplicationAdd( $args = null);
	
	/**
	 * onApplicationStart()
	 * 
	 * @param mixed $args
	 * @return
	 */
	public function onApplicationStart( $args = null);
	
	/**
	 * onApplicationRemove()
	 * 
	 * @param mixed $args
	 * @return
	 */
	public function onApplicationRemove( $args = null);
	
	/**
	 * onApplicationUpdateConfig()
	 * 
	 * @param mixed $args
	 * @return
	 */
	public function onApplicationUpdateConfig( $args = null );
	
	
}