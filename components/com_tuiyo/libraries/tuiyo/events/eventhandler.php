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
 * TuiyoEventHandler
 * 
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoEventHandler{
	
	protected $listeners = array( );
	
	protected $arguments = array( );
	
	
	/**
	 * TuiyoEventHandler::__construct()
	 * 
	 * @return
	 */
	public function __construct(){
		$this->arguments = func_get_args();
	}
	

	/**
	 * TuiyoEventHandler::attachProfileEvent()
	 * 
	 * @param mixed $event
	 * @param mixed $interface
	 * @return void
	 */
	public function attach($event, iDelegate $interface ){
		$this->listeners[$event][] = $interface;
	}
	
	/**
	 * TuiyoEventHandler::getInstance()
	 * 
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		static $instance;
		
		if(is_object($instance)&&!$ifNotExist){
			return $instance;		
		}else{
			$instance = new TuiyoEventHandler()	;	
		}
		return $instance;	
  	}
	
	/**
	 * TuiyoEventHandler::trigger()
	 * 
	 * @return
	 */
	public function trigger( $event , $args = null ){
        
        if(isset($this->listeners[$event]) && is_array($this->listeners[$event])){
        	foreach($this->listeners[$event] as $interface){
        		$params = (is_null($args)) ? $this->arguments : $args ;
        		
        		$interface->arguments = $params ;
        		$interface->invoke( $params );
        	}
        	return count($this->listeners[$event]);
        }
        
        return false;
	}
	
	/**
	 * TuiyoEventHandler::__set()
	 * 
	 * @param mixed $event
	 * @param mixed $interface
	 * @return
	 */
	public function __set($event, $interface){
		$this->attach($event, $interface );
	}
	
	/**
	 * TuiyoEventHandler::__call()
	 * 
	 * @param mixed $event
	 * @param mixed $args
	 * @return
	 */
	public function __call($event, $args){
		$this->trigger();
	}
	
}