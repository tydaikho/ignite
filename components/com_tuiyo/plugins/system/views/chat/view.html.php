<?php
/**
 * ******************************************************************
 * View                                                    *
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


/**
 * @package Tuiyo For Joomla
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class SystemServiceViewChat extends TuiyoViewServices
{
	public function __construct(){
		parent::__construct();
	}
    /**
     * @param mixed $data
     * @return
     */
    function showChatBox( $tpl = null )
    {
		//$this->setLayout("chatroom");
    	//echo "show chat box"; die;
        //parent::display($tpl);
    }
}