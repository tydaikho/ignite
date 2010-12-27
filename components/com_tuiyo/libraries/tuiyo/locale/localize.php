<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform             *
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
 * TuiyoLocalization
 * 
 * @package tuiyo
 * @version $Id$
 * @access public
 */
class TuiyoLocalize
{
    /**
     * Loaded languages, to avoid reloading
     */
    var $loaded = array();
    

    public function __construct( $language ){}
    
    /**
     * TuiyoLocalize::initiate()
     * Initiates a language domain
     * @param mixed $domain
     * @param mixed $locale
     * @param mixed $encoding
     * @return
     */
    public function initiate( $domain, $locale, $encoding ){
    	
		
		
		//Initialize gettText
		$locale 	= !empty($locale)  ? $locale : TUIYO_DEFAULT_LOCALE ;
		$domain 	= !empty($domain)  ? $domain : 'system';
		$encoding 	= !empty($encoding)? $encoding : TUIYO_DEFAULT_ENCODING ;
		
		putenv("LANG=$locale");
		
		if(!extension_loaded('gettext')){ 
			
			TuiyoLoader::import("gettext.gettext", "elibrary", "inc");

			T_setlocale(LC_ALL, $locale);
			T_bindtextdomain($domain, TUIYO_LOCALE );
			T_bind_textdomain_codeset($domain, $encoding);
			T_textdomain( $domain );
			
			//return TRUE;
		}
		setlocale(LC_ALL, $locale);
		
		bindtextdomain( $domain , TUIYO_LOCALE );
		bind_textdomain_codeset($domain, $encoding );
        textdomain( $domain );
        
        $path 	= "components/com_tuiyo/locale/".$locale ;
		//Load the parameters for the site!
		if(!class_exists('JSite')){
			$path 	= "../components/com_tuiyo/locale/".$locale ;
		}
        $GLOBALS['mainframe']->addMetaTag("locale", $locale );
		$GLOBALS['mainframe']->addCustomHeadTag('<link href="'.$path.'/LC_MESSAGES/system.client.json" lang="'.$locale.'" rel="gettext" />') ;
    }
    
 	/**
 	 * TuiyoLocalize::getInstance()
 	 * Gets an instance of the localization class
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
			$instance = new TuiyoLocalize()	;	
		}
		return $instance;	
  	}    
    
}


function __l( $messageID ){
	return _( $messageID );
}
