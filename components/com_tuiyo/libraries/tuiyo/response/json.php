<?php
/**
 * JSON parser for Tuiyo
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

/**
 * Import Document 
 */
TuiyoLoader::import('response.document');
 

/**
 * TuiyoJSON handling handling class
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoJSON extends TuiyoDocument{
 	
 	private $parseTime = '';
 	
 	private $_errors   = array();
 	
 	/**
 	 * TuiyoJSON::TuiyoJSON()
 	 * 
 	 * @return
 	 */
 	public function TuiyoJSON()
 	{}
 	
 	/**
 	 * TuiyoJSON::encode()
 	 * 
 	 * @param mixed $value
 	 * @return
 	 */
 	public function encode($value)
 	{}
 	
 	/**
 	 * TuiyoJSON::decode()
 	 * 
 	 * @param mixed $value
 	 * @return
 	 */
 	public function decode($value)
 	{}
 	
 	/**
 	 * TuiyoJSON::getJSON()
 	 * 
 	 * @return
 	 */
 	public function getJSON()
 	{}
 	
 	/**
 	 * TuiyoJSON::setError()
 	 * 
 	 * @param mixed $error
 	 * @return
 	 */
 	private function setError($error)
 	{}
 	
 	/**
 	 * TuiyoJSON::getErrors()
 	 * 
 	 * @return
 	 */
 	public function getErrors()
 	{}
 	
 	
	/**
	 * Gets and instance of the JSON class
	 * TuiyoJSON::getInstance()
	 * 
	 * @return
	 */
	public function getInstance()
 	{	
		static $instance;
		
		if( is_object($instance) ){
			return $instance;		
		}else{
			$instance = new TuiyoJSON()	;	
		}
		return $instance;		
 	}
 	
 }