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


interface iProfile{
	
	/**
	 * onProfileCreate()
	 * 
	 * @param mixed $args (optional)
	 * @return mixed
	 */
	public function onProfileCreate($args = null);
	
	/**
	 * onProfileSuspend()
	 * 
	 * @param mixed $args (optional)
	 * @return mixed 
	 */
	public function onProfileSuspend($args = null);
	
	/**
	 * onProfileBuild()
	 * 
	 * @param mixed $args (optional)
	 * @return mixed
	 */
	public function onProfileBuild($args = null);
	
	/**
	 * onProfileView()
	 * 
	 * @param mixed $args (optional)
	 * @return mixed
	 */
	public function onProfileView($args = null);
	
	/**
	 * onProfileDelete()
	 * 
	 * @param mixed $args (optional)
	 * @return mixed
	 */
	public function onProfileDelete($args = null);
	
	/**
	 * onProfileUpdate()
	 * 
	 * @param mixed $args
	 * @return mixed
	 */
	public function onProfileUpdate($args = null);
	
}