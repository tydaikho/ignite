<?php
/**
 * ******************************************************************
 * Tuiyo Application entry                                          *
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
 * joomla utitlies
 */
jimport('joomla.application.component.view');

/**
 * Set ContentMimeType
 */
$doc = JFactory::getDocument();
$doc->setMimeEncoding("application/json");


/**
 * TuiyoViewTuiyo
 * 
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoViewTuiyo extends JView
{
	
	public function encode( $data ){
		echo json_encode($data);
	}
	
}