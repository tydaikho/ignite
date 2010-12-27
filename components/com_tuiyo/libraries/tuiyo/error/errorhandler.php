<?php
/**
 * Tuiyo Error Handling Class
 *
 * @copyright  2008 tuiyo Platform
 * @license    http://platform.tuiyo.com/license   BSD License
 * @version    Release: $Id$
 * @link       http://platform.tuiyo.com/
 * @author 	   livingstone[at]drstonyhills[dot]com 
 * @access 	   Public 
 * @since      1.0.0 alpha
 * @package    tuiyo
 */
 
/**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');

class TuiyoErrorHandler{
	
	private $_debugLevel= '';
	
	private $debug 		= TRUE;
	

	
	/**
	 * TuiyoError::TuiyoError()
	 * 
	 * @return
	 */
	public function __construct(){
		JError::detachHandler();
		
		//error_reporting(E_ERROR | E_USER_ERROR | E_PARSE | E_USER_WARNING  | E_USER_NOTICE);
		//setError level;
		set_error_handler( array($this, '_'));
		//Error Reporting
		
	}
	
	public function __destruct(){
		JError::attachHandler();
	}
	
	public static function _($errNo, $errorMsg, $fileName, $lineNumber, $vars){
		
  		static $errorType 	= array (
	       E_ERROR          => 'ERROR',
		   //E_WARNING        => 'WARNING',
	       E_PARSE          => 'PARSING ERROR',
	       //E_NOTICE         => 'NOTICE',
	       E_CORE_ERROR     => 'CORE ERROR',
	       E_CORE_WARNING   => 'CORE WARNING',
	       E_COMPILE_ERROR  => 'COMPILE ERROR',
	       E_COMPILE_WARNING => 'COMPILE WARNING',
	       E_USER_ERROR     => 'USER ERROR',
	       E_USER_WARNING   => 'USER WARNING',
	       E_USER_NOTICE    => 'USER NOTICE',
	   	   //E_STRICT         => 'STRICT NOTICE',
    	   E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
	 	);
		if(array_key_exists($errNo , $errorType ) ){
			
			JError::raiseError(TUIYO_SERVER_ERROR , "<b>MESSAGE :</b> $errorMsg.<br /><b>FILE</b>: $fileName.<br /> <b>LINE</b>: $lineNumber" );
			//return die;	
		}
	}
	
	/**
	 * TuiyoErrorHandler::logError()
	 * 
	 * @param mixed $errorNo
	 * @param mixed $errorMsg
	 * @param mixed $fileName
	 * @param mixed $lineNumber
	 * @param mixed $vars
	 * @return void
	 */
	public function logError($errorNo, $errorMsg, $fileName, $lineNumber, $vars){}
	
	/**
	 * TuiyoErrorHandler::deleteLogs()
	 * 
	 * @return void
	 */
	public function deleteLogs(){}
	
	/**
	 * TuiyoErrorHandler::getInstance()
	 * 
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($ifNotExist = TRUE){
				/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_object($instance)){
				return $instance;
			}else{
				unset($instance);
				TuiyoErrorHandler::getInstance(  $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoErrorHandler()	;	
		}
		return $instance;
	}
	
}