<?php
/**
 * ******************************************************************
 *  API object for the Tuiyo platform                               *
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
 
 
 defined('TUIYO_EXECUTE') or die('Restricted Access');
 
 /**
  * TuiyoAPI
  * @package tuiyo
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoAPI{
 	
 	
 	private static $_loaded 	= FALSE;
 	/**
 	 * Api Instantiated classes
 	 */
 	private static $_instances 	= array();
 	/**
 	 * Debug the applications
 	 */
 	private static $_debug   	= FALSE;
 	/**
 	 * Custom Execution hooks
 	 */
 	public static $userHooks  	= TRUE;
 	
 	/**
 	 * For simple API class loads
 	 */
 	private static $_apiMap 	= array(
 		"table"		=> "database.table",
 		"ini"		=> "filesystem.ini",
 		"xml"		=> "filesystem.xml", 		
 		"image"		=> "filesystem.image",
		"folder"	=> "filesystem.folder",
 		"file"		=> "filesystem.file",
 		"form"		=> "interface.form",
 		"document"	=> "response.document",
 		"json"		=> "response.json",
 		"validate"	=> "request.validate",
 		"params"	=> "user.params",
 		"authentication"=>"user.authentication",
 		"activity"	=> "user.activity",
 		"relations"	=> "user.relations",
 		"session"	=> "user.session",
 		"privacy"	=> "user.privacy",
 		"error"		=> "error.errorhandler",
 		"version"	=> "database.version",
 		"imagemanipulation" => "filesystem.imagemanipulation",
 		"localize"	=> "locale.localize",
 		"notify"	=> "mail.notify"
    ); //if not in array, use as *is*
 	
 	/**
 	 * TuiyoAPI::TuiyoAPI()
 	 * 
 	 * @return
 	 */
 	public function TuiyoAPI()
 	{	
 		TuiyoLoader::import("events.interfaces.idelegate");	
	    TuiyoLoader::import("events.eventslistener");	
        TuiyoLoader::import("events.eventhandler" );
 		TuiyoLoader::import("events.eventloader");
 		
		TuiyoLoader::import("events.delegate"); 
		
		$GLOBALS["events"] = new TuiyoEventHandler( );
		
 		self::$_loaded = TRUE;
 	}
 	
 	/**
 	 * TuiyoAPI::array2Object()
 	 * Converts an Array to an Object
 	 * @param mixed $array
 	 * @return object
 	 */
 	public function array2Object( $array )
	 {
		$object = new stdClass();
	   if (is_array($array) && count($array) > 0) {
	      foreach ($array as $name=>$value) {
	         $name = strtolower(trim($name));
	         if (!empty($name)) {
	            $object->$name = $value;
	         }
	      }
	   }
	   return $object;	 	
	 }
	 
	 /**
	  * TuiyoAPI::object2Array()
	  * Converst an Object back to an array
	  * @param mixed $object
	  * @return array;
	  */
	 public function object2Array( $object )
	 {}
	 
	 /**
	  * TuiyoAPI::parseINI()
	  * Global function to parse any ini file
	  * @param mixed $filePath path to ini file
	  * @param mixed $cChars comment characters
	  * @return array of key value pairs
	  */
	 public function parseINI($filePath , $cChars = array(";", "#", "//")){
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		
		$sections 	= array();
        $values 	= array(); if(!JFile::exists($filePath)){return $values; }
        $lines 		= file( $filePath ); if(is_bool($lines)){ return $values; }
        $lineNo 	= 0;

        foreach ($lines as $line):
            if (empty($line)||in_array(substr($line, 0, 1), $cChars)) {
                $lineNo++;
                continue;
            }
            list($key, $value)  = explode('=', $line, 2);
            $values[trim($key)] = trim($value); 
            $lineNo++;
        endforeach;
        
        return $values;
	 }
 	
 	/**
 	 * TuiyoAPI::debugAPI()
 	 * 
 	 * @param bool $mode
 	 * @return
 	 */
 	public function debugAPI($mode = FALSE){
 		self::$_debug = (bool)$mode;
 		TuiyoAPI::reset();
 	}
 	
 	/**
 	 * TuiyoAPI::random()
 	 * Generates a random String length
 	 * @param integer $length
 	 * @return
 	 */
 	public function random($length = 6 ){
		$code = md5(uniqid(rand(), true));
		if ($length != "") 
			return substr($code, 0, $length);
		else 
			return $code;
 	}
 	
 	/**
 	 * TuiyoAPI::getDebugMode()
 	 * 
 	 * @return void
 	 */
 	public function getDebugMode(){
 		return TuiyoAPI::_debug ;
 	}
 	
 	/**
 	 * TuiyoAPI::reset()
 	 * 
 	 * @return
 	 */
 	public function reset()
 	{ /* Shuts down every thing */}
 	
 	
 	/**
 	 * TuiyoAPI::getInstance()
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
			$instance = new TuiyoAPI()	;	
		}
		return $instance;	
  	}
 	
 	
 	/**
 	 * TuiyoAPI::import()
 	 * @param mixed $classScope
 	 * @return void
 	 */
 	public function get( $classScope , $parameters = null , $instance = true)
 	{
		 if($instance){
		 	if(in_array($classScope , self::$_instances) ){
		 		return self::$_instances[$classScope];		 	
		 	}		 		 		 		 
		 }
		 //create a new one!
		 $classScope 	= strtolower($classScope);
		 $classDotScope = (array_key_exists($classScope , self::$_apiMap )) 
		 				? self::$_apiMap[$classScope] : $classScope;
		 //import 
		 $APIClass 		= TuiyoLoader::library( $classDotScope , $parameters );
		 
		 //return the object
		 if(is_object($APIClass)){
		 	self::$_instances[$classScope] = $APIClass;
		 	return $APIClass;		 
		 }			
 	}
 	
 	/**
 	 * TuiyoAPI::getURL()
 	 * Gets content from the specified URL
 	 * @param mixed $URL
 	 * @return response
 	 */
 	public function getURL( $url )
	 { 
	 	TuiyoLoader::helper("parameter");
	 	
	 	$params = &TuiyoParameter::load("global");

	 	$prName	= $params->get("serverHttpProxyName", null );
	 	$prPort	= $params->get("serverHttpProxyPort", null );
	 	
	 	//If requesting via proxy User other method
	 	if(!empty($prName) && !empty($prPort)){
	 		return TuiyoAPI::getUrlViaProxy( $url , $prName, $prPort );
	 	}
	 	
 		$parsed = parse_url($url);
	    $host 	= $parsed["host"];
	    $port 	= (!isset( $parsed["port"] ) || $port==0 ) ? 80 : $parsed["port"];
	    $path 	= $parsed["path"];
	    
	    if ($parsed["query"] != "") $path .= "?".$parsed["query"];
	
	    $out 	= "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	    $fp 	= fsockopen($host, $port, $errno, $errstr, 30);
		$content= null;
		$body 	= false;
	    
		$bytes 	= fwrite($fp, $out);
		    while (!feof($fp)) {
		        $lfp = fgets($fp, $bytes);
		        if ( $body ) $content.= $lfp;
		        if ( $lfp == "\r\n" ) $body = true;
		    }
	    fclose($fp);
	   
	    return $content;
	}
	
	/**
	 * TuiyoAPI::getUrlViaProxy()
	 * 
	 * @param mixed $URL
	 * @param mixed $proxyName
	 * @param mixed $proxyPort
	 * @return
	 */
	public function getUrlViaProxy( $URL , $proxyName = NULL , $proxyPort = NULL )
	{
		if(empty($proxyName) || empty($proxyPort)){
	 		TuiyoLoader::helper("parameter");
	 	
	 		$params 	= &TuiyoParameter::load("global");
		 	$proxyName	= $params->get("serverHttpProxyName", null );
	 		$proxyPort 	= $params->get("serverHttpProxyPort", null );;
 		}
 		$parsed 	= parse_url($URL);
	    $host 		= $parsed["host"];
	    $path 		= $parsed["path"]; 		
		
		$out		= "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
        $fp 		= fsockopen($proxyName, $proxyPort );
		$content	= null;
		$body 		= false;
		
        if (!$fp) {
            return false;
        }
        $bytes 		= fputs($fp, "GET $URL HTTP/1.0\r\nHost: $proxyName\r\n\r\n");
        	while (!feof($fp)) {
	        	$lfp= fgets($fp, $bytes);
		        if ( $body ) $content.= $lfp;
		        if ( $lfp == "\r\n" ) $body = true;
        	}
        fclose($fp);
        
        //$content 	= substr($content, strpos($content, "\r\n\r\n") + 4);
        
		
		return $content; 
	}
 	
 	/**
 	 * TuiyoAPI::postToURL()
 	 * Posts data to the specified UR
 	 * @param mixed $url
 	 * @param mixed $postData
 	 * @return response
 	 */
 	public function postToURL($url, $postData = array()){
 		
 	}
 	
 	/**
 	 * TuiyoAPI::close()
 	 * @return void
 	 */
 	public function close()
 	{
 		//empty all instances!
 		self::$_instances = array(); 	
 	}
 	
 	/**
 	 * TuiyoAPI::loadedInstances()
 	 * @return
 	 */
 	public function loadedInstances(){
 		return self::$_instances; 	
 	}
 }