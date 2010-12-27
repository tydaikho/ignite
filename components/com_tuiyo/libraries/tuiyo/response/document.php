<?php
/**
 * ******************************************************************
 * Document object for the Tuiyo platform                           *
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
 
 TuiyoLoader::import('response');
 
 /**
  * TuiyoDocument
  * 
  * @package tuiyo
  * @author Livingstone Fultang
  * @copyright 2009
  * @version $Id$
  * @access public
  */
 class TuiyoDocument extends TuiyoResponse{
 	
 	private $_docBody 	 = array();
 	
 	private $_DOCTYPE 	 = array();
 	
 	private $_tmplVars   = array();
 	
 	private $_elements   = array();
 	
 	private $_errors     = array();
 	
 	private $_pathway    = array();
 	
 	private $_messageQueue = array();
 	
 	private static $_jDOC = null;
 	
 	/**
 	 * TuiyoDocument::TuiyoDocument()
 	 * Class Constructor
 	 * @return
 	 */
 	public function TuiyoDocument()
	{
 		parent::TuiyoResponse();
 		self::$_jDOC =&JFactory::getDocument();
 	}
 	
 	/**
 	 * TuiyoDocument::getDocument()
 	 * Gets the entire document before it is sent!
 	 * @return
 	 */
 	public function getDocument()
 	{
 		return self::$_jDOC;
 	}
 	
 	/**
 	 * TuiyoDocument::getDocumentBody()
 	 * Grabs the body of the document
 	 * @return
 	 */
 	public function getDocumentBody()
 	{}
 	
 	/**
 	 * TuiyoDocument::getSysTmpl()
 	 * Loads system template and parses it
 	 * @param mixed $name
 	 * @param string $ext
 	 * @return
 	 */
 	public function getSysTmpl($name, $ext = 'tpl' )
	{}
	
	/**
	 * TuiyoDocument::getInterfaceTmpl()
	 * Gets an interface template
	 * @param mixed $name
	 * @param string $ext
	 * @return
	 */
	public function getInterfaceTmpl($name, $ext = 'tpl')
	{}
 	
 	/**
 	 * TuiyoDocument::getElement()
 	 * Gets a body element
 	 * @param mixed $elementID
 	 * @return
 	 */
 	public function getElement( $elementID = NULL )
 	{/*Returns a php created element refer TuiyoElement, returns an element object*/}
 	
 	/**
 	 * TuiyoDocument::createElement()
 	 * Create a body element
 	 * @param mixed $elementID
 	 * @return
 	 */
 	public function createElement( $elementID = NULL )
 	{ /*creates a new element object refer to TuiyoElement*/}
 	
 	/**
 	 * TuiyoDocument::injectElement()
 	 * 
 	 * @param mixed $element
 	 * @param string $heirachy
 	 * @return
 	 */
 	public function injectElement( $element, $heirachy = 'AFTER')
 	{}
 	
 	/**
 	 * TuiyoDocument::getWidget()
 	 * 
 	 * @param mixed $widgetName
 	 * @return
 	 */
 	public function getWidget( $widgetName )
 	{/*gets an interface widget*/}
 	
 	
 	/**
 	 * TuiyoDocument::addJS()
 	 * 
 	 * @param mixed $url
 	 * @return
 	 */
 	public function addJS( $url, $type="text/javascript" , $allowOverwrite = TRUE ){		
		//Is the JS file overwritten by the template?
  		global $mainframe;
		
		//Check if the file is overwritten in the site template
	   if( TUIYO_ALLOW_ASSETS_OVERWRITE && $allowOverwrite){
	 		$params = array(
				'template' 	=> $mainframe->getTemplate(),
				'file'		=> "assets.php",
				'directory'	=> JPATH_THEMES
			);
			// check
			$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
			$template	= JFilterInput::clean($params['template'], 'cmd');
			$file		= JFilterInput::clean($params['file'], 'cmd');
			$file 		= $directory.DS.$template.DS."html".DS."com_tuiyo".DS.$file ;
	
			if (file_exists($file) && is_file($file) ) {
				//LOAD THE overwrite files
				include_once $file;
				
				$OVERITE = str_ireplace( TUIYO_LIVE_PATH."/" , "", $url ) ;
				
				if(isset($TUIYO_ASSETS) && is_array($TUIYO_ASSETS) && array_key_exists($OVERITE, $TUIYO_ASSETS)){				
					$url = $TUIYO_ASSETS[$OVERITE] ;
				}
			}
		}
		
 		//TODO: Better Caching, and script management 		
 		return JFactory::getDocument()->addScript( $url , $type );
 	}
 	
 	/**
 	 * TuiyoDocument::addCSS()
 	 * 
 	 * @param mixed $url
 	 * @param string $type
 	 * @param mixed $media
 	 * @param mixed $attribs
 	 * @return
 	 */
 	public function addCSS($url, $type="text/css", $media=null, $attribs = array()){
		global $mainframe;
		
		//Check if the file is overwritten in the site template
		if( TUIYO_ALLOW_ASSETS_OVERWRITE && $allowOverwrite){
			$params = array(
				'template' 	=> $mainframe->getTemplate(),
				'file'		=> "assets.php",
				'directory'	=> JPATH_THEMES
			);
			// check
			$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
			$template	= JFilterInput::clean($params['template'], 'cmd');
			$file		= JFilterInput::clean($params['file'], 'cmd');
			$file 		= $directory.DS.$template.DS."html".DS."com_tuiyo".DS.$file ;
		
			if (file_exists($file) && is_file($file) ) {
				//LOAD THE overwrite files
				include_once $file;
				
				$OVERITE = str_ireplace( TUIYO_LIVE_PATH."/" , "", $url ) ;
				
				if(isset($TUIYO_ASSETS) && is_array($TUIYO_ASSETS) && array_key_exists($OVERITE, $TUIYO_ASSETS)){				
					$url = $TUIYO_ASSETS[$OVERITE] ;
				}
			}
		}
 		//For now just pass it to Joomla
 		//TODO: Better Caching, and script management
 		return JFactory::getDocument()->addStyleSheet( $url , $type, $media, $attribs );
 	}
 	
 	/**
 	 * TuiyoDocument::getDOCTYPE()
 	 * 
 	 * @param mixed $type
 	 * @return
 	 */
 	public function getDOCTYPE( $defaultType = null )
 	{
		$type = JFactory::getDocument()->gettype(); 
 		if(!empty($type) ){ //check type is valid type
 			return  $type;		
 		}else{
 			return $defaultType; 		
 		}												 		 															 		 							
	 }
 	
 	/**
 	 * TuiyoDocument::setDOCTYPE()
 	 * 
 	 * @param mixed $type
 	 * @return
 	 */
 	public function setDOCTYPE( $type )
 	{}
 	
 	
 	/**
 	 * TuiyoDocument::enqueMessage()
 	 * 
 	 * @param mixed $message
 	 * @param mixed $type
 	 * @return void
 	 */
 	public function enqueMessage( $message, $type= "message"){
		
		$session =& JFactory::getSession();
		
		if (!count($this->_messageQueue))
		{
			$sessionQueue = $session->get('application.queue');
			
			//Remove any other Queued Message
			if (count($sessionQueue)) {
				$this->_messageQueue = $sessionQueue;
				$session->set('application.queue', null);
			}
		}
		// Enqueue the message
		$this->_messageQueue[] = array('message' =>$message, 'type' => strtolower($type));
		
		$session->set('application.queue', $this->_messageQueue );
		
		return true;
 	}
 	
 	

 	/**
 	 * TuiyoDocument::parseTmpl()
 	 * 
 	 * @param mixed $tFile
 	 * @param mixed $tPath
 	 * @param mixed $tVars
 	 * @param string $tExt *.php,*.html, default=*.tpl
 	 * @return
 	 */
 	public function parseTmpl($tFile, $tPath, $tVars =array(), $tExt='tpl')
	{	
		global $mainframe;
		
		$tFile  = $tPath.DS.$tFile.'.'.$tExt;
    	$tExt 	= !is_null($tExt) ? $tExt : '.tpl';
    	$tVars 	= !is_null($tVars)?  $tVars : array() ;
    	//Extract vars from array
	    extract($tVars);
	    //output buffer start 
        ob_start();
        
        //Check if the file is overwritten in the site template
 		$params = array(
			'template' 	=> $mainframe->getTemplate(),
			'file'		=> 'index.php',
			'directory'	=> JPATH_THEMES
		);
		// check
		$directory	= isset($params['directory']) ? $params['directory'] : 'templates';
		$template	= JFilterInput::clean($params['template'], 'cmd');
		$file		= JFilterInput::clean($params['file'], 'cmd');

		if ( !file_exists( $directory.DS.$template.DS.$file) ) {
			$template = 'system';
		}
		// Parse the template INI file if it exists for parameters and insert
		$inTmpl		 = $directory.DS.$template.DS.'html'.DS.'com_tuiyo' ;
   		$inTmplFile  = str_replace(TUIYO_VIEWS , $inTmpl , $tFile );
   		
        if(@is_file($inTmplFile ) && file_exists( $inTmplFile )){
        	include $inTmplFile ;
        }elseif(@is_file($tFile)&&file_exists($tFile)){ 	//include it
            include $tFile;
        } else {
           JError::raiseError( TUIYO_NOT_FOUND , sprintf( _("Template FILE:%s , not found"), $tFile ) );
        }
        $parsed = ob_get_contents();
        //Close Buffer!
        ob_end_clean();
        
        return $parsed;
	}
 	
 	/**
 	 * TuiyoDocument::setPageTitle()
 	 * 
 	 * @param string $title
 	 * @param bool $overWrite
 	 * @return
 	 */
 	public function setPageTitle( $title = '', $overWrite = TRUE )
 	{}
 	
 	/**
 	 * TuiyoDocument::getPageTitle()
 	 * 
 	 * @return
 	 */
 	public function getPageTitle()
 	{}
 	
 	/**
 	 * TuiyoDocument::_setError()
 	 * 
 	 * @param mixed $error
 	 * @return
 	 */
 	private function _setError( $error )
 	{}
 	
 	/**
 	 * TuiyoDocument::getErrors()
 	 * 
 	 * @return
 	 */
 	public function getErrors()
 	{}
 	
 	/**
 	 * TuiyoDocument::addToPathway()
 	 * 
 	 * @param bool $respectHeirrachy
 	 * @return
 	 */
 	public function addToPathway($respectHeirrachy = TRUE)
 	{}
 	
 	/**
 	 * TuiyoDocument::getPathway()
 	 * 
 	 * @return
 	 */
 	public function getPathway()
 	{}
 	
 	/**
 	 * TuiyoDocument::getEditor()
 	 * 
 	 * @return
 	 */
 	public function getEditor()
 	{}
 	
 	/**
 	 * TuiyoDocument::setVar()
 	 * 
 	 * @param mixed $varName
 	 * @param string $value
 	 * @param bool $overWrite
 	 * @return
 	 */
 	public function setVar($varName, $value = '', $overWrite = TRUE )
 	{}
 	
 	
 	/**
 	 * TuiyoDocument::startBuild()
 	 * 
 	 * @return void
 	 */
 	public function startBuild(){
 		
 		//add JQuery to the site!
 		 $doc =&TuiyoDocument::getInstance();
 		 
		 $doc->addJS( TUIYO_JQUERY );
		 $doc->addJS( TUIYO_GETTEXT_JS );
		 $doc->addJS( TUIYO_FACEBOX );
		 $doc->addJS( TUIYO_APPENDDOM );
		 $doc->addJS( TUIYO_JS_COMMON );
		 
		 $doc->addHeadJS( TUIYO_FACEBOX_INIT );
		 //$doc->addScriptDeclaration( TUIYO_JQUERY_COMPAT );
		 
		 $doc->addCSS( TUIYO_CSS_COMMON );		 
		 $doc->addCSS( TUIYO_FACEBOX_CSS );
 	}
 	
 	public function addHeadJS( $string , $type ="text/javascript"){
 		return JFactory::getDocument()->addScriptDeclaration( $content, $type );
 	}
 	
 	public function finishBuild(){
 		
 		global $mainframe;
 		
	    
	    $userData 		= TuiyoAPI::get("user" );
 		$profile 		= TuiyoUser::getUserFromRequest();
		 
	 	$document		= JFactory::getDocument(); 
 		$template		= JFilterInput::clean( $mainframe->getTemplate(), 'cmd');
 		
 		$userStyle 		= TUIYO_STYLES.DS.$userData->id.DS.$template.".ini" ;
		
		if(file_exists( $userStyle ) && is_file($userStyle) && is_readable( $userStyle )){
			
			$content 	= file_get_contents($userStyle);
			$params 	= new JParameter($content);
			$session 	= JSession::getInstance('none',array());

			$session->set("TUIYO_STYLE", $params);
			
		}
		
 		//User Avatar
 		$mainframe->addMetaTag( "thumb70" , $userData->getUserAvatar()->thumb70 );
 		$mainframe->addMetaTag( "thumb35" , $userData->getUserAvatar()->thumb35 );
 		
 	}
 	
 	
 	/**
 	 * TuiyoDocument::addJSDefines()
 	 * 
 	 * @return void
 	 */
 	public function addJSDefines()
 	{
		 //add JQuery to the site!
 		 $doc =&JFactory::getDocument();
 		 $headData = array(
 		 	"livePath"	=>TUIYO_LIVE_PATH,
 		 	"index"		=>TUIYO_INDEX,
			"ajaxIMG_16"=>TUIYO_LIVE_PATH.'/client/default/images/loading.gif',
		 );
		 
		 $TuiyoDefines 		= $this->parseTmpl("TuiyoDefines" , TUIYO_SYSTEM_JS , $headData, "js" );
		 $TuiyoDefineFile 	= JPATH_CACHE.DS."js.defines.".JFactory::getUser()->id.".js";
		 $TuiyoDefineFileL 	= JURI::root()."cache/js.defines.".JFactory::getUser()->id.".js";
		 
		 $TuiyoDefinneFileH = fopen($TuiyoDefineFile, 'w'); 
		
		if(!$TuiyoDefinneFileH){
			trigger_error( _("Cannot create js.defines"), E_USER_ERROR );
			return false;
		}
		//write to the file
		fwrite($TuiyoDefinneFileH, $TuiyoDefines);
		//close the file
	 	fclose($TuiyoDefinneFileH) ;
		 //include the script;
		$doc->addScript( $TuiyoDefineFileL );
		
		//Add secured data in metaTags
		$GLOBALS['mainframe']->addMetaTag( "_token" , JUTility::getToken() );
		//$GLOBALS['mainframe']->addMetaTag( "_token" , JUTility::getToken() );
		
		//Search URL
		$hashURL 	= JRoute::_("index.php?option=com_search&amp;view=search&amp;task=find"
			. "&amp;areas[1]=groups&amp;areas[2]=articles&amp;areas[3]=applications&amp;areas[0]=profiles"
			. "&amp;areas[4]=photos&amp;areas[5]=resources&amp;areas[6]=content&amp;areas[7]=web" ) ;
			
		$GLOBALS['mainframe']->addMetaTag( "_searchurl",$hashURL );
		
		return true;
 	}
 	
 	/**
 	 * TuiyoDocument::getVar()
 	 * 
 	 * @param mixed $varName
 	 * @return
 	 */
 	public function getVar($varName)
 	{}
 	
 	/**
 	 * TuiyoDocument::__set()
 	 * 
 	 * @param mixed $varName
 	 * @param mixed $value
 	 * @return
 	 */
 	private function __set($varName, $value)
	{}
	
	/**
	 * TuiyoDocument::__get()
	 * 
	 * @param mixed $varName
	 * @return
	 */
	private function __get($varName)
	{}
	
	 /**
	  * TuiyoDocument::getInstance()
	  * Gets an Instance of the object if non exists
	  * @return
	  */
	 public function getInstance($ifNotExist = true)
	 {
		/** Creates new instance if none already exists ***/
		static $instance = array();
		
		if(isset($instance)&&!empty($instance)&&$ifNotExist){
			if(is_a($instance, TuiyoDocument)){
				return $instance;
			}else{
				unset($instance);
				TuiyoDocument::getInstance(  $ifNotExist );			
			}								
		}else{
			$instance = new TuiyoDocument()	;	
		}
		return $instance;	 
	 }
 	
 }