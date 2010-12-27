<?php
/**
 * ******************************************************************
 * Params object for the Tuiyo platform                               *
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
  * TuiyoParams
  * @package Tuiyo For Joomla
  * @author Livingstone Fultang
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoParams{
 	
 	/**
 	 * Validation Routines for Params items
 	 */
 	var  $validation 	= array(
		 	"user.privacy" 	=> array(
		 		"fields" 	=> array(
		 			"viewProfile" 		=> "interger",
				    "viewSocialBook"	=> "interger",
				    "viewInSearch"  	=> "interger",
				    "viewProfilePicture"=> "interger",
				    "viewActivityStream"=> "interger",
				    "viewPhotos"  		=> "interger",
				    "viewMyGroups"		=> "interger",
				    "viewMyEvents"		=> "interger",
				    "viewMyFriendsLists"=> "interger",
				    "viewMyExtendedProfile"=> "interger",
				    "commentOnPhotos"  	=> "interger",
				    "tagPhotos"  		=> "interger",
				    "postToProfile"		=> "interger",
	    			"viewContactInfo"  	=> "interger",
    			)
			 ), 
			"user.contact" 	=> array(
				"fields"	=> array(
					"company"	=>"string", 
					"street"	=>"string", 
					"region"	=>"string" , 
					"town"		=>"string" ,
					"postcode"	=>"string", 
					"phone"		=>"string",
					"email"		=>"string", 
					"description"=>"string", 
					"website"	=>"link", 
					"gTalkID"	=>"string",
					"msnID"		=>"string",
					"yahooID"	=>"string",
					"skypeID"	=>"string",
					"aolID"		=>"string"
				)
			)		   
		 );
 	/**
 	 * TuiyoParams::TuiyoParams()
 	 * @param mixed $userID
 	 * @param string $paramGroup
 	 * @return void
 	 */
 	public function TuiyoParams( $paramKey, $userID = NULL  )
	 {
	    /** Get the Table; **/
		$this->table  	= TuiyoLoader::table( "params" );
		$this->userID  	= (int)$userID ;
		$this->key   	= $paramKey ;
		$this->file		= NULL ;
		$this->manifest = NULL ;
		$this->exists 	= FALSE ;
		
		/** Load the params from the table if exists; **/
		$this->defined  = array();
		
		/** Default System manifest files ***/
		$this->manifest = array(
			"system.global" 	=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.global.xml",
			"system.languages"	=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.languages.xml",
			"system.photos"		=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.photos.xml",
			"system.styles"		=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.styles.xml",
			"system.widgets"	=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.widgets.xml",
			"system.groups"		=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.groups.xml",
			"system.terms"		=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.terms.xml",
			"system.statistics"	=> JPATH_COMPONENT_ADMINISTRATOR.DS."system.statistics.xml"
		);
		
 	}
 	
 	/**
 	 * TuiyoParams::loadParams()
 	 * @param mixed $key
 	 * @param mixed $userID
 	 * @return void
 	 */
 	public function loadParams( $key, $userID = NULL){
 		
 		$paramTable = TuiyoLoader::table("params" );
 		$userID		= (!empty($userID)) ? (int)$userID : 0 ; //user or system?
 		$defined 	= $paramTable->getParamGroup( $key , $userID );
		
 		if(sizeof($defined)>0){
 			if( isset($defined[0]) ){
 				$this->defined = json_decode( $defined[0]["data"] );
 				$this->defined = (array)$this->defined;
                $this->userID  = $defined[0]["userID"] ;
                $this->exists  = TRUE;                
 			}
 		}
 	}
 	
 	/**
 	 * Gets a parameter value, 
 	 * TuiyoParams::get()
 	 * @param mixed $paramName
 	 * @param mixed $default
 	 * @return void
 	 */
 	public function get( $paramName , $default = NULL ){
 		if(isset($this->defined[$paramName])){
 			return $this->defined[$paramName];
 		}
 		return $default ;
 	}
 	
 	/**
 	 * Sets params. use updatesParams to update the parameter table
 	 * TuiyoParams::set()
 	 * @param mixed $paramName
 	 * @param mixed $value
 	 * @return void
 	 */
 	public function set( $paramName, $value ){
 		if(!is_array($this->defined) ) return false;
		$this->defined[$paramName] = $value ;
 	}
 	
 	/**
 	 * TuiyoParams::storeSystemParams()
 	 * Stores sytem parameters
 	 * @param mixed $paramKey
 	 * @param mixed $fields
 	 * @return void
 	 */
 	public function storeSystemParams($paramKey, $postData, $application = NULL){
 		
		//First make sure that we have the right parameters loaded.
		$this->loadParams($paramKey, 0 );
		
		//The system config manifest file
		$manifest = $this->manifest;
		
		//Extract and Validate the parameters
		if(array_key_exists($paramKey, $manifest) ){
			 //Get the file from the manifest array
			 $file 		= $manifest[$paramKey];
		}else{
			 $hash 		= explode(".", $paramKey);
			 $file 		= TUIYO_APPLICATIONS.DS.strtolower($hash[1]).DS.$paramKey.".xml";	
		}
		//Check that we have a valid file
		if(!file_exists($file)){
			JError::raiseError(sprintf(_("%s.xml file not found") , $paramKey) ) ;
			return false;
		}
		
		$paramTable 	= TuiyoLoader::table("params" );
		$validate	  	= TuiyoAPI::get( "validate" );
		$paramXml 		= TuiyoAPI::get("xml", $file );
		$paramRootXml 	= $paramXml->file->document->configparams[0];
		//Extract the params
		foreach( (array)$paramRootXml->children() as $param):
		
			$name 		= $param->attributes("name") ;
			$type 		= $param->attributes("type") ;
			$label 		= $param->attributes("label") ;
			$default	= $param->attributes("default") ;
			$decsript	= $param->attributes("description") ;
			$dataType	= $param->attributes("datatype") ;
			
			//Validate the values
			$value 		= !empty($dataType) 
						  ?(method_exists($validate, $dataType))  
						  ? call_user_func( array($validate, $dataType), $postData[$name] )
						  : $postData[$name] 
						  : $postData[$name];
						  
			$this->set( $name, $value );
		
		endforeach;
		
		
		
		//print_R($this->defined);
		 //die;
		if(!$this->exists){  //We are dealing with new settings
			$paramTable->load( );
			$paramTable->userID 		= 0;
			$paramTable->application	= $paramKey ;
			$paramTable->data			= json_encode( $this->defined );
			$paramTable->lastModified 	= date('Y-m-d H:i:s');
			
			//Store params
			if(!$paramTable->store()){
				JError::raiseError(TUIYO_SERVER_ERROR, $paramTable->getError() );
				return false;
			}
		}else{
			$this->updateParams( 0 ); //store user params
		}
		 return true;
 	}
 	
 	/**
 	 * Stores the param object in the database
 	 * TuiyoParams::storeParams()
 	 * @param mixed $paramKey
 	 * @param mixed $userID
 	 * @return void
 	 */
 	public function storeUserParams($paramKey, $userID, $paramsData){
 		
		 $paramTable 	= TuiyoLoader::table("params" );
 		 $validate	  	= TuiyoAPI::get( "validate" );
		 
		 //Extract and Validate the parameters
		 if(array_key_exists($paramKey, $this->validation) ){
 			 
	         $newArray 	= array();
			 $fields 	= $this->validation[$paramKey]["fields"] ;
		 	 
		     foreach($paramsData as $param=>$value){
		 	 	if(array_key_exists($param, $fields)){
		 	 		$value = ( empty($fields[$param] ) || !method_exists($validate , $fields[$param] ) ) ? $value  : 
					         call_user_func( array($validate , $fields[$param] ) , $value );
       					$this->set( $param , $value  );
		 	 	}
		 	 }
		 
		 //Extract and validate the social book parameters	 	 
		 }elseif(strval($paramKey) == "user.social"){
		 	
		 	$sfTable 	= TuiyoLoader::table("fields" );
		 	$sfElements = $sfTable->listAll();
		 	
		 	foreach($paramsData as $param=>$value):
		 		foreach($sfElements as $element):
	 				
			 		if($param == $element->name){
			 			$value = (!method_exists($validate, $element->validation ) || empty($element->validation)) ? $value 
			 					 : call_user_func(array( $validate , $element->validation ) , $value );
			    		$this->set($param, $value );
			 		}
		 		endforeach;
		 	endforeach;
 	
		 }else{
		 	//If Custom Applications
		 	//echo "what are you doing here?";
		 	//load the  user settings xml file and get the params
		 	
		 }			
		if(!$this->exists){  //We are dealing with new settings
			$paramTable->load( );
			$paramTable->userID 		= (int)$userID;
			$paramTable->application	= $paramKey ;
			$paramTable->data			= json_encode( $this->defined );
			
			if(!$paramTable->store()){
				trigger_error( $paramTable->getError() , E_USER_ERROR );
				return false;
			}
		}else{
			$this->updateParams( ); 
			return true;
		}
 	}
 	
 	/**
 	 * TuiyoParams::updateParams()
 	 * Updates the params table if record already exists
 	 * @param mixed $userID
 	 * @return
 	 */
 	private function updateParams( $userID = NULL ){
 		
		 $paramTable = TuiyoLoader::table("params" );
		 $paramJSON  = json_encode( $this->defined  );
		 $userID	 = empty($userID) ? $this->userID : $userID ;
		 
		 return $paramTable->updateTable($this->key , $userID , $paramJSON );
 		 
 	}
 	
 	/**
 	 * Builds an Array Based form structure of
 	 * The current parameter object
 	 * TuiyoParams::getForm()
 	 * @return void
 	 */
 	public function getForm( $formID = NULL , $frontEnd = false, $showSubmit = TRUE ){		
		
	 	/** Custom Manifest Folders */
	 	$thisKey 	= strtolower($this->key);
	 	$keyParts 	= explode( ".", $this->key );
		$keyCount 	= count($keyParts) ;
		
		if($keyCount!== 2) trigger_error( _("Invalid request key"), E_USER_ERROR);
		
		if(array_key_exists($thisKey, $this->manifest))
		{
			$this->file = $this->manifest[$thisKey];
		}else{		
			//Get the type of config file
			$manifest = array( 
				"user" 		=> TUIYO_APPLICATIONS.DS.$keyParts[1].DS.$thisKey.".xml",
				"system"	=> TUIYO_APPLICATIONS.DS.$keyParts[1].DS.$thisKey.".xml"
			);
			$this->file = $manifest[$keyParts[0]];
		}	
		/** //Get the XML file */
		$xml 		= TuiyoAPI::get("xml", $this->file );
		$root 		= $xml->file->document;
		$rootName 	= $root->attributes("key") ;
		$rootType 	= $root->attributes("type") ;
		
		/** Load System Params **/
		if(!$frontEnd) $this->loadParams( $this->key, 0 );
		if($frontEnd)  $this->loadParams( $this->key, $this->userID );
		
		//Validate the file
		if($root->name() !== 'tuiyoconfig') return trigger_error(_("Invalid config file") );
		if(empty( $rootName ) ) 			return trigger_error(_("Invalid config file: Key not defined") );
		if(empty( $rootType ) ) 			return trigger_error(_("Invalid config file: Type not defined") );
		if(!isset( $root->configinfo[0] ) ) return trigger_error(_("Invalid config file: configinfo not defined") );
		if(!isset( $root->configparams[0] ))return trigger_error(_("Invalid config file: params not defined") );
		
		//Build the form
		$form 	= TuiyoAPI::get("form" , $thisKey );
		
		//Add Hidden Fields
		foreach( (array)$root->configinfo[0]->children() as $info){
			
			$name 	= $info->attributes("name") ;
			$value 	= $info->attributes("content") ;
			
			if(!isset( $name) || !isset( $value)  ) continue;
			
			$name 	=  isset( $name) ? $name : NULL ;
			$value 	=  isset( $value) ? $value : NULL ;
			$args 	= array( 
				"type"	=> "hidden",
				"name"	=> $name, 
				"value"	=> $value
			);
			$form->add("hidden" , $args["name"], $args );
		}
		
		//Add Form Elements
		foreach( (array)$root->configparams[0]->children() as $param){
		
			$name 	= $param->attributes("name") ;
			$type 	= $param->attributes("type") ;
			$label 	= $param->attributes("label") ;
			$default= $this->get($name , $param->attributes("default") ); //Gets if defined, or returns default;
			$decsrip= $param->attributes("description");
			
			if(!isset( $name) || !isset($label) || !isset($type)  ) continue;
			
			
			switch($type):
				case "text":
				case "password": 
					$args 	= array( 
						"name"	=> $name, 
						"label"	=> $label,
						"description"=> !empty($decsrip)&&!is_array($decsrip) ? $decsrip : null, 						
						"value"	=> !empty($default)&&!is_array($default) ? $default : null 
					);
					$form->add( $type , $args["name"] , $args );
					break;
				case "textarea": 
					$args 	= array( 
						"name"	=> $name, 
						"label"	=> $label,
						"description"=> !empty($decsrip)&&!is_array($decsrip) ? $decsrip : null, 
						"innerHTML"	 => !empty($default)&&!is_array($default) ? $default : null 
					);				
					$form->add("textarea" , $args["name"] , $args );
					break;
					
				case "radiogroup":
				case "droplist":
				case "select" :
					$args 	= array( 
						"name"	=> $name, 
						"label"	=> $label,
						"value"	=> !empty($default)&&!is_array($default) ? $default : null ,
						"options" => array(),
						"description"=> !empty($decsrip)&&!is_array($decsrip) ? $decsrip : null, 
					);
					//get Objtions
					$childOptions = $param->children();
					
					foreach($childOptions as $child){
						$optionValue = $child->attributes( "value" );
						$optionName  = $child->data();
						
						$args["options"][$optionName] = $optionValue;
					}			
					$form->add($type, $args["name"] , $args );	
								
					break;										
				default:
					continue;
				break;
			endswitch;
		}
		//hidden fields
		$form->add("hidden", "paramKey" , array("type"=>"hidden", "name"=>"paramKey", "value"=>$thisKey ));
		
		return $form->outPutForm( );
 	}
 	
 	/**
 	 * TuiyoParams::getSocialBook()
 	 * Gets social Book Form
 	 * @param mixed $formID
 	 * @param bool $frontPage
 	 * @param bool $showSubmit
 	 * @return
 	 */
 	public function getSocialBook($userID, $formID = NULL, $frontPage = TRUE, $showSubmit = TRUE )
    {
    	$sfTable 	= TuiyoLoader::table("fields" , true);
    	$sfEls		= $sfTable->listAll(); 
    	
    	/** Load Social Data ***/
    	$this->loadParams( "user.social" ,  $userID );
    	
		/** Build the form ****/
		$form 	= TuiyoAPI::get("form" , "user.social" );
		
    	foreach( (array)$sfEls as $element ):
    		
    		//Smart Description
    		$description  = ($element->required > 0)? _("This field IS required, ") : _("This field IS Not required,"); 
    		$description .= ($element->indexed > 0) ? _("This field IS searchable, ") : _("This field IS NOT searchable,");
    		$description .= ($element->visible > 0) ? _("This field IS visible on profile. ") : _("This field IS NOT visible on profile. ");
    		$description .= !empty($element->descr)&&!is_array($element->descr) ? $element->descr : null ;
			$defaultValue = $this->get($element->name , $element->defaultvalue ); 
			
    		//switch element type
			switch($element->type):
				case "text":
				case "password": 
					$args 	= array( 
						"name"	=> $element->name, 
						"label"	=> $element->title,
						"description" => $description ,					
						"value"	=> !empty($defaultValue)&&!is_array($defaultValue) ? $defaultValue : null 
					);
					$form->add( $element->type , $args["name"] , $args );
					break;
				case "textarea": 
					$args 	= array( 
						"name"	=> $element->name, 
						"label"	=> $element->title,
						"description"=>  $description ,	
						"innerHTML"	 => !empty($defaultValue)&&!is_array($defaultValue) ? $defaultValue : null 
					);				
					$form->add("textarea" , $args["name"] , $args );
					break;
				case "radiogroup":
				case "droplist":
				case "select" :
					$args 	= array( 
						"name"	=> $element->name, 
						"label"	=> $element->title,
						"value"	=> !empty($defaultValue)&&!is_array($defaultValue) ? $defaultValue : null ,
						"options" => array(),
						"description"=>  $description ,	
					);
					//get Obtions
					$childOptions = array();
					
					foreach($childOptions as $child){
						$optionValue = $child->attributes( "value" );
						$optionName  = $child->data();
						
						$args["options"][$optionName] = $optionValue;
					}
									
					$form->add($element->type, $args["name"] , $args );	
								
					break;										
				default:
					continue;
				break;
			endswitch;    		
    	endforeach;
		//hidden fields
		$form->add("hidden", "paramKey" , array("type"=>"hidden", "name"=>"paramKey", "value"=>"user.social" ));
		
		return $form->outPutForm( $formID , $frontPage , $showSubmit);    	
    }
 	
 	
	/**
	 * Gets an instance of a parameter object creatig if not exists
	 * TuiyoParams::getInstance()
	 * @param string $paramGroup
	 * @param mixed $userid (optional) if user param specifiy ID
	 * @param bool $ifNotExist
	 * @return
	 */
	public function getInstance($paramGroup = 'system.global', $userid = NULL,  $ifNotExist = TRUE )
 	{ 
 		/** Creates new instance if none already exists ***/
		return  new TuiyoParams($paramGroup, $userid )	;	
	
  	}
  	
  	
 	
 }