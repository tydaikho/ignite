<?php
/**
 * ******************************************************************
 * TuiyoEvent Manager object for the Tuiyo platform              *
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
 * No direct access to these files
 */
defined('TUIYO_EXECUTE') || die;


interface iDelegate{
	
	public function invoke( $args = null );
	
}