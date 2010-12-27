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

/**
 * TuiyoTableTimelinedata
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoTableTimelinedata extends JTable{
	
	var $ID				= null;
	
	var $data			= null;

	
	/**
	 * TuiyoTableTimelinedata::__construct()
	 * 
	 * @param mixed $db
	 * @return void
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__tuiyo_timelinedata', 'ID', $db );
	}
	
    /**
     * TuiyoTableTimelinedata::getInstance()
     * 
     * @param mixed $db
     * @param bool $ifNotExist
     * @return
     */
    public function getInstance($db = null, $ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoTableTimelinedata::getInstance($db , $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoTableTimelinedata( $db  )	;	
		}
		return $instance;	 
	 }	
	
}