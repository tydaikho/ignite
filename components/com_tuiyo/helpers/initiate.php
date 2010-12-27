<?php
/**
 * ******************************************************************
 * Main Tuiyo Initiation Class                                      *
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
 
defined('TUIYO_EXECUTE') || die;

/**
 * TuiyoInitiate
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoInitiate{
	
	private static $_errors 	= array();
	
	/**
	 * TuiyoInitiate::TuiyoInitiate()
	 * Application Initiation metod
	 * @return
	 */
	public function TuiyoInitiate()
	{
		TuiyoInitiate::_setDefines(); 
		TuiyoInitiate::_loadErrorHandler();
		TuiyoInitiate::_localize();
		
		jimport('joomla.cache.cache');
		
		$conf 		=& JFactory::getConfig();
		$options 	= array(
			'defaultgroup' 	=> 'com_tuiyo',
			'cachebase' 	=> $conf->getValue('config.cache_path'),
			'lifetime' 		=> $conf->getValue('config.cachetime') * 60,
			'language' 		=> $conf->getValue('config.language'),
			'storage' 		=> 'file' 
		);
		$cache 		= new JCache($options);
		$cache->setCaching( $conf->getValue('config.caching') );
		
		 $GLOBALS['TUIYO_CACHE'] = $cache ;
		
		//Load the parameters for the site!
		if(class_exists('JSite')){
			TuiyoInitiate::_params();
		}
		
		//load all the plugins
		TuiyoInitiate::registerPlugins();
	}
	
	/**
	 * TuiyoInitiate::getTimer()
	 * 
	 * @return
	 */
	public function getTimer()
	{
		$timer = Tuiyoloader::helper('timer' , TRUE);
		if(is_a($timer, 'TuiyoTimer')){
			$instance =& $timer;
		}else{
			$instance = new TuiyoTimer() ;
		}
		return $instance;
	}
	
	/**
	 * TuiyoInitiate::getHooks()
	 * 
	 * @return
	 */
	public function getAPIHooks()
	{}
	
	/**
	 * TuiyoInitiate::_params()
	 * 
	 * @return void
	 */
	private function _params()
	{ 
		$itemID 	= &JRequest::getVar( 'Itemid' , null );
		$path   	= &JSite::getMenu();
		$URI		= &JURI::getInstance();
		
		$params 	= &$path->getParams( (int)$itemID );
		$doFoo		= JRequest::getVar("do" , $params->get("do") );
		$app		= JRequest::getVar("app", $params->get("redirect") );
		$extApp		= JRequest::getVar("redirect" , $app );
		
		JRequest::setVar("do" , $doFoo );
		JRequest::setVar("redirect" , !empty($extApp) ? $extApp : null );
																		
	}
	
	/**
	 * TuiyoInitiate::_localize()
	 * Localizes the system with gettext;
	 * @return void
	 */
	private function _localize(){
		
		//Initialize gettText
		$localize 	=&TuiyoAPI::get("localize");
		$language 	=&JFactory::getLanguage();
		
		$locale 	= TUIYO_DEFAULT_LOCALE ;
		$domain 	= 'system';
		$encoding 	= TUIYO_DEFAULT_ENCODING ;
		
		
		//print_r( $language->getLocale() );
		
		
		
		//return TRUE;
		$localize->initiate($domain, $locale ,$encoding);	
		//return TRUE;	
	}
	
	/**
	 * TuiyoInitiate::getRegistry()
	 * 
	 * @return
	 */
	public function getRegistry()
	{}
	
	/**
	 * TuiyoInitiate::getErrors()
	 * 
	 * @return
	 */
	public function getErrors()
	{}
	
	/**
	 * TuiyoInitiate::getDispatcher()
	 * 
	 * @return
	 */
	public function getDispatcher()
	{}
	
	/**
	 * TuiyoInitiate::_loadErrorHandler()
	 * 
	 * @return
	 */
	private function _loadErrorHandler()
	{
		 TuiyoLoader::import('error.errorhandler');
		 TuiyoErrorHandler::getInstance();	
	}				
	
	/**
	 * TuiyoInitiate::_setDefines()
	 * 
	 * @return
	 */
	private function _setDefines()
	{	
		$lib = strtolower( TUIYO_LIB );
		
		if(require_once TUIYO_LIBRARIES.DS.$lib.DS.'api.php'){
			
			$API 			=& TuiyoAPI::getInstance();
			$GLOBALS['API'] =& $API;
			
			define('TUIYO_API_LOADED' , true);
			
		}
		//start the clock. TIC TOC!
		$TIMER=&self::getTimer();
		$TIMER->startTimer();
		
		//Default Style
		$doc=&JFactory::getDocument();
		$doc->addStyleSheet(TUIYO_LIVE_PATH.'/client/default/css/common.css');
	}
	
	public function registerPlugins(){
		
		$plugins 	= TuiyoLoader::model("applications", true);
		$services 	= $plugins->getAllSystemPlugins();
		$services[] = "system"; //@TODO Crazy way to add the system plugin;
		$groups 	= array(
			"timeline" => array(),
			"profile"  => array(),
		    "messages" => array(),
			"administrator"=>array(),
		);
		
		foreach($services as $service){
			
			$timeline 		= TUIYO_PLUGINS.DS.$service.DS."events.timeline.php";
			$profile  		= TUIYO_PLUGINS.DS.$service.DS."events.profile.php";
			$messages 		= TUIYO_PLUGINS.DS.$service.DS."events.messages.php";
			$administrator 	= TUIYO_PLUGINS.DS.$service.DS."events.administrator.php";
			$authentication = TUIYO_PLUGINS.DS.$service.DS."events.authentication.php";
			
			//Plugins
			if( file_exists($timeline) ){
				$groups["timeline"][$service]=$timeline;
			}
			//Profile
			if(file_exists($profile)){
				$groups["profile"][$service]=$profile;
			}
			//Administrator
			if( file_exists($administrator) ){
				$groups["administrator"][$service]=$administrator;
			}
			
		}
		
		$session 	= JSession::getInstance('none',array());
		$session->set("PLUGIN_GROUPS", $groups);
		
		$GLOBALS['PLUGIN_GROUPS'] = $groups;
	}
	
	/**
	 * TuiyoInitiate::start()
	 * Starts the Tuiyo application
	 * 
	 * @return
	 */
	public function start()
	{
		static $instance;
		
		if( is_object($instance) ){
			return $instance;		
		}else{
			$instance = new TuiyoInitiate()	;	
		}
		return $instance;		
	}
	
}
 
 