<?php
/**
 * ******************************************************************
 * Loader object for the Tuiyo platform                             *
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
 * TuiyoLoader
 * 
 * @package tuiyo
 * @author Livingstone Fultang
 * @copyright 2009
 * @version $Id$
 * @access public
 */
class TuiyoLoader{

	    var $_cache			= FALSE;
	    
	    /**
	     * store for loaded Objects
	     */
		static $_stored 	= array(
			"libraries"		=>array(),
			"models"		=>array(),
			"controllers"	=>array(),
			"views"			=>array(),
			"applications"	=>array(),
			"language"		=>array(),
			"helpers"		=>array(),
			"macros"		=>array(),
			"tables"		=>array()
		);
		
		static $_loaded 	= array();
		
		
		var $loadFromDir 	= TUIYO_LIB;
	/**
	 * Constructor method
	 * 
	 * @return void
	 */
	function TuiyoLoader($lib = '' )
	{}
	

	/**
	 * TuiyoLoader::library()
	 * 
	 * @param mixed $libraryDotPath
	 * @param mixed $parameters
	 * @return
	 */
	public function library($libraryDotPath, $parameters = null)
	{
		/**
		 * Load the files
		 */
		TuiyoLoader::import($libraryDotPath , 'library');
		
		//check is loaded
		if(!array_key_exists($libraryDotPath , self::$_loaded)){
			JError::raiseError(TUIYO_NOT_FOUND , "Request Library Class Not Found");		
		}
		$library = ucfirst(TUIYO_LIB)
		         . ucfirst(self::$_loaded[$libraryDotPath]) 
				 ;
		if(in_array($library , self::$_stored["libraries"])){
			$object = self::$_stored["libraries"][$library];
			if(is_object($object)){
				return $object;			
			}else{
				unset(self::$_stored["libraries"][$library]);
				TuiyoLoader::library( $libraryDotPath, $parameters );			
			}																
		}
		//instanciate the class
		$instance    =& call_user_func(array($library , 'getInstance') , $parameters );
		if(!is_object($instance)){
			die;
			JError::raiseError(TUIYO_NOT_ACCEPTABLE, "Libary Class is not an object" ); //$libary is not an object		
		}
		self::$_stored["libraries"][$library] =& $instance;		
																																										 return $instance;																								
	}
	
	/**
	 * Check Item is Loaded
	 * 
	 * @param mixed $itemName : The name of the class to load
	 * @param string $itemType : permitted { FILE, METHOD, CLASS, LIBRARY }
	 * @return
	 */
	public function isLoaded($itemName, $itemType='FILE')
	{}
	
	
	
	/**
	 * TuiyoLoader::getUserByUserName()
	 * Returns a TuiyoUser object with the specified ID;
	 * @param mixed $username
	 * @return object on success and false if empty
	 */
	public function getUserByUserName( $username ){
		
		$username	= strval( $username );
		$userTable	= TuiyoLoader::table( 'users' );
		$userID 	= $userTable->getUserID( $username ) ;
		
		if(!empty ($userID )){
			$userObject = TuiyoAPI::get('user', (int)$userID );
			return (object)$userObject; 
		}
		return false;
	}
	
	
	/**
	 * TuiyoLoader::require()
	 * Imports (aka requires) a file
	 * @param mixed $fileName
	 * @return void
	 */
	public function import($dotPath ,  $type="library" , $ext = 'php')
	{
		$iTypes = array(
			"library" 	=> TUIYO_LIBRARIES.DS.TUIYO_LIB.'',
			"elibrary"	=> TUIYO_LIBRARIES,
			"helper" 	=> TUIYO_HELPERS,
			"model"  	=> TUIYO_MODELS,
			"macro"		=> TUIYO_MACROS,
			"controller"=> TUIYO_CONTROLLERS,
			"table"		=> TUIYO_TABLES,
			"component"	=> TUIYO_APPLICATIONS,			
			"plugin"	=> TUIYO_PLUGINS
		);
		
		$parts 		= explode('.', $dotPath);
		$cparts 	= count($parts);
		$counter	= ((int)$cparts > 1 || ($type!=='library'))?($cparts-1):$cparts;
		
		if($ext == 'php' || $ext == 'inc'){
			$filename = $parts[$cparts-1];
			$filepath = $iTypes[$type];
			for($i=0; $i<($counter); $i++){
				$filepath .= DS.strtolower( $parts[$i] );
				if(($i+1)<($cparts-1) ){
					$filepath .= ''.DS;
				}
			}
		}
		$file = $filepath.DS.$filename.'.'.$ext;
		
		if(file_exists($file)){
			require_once ($file);
		}else{
			JError::raiseError( TUIYO_NOT_FOUND , "[$type]:$filename.php not found at $file");		
		}												
		self::$_loaded[$dotPath] = $filename;
		
		return true;
	}
	
	/**
	 * TuiyoLoader::controllerIsApp()
	 * Verifies an Application controller
	 * @param mixed $controller
	 * @return
	 */
	public function controllerIsApp($controller){
		
		if(!file_exists(TUIYO_CONTROLLERS.DS.$controller.".php")){
			if(file_exists(TUIYO_APPLICATIONS.DS.$controller.DS.$controller.".php")){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Imports a helper file or class
	 * Loads a helper from the public helpers
	 * @param mixed $fileName
	 * @param bool $createInstance
	 */
	public function helper($fileName, $createInstance = false , $parameters = null )
	{
		TuiyoLoader::import($fileName , 'helper');
	
		if($createInstance){	
			$helperClass = ucfirst(TUIYO_LIB).ucfirst( self::$_loaded[$fileName] );
			if(in_array($helperClass , self::$_stored["helpers"])){
				$object = self::$_stored["helpers"][$helperClass];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["helpers"][$helperClass]);
					TuiyoLoader::helper( $fileName ,$createInstance , $parameters );			
				}																
			}
			$helper 	 = new $helperClass( $parameters );
			
			self::$_stored["helpers"][$helperClass] =& $helper ;
			
			return $helper;
		}  
	}
	
	/**
	 * TuiyoLoader::macro()
	 * Loads admin macros to the system;
	 * @param mixed $macroName
	 * @param bool $createInstance
	 * @param mixed $parameters
	 * @return
	 */
	public function macro($macroName, $createInstance = true, $parameters = null ){
		
		TuiyoLoader::import($macroName , 'macro');
	
		if($createInstance){
			
			$macroClass = ucfirst(TUIYO_LIB).'Macro'.ucfirst( self::$_loaded[$macroName] );
			
			if(in_array($macroClass , self::$_stored["macros"])){
				$object = self::$_stored["macros"][$macroClass];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["macros"][$macroClass]);
					TuiyoLoader::macro( $macroName ,$createInstance , $parameters );			
				}																
			}
			$macro 	 = new $macroClass( $parameters );
			
			self::$_stored["macros"][$macroClass] =& $macro ;
			
			return $macro;
		} 	
	}
	
	/**
	 * TuiyoLoader::model()
	 * Loads a model from the public models
	 * @param mixed $fileName
	 * @param bool $createInstance
	 * @param mixed $parmaeters
	 * @return
	 */
	public function model($fileName, $createInstance = false , $parmaeters = null )
	{
		TuiyoLoader::import($fileName , 'model');
		
		if($createInstance){	
			$modelClass = ucfirst(TUIYO_LIB).'Model'.ucfirst( self::$_loaded[$fileName] );
			if(in_array($modelClass , self::$_stored["models"])){
				$object = self::$_stored["models"][$modelClass ];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["models"][$modelClass ]);
					TuiyoLoader::model( $fileName , $createInstance , $parmaeters );			
				}																
			}
			$model 	 = new $modelClass( $parmaeters );
			
			self::$_stored["models"][$modelClass] =& $model ;
			
			return $model;
		}  
	}
	
	/**
	 * TuiyoLoader::plugin()
	 * Loads a plugin/service controller
	 * @param mixed $pluginName
	 * @param bool $createInstance
	 * @param mixed $parameters
	 * @return
	 */
	public function plugin($pluginName, $createInstance = false , $parmaeters = null ){
		
		$pluginName = strtolower($pluginName); 
		$fileName 	= (string)$pluginName.".controller";
		
		TuiyoLoader::import($fileName , 'plugin');
		
		if($createInstance){	
			$pluginClass = ucfirst($pluginName).'ServiceController';
			if(in_array($pluginClass , self::$_stored["plugins"])){
				$object = self::$_stored["plugins"][$pluginClass];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["plugins"][$pluginClass]);
					TuiyoLoader::plugin( $pluginName , $createInstance , $parmaeters );			
				}																
			}
			$plugin 	 = new $pluginClass( $parmaeters );
			
			self::$_stored["plugins"][$pluginClass] =& $plugin ;
			
			return $plugin;
		} 
	}
	
	/**
	 * TuiyoLoader::controller()
	 * Loads a controller from the public controllers
	 * @param mixed $fileName
	 * @param bool $createInstance
	 * @param mixed $parameters
	 * @return
	 */
	public function controller($fileName, $createInstance = false , $parameters = null )
	{
		TuiyoLoader::import($fileName , 'controller');
		
		if($createInstance){	
			$controllerClass = ucfirst(TUIYO_LIB).'Controller'.ucfirst( self::$_loaded[$fileName] );
			if(in_array($controllerClass , self::$_stored["controllers"])){
				$object = self::$_stored["controllers"][$controllerClass ];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["controllers"][$controllerClass ]);
					TuiyoLoader::controller( $fileName , $createInstance , $parameters );			
				}																
			}
			$controller 	 = new $controllerClass( $parameters );
			
			self::$_stored["controllers"][$controllerClass] =& $controller ;
			
			return $controller;
		}  
	}
	
	/**
	 * TuiyoLoader::table()
	 * Loads a library table
	 * @param mixed $fileName
	 * @param bool $createInstance
	 * @param mixed $parameters
	 * @return void
	 */
	public function table($fileName, $createInstance = true, $parameters = null )
	{
		TuiyoLoader::import($fileName , 'table');
		$parameters = !is_null($parameters) ? $parameters : JFactory::getDBO();
		
		if($createInstance){
			//Table File
			$tableClass = ucfirst(TUIYO_LIB).'Table'.ucfirst( self::$_loaded[$fileName] );
			//Had we loaded it before
			if(in_array($tableClass , self::$_stored["tables"])){
				$object = self::$_stored["tables"][$tableClass ];
				if(is_object($object)){
					return $object;			
				}else{
					unset(self::$_stored["tables"][$tableClass ]);
					TuiyoLoader::table( $fileName , $createInstance , $parameters );			
				}																
			}
			$table 	 = new $tableClass( $parameters );
			
			self::$_stored["tables"][$tableClass] =& $table ;
			
			return $table;
		}  
	}
	
	/**
	 * TuiyoLoader::loadAPI()
	 * Loads (requires) an external API library
	 * @param mixed $path
	 * @return void
	 */
	public function loadAPI( $path )
	{}
	
	/**
	 * Gets an instance of the loaded class!
	 * This has already been loaded into the $LOAD global
	 * @return
	 */
	public function getInstance(){
		static $instance;
		
		if( is_object($instance) ){
			return $instance;		
		}else{
			$instance = new TuiyoLoader()	;	
		}
		return $instance;		
	}
}
