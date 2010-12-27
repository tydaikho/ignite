<?php
/**
 * ******************************************************************
 * Class/Object for the Tuiyo platform                              *
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
 * TuiyoTableTimelinetmpl
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableTimelinetmpl extends JTable{
	
	/**
	 * A unique identifier (interger this time)
	 */
	var $ID				= null;
	
	/**
	 * The current Application responsible for this template
	 */
	var $appName 	    = null;
	
	/**
	 * A unique identifier for this template, e.g.
	 * 	USER_COMMENT, USER_STATUS, etc..
	 */
	var $identifier 	= null;
	
	/**
	 * The nature of the title field
	 * 	e.g {%userA} wrote on {%userB}'s profile',
	 * 	{%userA update their profile}
	 */
	var $title 			= null;
	
	/**
	 * Post-publishing handling options
	 * 	e.g Re-post, hide, comment, etc...
	 */
	var $variables		= null;
	
	/**
	 * The type of story represented in this template
	 * 	e.g Status, Import, Link, Comment, Video, etc....
	 */
	var $body			= null;
	
	/**
	 * A call to action, e.g "view userA profile" , "take the BLAH BLAH Quiz" etc
	 */
	var $actions		= null;
	
	/**
	 * The template mainbody. Be creative!
	 */
	var $resources		= null;
	
	/**
	 * One Line Story or Multiline, ENUM 0 ,, 1
	 */
	var $type			= null;
	
	/**
	 * One Line Story or Multiline, ENUM 0 ,, 1
	 */
	var $thisUserID		= null;
	
	/**
	 * Who did the activity and who should carry it?
	 */
	var $thatUserID		= null;

	
	/**
	 * TuiyoTableTimelinetmpl::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( &$db ){
		parent::__construct( '#__tuiyo_timelinetmpl', 'ID', $db );
	}
	
    /**
     * TuiyoTableTimelinetmpl::getInstance()
     * 
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true )
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableTimelinetmpl::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableTimelinetmpl( $db  )	;	
		}
		return $instance;	 
	 }	
	
}