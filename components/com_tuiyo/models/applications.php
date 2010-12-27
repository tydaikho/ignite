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
 * joomla MOdel
 */
jimport( 'joomla.application.component.model' );


/**
 * TuiyoModelApplications
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoModelApplications extends JModel{
	
	/**
	 * TuiyoModelApplications::getSingleApplication()
	 * @param mixed $appName
	 * @param bool $incManifest
	 * @return void
	 */
	public function getSingleApplication( $appName , $incManifest = true )
	{	
		$userObject =& JFactory::getUser();
		$appTable 	=& TuiyoLoader::table("applications");
		$params 	=& TuiyoAPI::get("params" );
		
		$singleApp 	= (array)$appTable->getSingleApplication( $appName );
		
		if(!$incManifest) return $singleApp[0]; //if we don't need the settings form
		
		$ccApp 		= NULL;
		
		foreach( $singleApp as $application){
				
			$newApp 		= new stdClass;
			$newAppID		= strtolower( $application['identifier'] );	
			$newAppXMLFile 	= TUIYO_APPLICATIONS.DS.$newAppID.DS."system.".$newAppID.".xml";
			
			if(!file_exists( $newAppXMLFile )) continue;
			
			$newAppXML 		= TuiyoAPI::get("xml" , $newAppXMLFile );
			$root 			= $newAppXML->file->document;
			$rootName 		= $root->attributes("key") ;
			$rootType 		= $root->attributes("type") ;
						
			$newApp->name   = $newAppID;
			$newApp->icon32 = 'components/com_tuiyo/applications/'.$newAppID.'/icon.png';
			$newApp->title 	= $application['name'];
			$newApp->description= null;
			$newApp->author		= null;
			$newApp->website 	= null;
			$newApp->email		= null;
			$newApp->version 	= null; 
			$newApp->installDate= null;

			foreach((array)$root->configinfo[0]->children() as $info):
				
				$infoName = $info->attributes( "name" );
				$infoValue = $info->attributes("content" );
				
				if(empty($infoName) ||is_array($infoName)) continue;
				if(empty($infoValue)||is_array($infoValue)) continue ;
				
				$newApp->$infoName = $infoValue ;	
			
			endforeach;
			
			//specification				
			$newApp->published 			= $application['published'];
			$newApp->installedDate 		= $application['installedDate'];
			$newApp->lastUpdated		= $application['lastUpdated'];
			$newApp->usersCount			= $application['usersCount'];			
			$newApp->access 			= $application['access'];
			$newApp->ordering 			= $application['ordering'];		
			
			$newApp->hasTimeline 		= isset($newApp->hasTimeline) && strtolower($newApp->hasTimeline) === "true" ? 1 : 0 ;
			$newApp->hasNotifications	= isset($newApp->hasNotifications)&& strtolower($newApp->hasNotifications) === "true"? 1 : 0;
			$newApp->hasSearch 			= isset($newApp->hasSearch)&& strtolower($newApp->hasSearch)=== "true"  ? 1 : 0;
			$newApp->hasProfile			= isset($newApp->hasProfile	)&& strtolower($newApp->hasProfile)=== "true" ? 1	: 0;
			$newApp->hasExternalProfile	= isset($newApp->hasExternalProfile	)&& strtolower($newApp->hasExternalProfile)=== "true" ? 1	: 0;				
			$newApp->id					= $application["extID"] ;
			
			$ccApp = $newApp;
		}
		
		unset( $singleApp );
		
		return (object)$ccApp;	
	}
	/**
	 * 
	 * Gets a single user plugins ...
	 * @param  $userID
	 * @param mixed $type
	 * @param boolean $pluginXML
	 * @param boolean $incParams
	 */
	public function getAllUserPlugins($userID,  $type="services" , $pluginXML=false , $incParams = false){
		
		//Import the Tuiyo Alias of JParameter
		TuiyoLoader::helper("parameter");
		
		$table 		=& TuiyoLoader::table("userplugins" , true );
		$plugins 	= $table->loadUserPlugins( $userID, $incParams );
		
		$_plugins		= array();
		$_pluginsSpec 	= array();
		
		foreach($plugins as $plugin){
			
			if(!$pluginXML) $_plugins[] = $plugin['name'];
			
			$newPluginSpec  = array();
			$newPlugin 		= new stdClass;
			$newPluginID	= strtolower( $plugin['name'] );	
			$newPluginXMLf 	= TUIYO_PLUGINS.DS.$newPluginID.DS."plugin.xml";
			$newPluginLivePath = TUIYO_LIVE_PATH.'/plugins/'.$newPluginID; 
		
			if(!file_exists( $newPluginXMLf )) continue; //this is not a plugin
			
			$newPluginXML 	= TuiyoAPI::get("xml" , $newPluginXMLf );
			$root 			= $newPluginXML->file->document;
			$rootType 		= $root->attributes("type") ;
			
			if((string)$rootType <> "plugin") continue; //this is not a plugin
			
			//Load data
			$pluginParams   = new TuiyoParameter('', $newPluginXMLf );
			
			$pluginParams->setXML( $newPluginXML->file->document );
			
			$newPluginSpec['serviceID']				= $newPluginID;
			$newPluginSpec['serviceDescription']	= $root->description[0]->_data;
			$newPluginSpec['serviceLivePath']		= $newPluginLivePath;
			$newPluginSpec['serviceExecuteJS']		= $newPluginLivePath.'/'.$newPluginID.'.js';
			
			$newPluginSpec['settings_default_html'] = $pluginParams->renderHTML("params", "plugin");
			$newPluginSpec['settings_administrator_html'] = $pluginParams->renderHTML("params", "administrator");
			$newPluginSpec['settings_photos_html'] 	= $pluginParams->renderHTML("params", "photos");
			$newPluginSpec['settings_privacy_html'] = $pluginParams->renderHTML("params", "privacy");
			$newPluginSpec['serviceParams']			= "";
			
			$_pluginsSpec[$newPluginID] = $newPluginSpec ;
			
		}
		
		return (!$pluginXML)? $_plugins: $_pluginsSpec;
		
	}
	
	public function getSingleUserPlugin($userId, $pluginName, $pluginXML=true, $pluginType="services"){
		
		//Import the Tuiyo Alias of JParameter
		TuiyoLoader::helper("parameter");
		
		$upTable 		= TuiyoLoader::table("userplugins", true);
		
		$userPlugin 	= (array)$upTable->findUserPlugin($userId,$pluginName);
		
		//print_R($userPlugin);
		
		//Scan the plugin folder
		$exclude 		= array("system");
		
		$plugin 		= $userPlugin['name'];
		
		$newPluginSpec  = array();
			
		$newPlugin 		= new stdClass;
		$newPluginID	= strtolower( $plugin );	
		$newPluginXMLf 	= TUIYO_PLUGINS.DS.$newPluginID.DS."plugin.xml";
		$newPluginLivePath = TUIYO_LIVE_PATH.'/plugins/'.$newPluginID; 
	
		if(!file_exists( $newPluginXMLf )) return false; //this is not a plugin
		
		$newPluginXML 	= TuiyoAPI::get("xml" , $newPluginXMLf );
		$root 			= $newPluginXML->file->document;
		$rootType 		= $root->attributes("type") ;
		
		if((string)$rootType <> "plugin") continue; //this is not a plugin
		
		//Load data
		$pluginParams   = new TuiyoParameter($userPlugin['params'], $newPluginXMLf );
		
		$pluginParams->setXML( $newPluginXML->file->document );
		
		return $pluginParams;
		
	}
	
	public function getSinglePlugin($pluginName, $pluginXML = true, $pluginType="services"){}
	
	
	/**
	 * Loads system plugins.
	 * @param $type
	 * @param $pluginXML
	 * @param $incSystem
	 * @param $excUserPlugins
	 */
	public function getAllSystemPlugins( $type = "services" , $pluginXML=false , $incSystem = false, $excUserPlugins = false , $userID = null ){
		
		//Import the Joomla Folder Library
		jimport('joomla.filesystem.folders');
		
		//Import the Tuiyo Alias of JParameter
		TuiyoLoader::helper("parameter");
		
		//Scan the plugin folder
		$plugins = JFolder::folders( TUIYO_PLUGINS );
		$exclude = array("system");
		
		//If we want to exclude user plugins
		if($excUserPlugins && isset($userID)){
			$userPlugins = $this->getAllUserPlugins($userID , $type , false);
			$exclude 	= array_merge($exclude, (array)$userPlugins );
		}
		
		$plugins = array_diff($plugins, $exclude); 
		
		if(!$pluginXML) return $plugins ;
		
		//If we have to load the XML files
		$pluginsSpec = array();
		
		foreach( $plugins as $plugin){
			
			$newPluginSpec  = array();
			
			$newPlugin 		= new stdClass;
			$newPluginID	= strtolower( $plugin );	
			$newPluginXMLf 	= TUIYO_PLUGINS.DS.$newPluginID.DS."plugin.xml";
			$newPluginLivePath = TUIYO_LIVE_PATH.'/plugins/'.$newPluginID; 
		
			if(!file_exists( $newPluginXMLf )) continue; //this is not a plugin
			
			$newPluginXML 	= TuiyoAPI::get("xml" , $newPluginXMLf );
			$root 			= $newPluginXML->file->document;
			$rootType 		= $root->attributes("type") ;
			
			if((string)$rootType <> "plugin") continue; //this is not a plugin
			
			//Load data
			$pluginParams   = new TuiyoParameter('', $newPluginXMLf );
			
			$pluginParams->setXML( $newPluginXML->file->document );
			
			$newPluginSpec['serviceID']				= $newPluginID;
			$newPluginSpec['serviceDescription']	= $root->description[0]->_data;
			$newPluginSpec['serviceLivePath']		= $newPluginLivePath;
			$newPluginSpec['serviceExecuteJS']		= $newPluginLivePath.'/'.$newPluginID.'.js';
			
			$newPluginSpec['settings_default_html'] = $pluginParams->renderHTML("params", "plugin");
			$newPluginSpec['settings_administrator_html'] = $pluginParams->renderHTML("params", "administrator");
			$newPluginSpec['settings_photos_html'] 	= $pluginParams->renderHTML("params", "photos");
			$newPluginSpec['settings_privacy_html'] = $pluginParams->renderHTML("params", "privacy");
			
			$pluginsSpec[$newPluginID] = $newPluginSpec ;
			
		}
		
		return $pluginsSpec;
		
	}
	
	/**
	 * TuiyoModelApplications::getSingleUserApplication()
	 * Gets a single user Application', Including user params if any
	 * @param mixed $appName
	 * @param mixed $userID
	 * @param bool $incManifest
	 * @return void
	 */
	public function getSingleUserApplication($appName, $userID, $incManifest=true, $incUserParams = true)
	{
		$userObject =& JFactory::getUser();
		$appTable 	=& TuiyoLoader::table("userapps");
		$params 	=& TuiyoAPI::get("params" );
		
		$singleApp 	= $appTable->getAlluserApps( $userID, $appName );
		
		//Load 	Application
		//Get 	System Params
		//Get 	User Params
				
	}
	
	/**
	 * TuiyoModelApplications::getAllUserApplications()
	 * Gets All User Applications Including user Params if any
	 * @param mixed $userID
	 * @param bool $incManifest
	 * @param bool $incUserParams
	 * @return void
	 */
	public function getAllUserApplications( $userID, $incManifest=true, $incUserParams = false ){
		
		$userObject =& JFactory::getUser();
		$appTable 	=& TuiyoLoader::table("userapps");
		$params 	=& TuiyoAPI::get("params" );
		
		$allApps 	= $appTable->getAllUserApps( $userID, null );
		$allApps 	= (array)$allApps ;   //Make sure its an array;
		
		$cleanArray = array();
		
		//Now
		foreach( $allApps as $application){
			
			$newApp 		= new stdClass;
			$newAppID		= strtolower( $application['identifier'] );	
			$newAppXMLFile 	= TUIYO_APPLICATIONS.DS.$newAppID.DS."system.".$newAppID.".xml";
			
			if(!file_exists( $newAppXMLFile )) continue;
			
			$newAppXML 		= TuiyoAPI::get("xml" , $newAppXMLFile );
			$root 			= $newAppXML->file->document;
			$rootName 		= $root->attributes("key") ;
			$rootType 		= $root->attributes("type") ;
			
						
			$newApp->name   = $newAppID;
			$newApp->icon32 = 'components/com_tuiyo/applications/'.$newAppID.'/icon.png';
			$newApp->icon16 = 'components/com_tuiyo/applications/'.$newAppID.'/favicon.png';
			$newApp->title 	= $application['name'];
			$newApp->description= null;
			$newApp->author		= null;
			$newApp->website 	= null;
			$newApp->email		= null;
			$newApp->version 	= null; 
			
			foreach((array)$root->configinfo[0]->children() as $info):
				
				$infoName = $info->attributes( "name" );
				$infoValue = $info->attributes("content" );
				
				if(empty($infoName) ||is_array($infoName)) continue;
				if(empty($infoValue)||is_array($infoValue)) continue ;
				
				$newApp->$infoName = $infoValue ;	
			
			endforeach;
						//specification				
			$newApp->published 			= $application['published'];
			$newApp->installedDate 		= $application['installedDate'];
			$newApp->lastUpdated		= $application['lastUpdated'];
			$newApp->usersCount			= $application['usersCount'];			
			$newApp->access 			= $application['access'];
			$newApp->ordering 			= $application['ordering'];		
			
			$newApp->hasTimeline 		= isset($newApp->hasTimeline) && strtolower($newApp->hasTimeline) === "true" ? 1 : 0 ;
			$newApp->hasNotifications	= isset($newApp->hasNotifications)&& strtolower($newApp->hasNotifications) === "true"? 1 : 0;
			$newApp->hasSearch 			= isset($newApp->hasSearch)&& strtolower($newApp->hasSearch)=== "true"  ? 1 : 0;
			$newApp->hasProfile			= isset($newApp->hasProfile	)&& strtolower($newApp->hasProfile)=== "true" ? 1	: 0;
			$newApp->hasExternalProfile	= isset($newApp->hasExternalProfile	)&& strtolower($newApp->hasExternalProfile)=== "true" ? 1	: 0;			
			$newApp->id					= $application["extID"] ;
			
			$cleanArray[] = $newApp;
			
		}		
				
		return $cleanArray;
	}
	
	/**
	 * TuiyoModelApplications::getApplicationExtendedList()
	 * Gets a list of all available applications
	 * @return void
	 */
	public function getApplicationExtendedList( $userHasApp = FALSE ){
		
		$db 		=& JFactory::getDBO();
		$userObject =& JFactory::getUser();
		$appTable 	=& TuiyoLoader::table("applications");
		$params 	=& TuiyoAPI::get("params", "system.global");
		//$xmlParser 	=& TuiyoAPI::get("xml" , )
		
		$allApps 	= $appTable->getAll( null );
		$allApps 	= (array)$allApps ;   //Make sure its an array;
		
		$cleanArray  = array();
		
		//Now
		foreach( $allApps as $application){
			
			$newApp 		= new stdClass;
			$newAppID		= strtolower( $application['identifier'] );	
			$newAppXMLFile 	= TUIYO_APPLICATIONS.DS.$newAppID.DS."system.".$newAppID.".xml";
			
			if(!file_exists( $newAppXMLFile )) continue;
			
			$newAppXML 		= TuiyoAPI::get("xml" , $newAppXMLFile );
			$root 			= $newAppXML->file->document;
			$rootName 		= $root->attributes("key") ;
			$rootType 		= $root->attributes("type") ;
			
						
			$newApp->name   = $newAppID;
			$newApp->icon32 = 'components/com_tuiyo/applications/'.$newAppID.'/icon.png';
			$newApp->title 	= $application['name'];
			$newApp->description= null;
			$newApp->author		= null;
			$newApp->website 	= null;
			$newApp->email		= null;
			$newApp->version 	= null; 
			
			foreach((array)$root->configinfo[0]->children() as $info):
				
				$infoName = $info->attributes( "name" );
				$infoValue = $info->attributes("content" );
				
				if(empty($infoName) ||is_array($infoName)) continue;
				if(empty($infoValue)||is_array($infoValue)) continue ;
				
				$newApp->$infoName = $infoValue ;	
			
			endforeach;
						//specification				
			$newApp->published 			= $application['published'];
			$newApp->installedDate 		= $application['installedDate'];
			$newApp->lastUpdated		= $application['lastUpdated'];
			$newApp->usersCount			= $application['usersCount'];			
			$newApp->access 			= $application['access'];
			$newApp->ordering 			= $application['ordering'];		
			
			$newApp->hasTimeline 		= isset($newApp->hasTimeline) && strtolower($newApp->hasTimeline) === "true" ? 1 : 0 ;
			$newApp->hasNotifications	= isset($newApp->hasNotifications)&& strtolower($newApp->hasNotifications) === "true"? 1 : 0;
			$newApp->hasSearch 			= isset($newApp->hasSearch)&& strtolower($newApp->hasSearch)=== "true"  ? 1 : 0;
			$newApp->hasProfile			= isset($newApp->hasProfile	)&& strtolower($newApp->hasProfile)=== "true" ? 1	: 0;			
			$newApp->id					= $application["extID"] ;
			
			if($userHasApp){
				$userAppTable			= TuiyoLoader::table("userapps", true );
				$newApp->userHasApp 	= $userAppTable->userHasApp( $newApp->name, $userObject->id );
			}
			
			$cleanArray[] = $newApp;
		}
		
		
		return $cleanArray;
	}
	
	public function getServices(){
		return $this->getAllSystemPlugins("services");
	}
	
	public function getRecentlyUsed($userID, $count = 10){
		
		$uTable = TuiyoLoader::table("userapps", true );
		$uApps 	= $uTable->getRecentlyUsed( (int)$userID, $count );
		
		return (array)$uApps ;
	}
	
}