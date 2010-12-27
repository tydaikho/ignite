<?php
/**
 * ******************************************************************
 * Main Tuiyo Defines                                               *
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
 
 defined('TUIYO_PATH') || die;
 
 //====DO NOT EDIT ABOVE THIS LINE
 
 define('TUIYO_ALLOW_ASSETS_OVERWRITE' , TRUE );
 
 
 //====DO NOT EDIT BELLOW THIS LINE
 
 //error reporting change to when finished
 error_reporting(E_ALL);
 //error_reporting(E_ALL ^ E_NOTICE);
 //error_reporting(E_ERROR | E_USER_ERROR | E_PARSE );
 //ini_set('display_errors', 0) ;
 
 
 define('TUIYO_LIB' , 			'tuiyo');
 define('TUIYO_CONTROLLERS' , 	TUIYO_PATH.DS.'controllers' );
 define('TUIYO_APPLICATIONS' , 	TUIYO_PATH.DS.'applications' );
 define('TUIYO_HELPERS' , 		TUIYO_PATH.DS.'helpers' );
 define('TUIYO_LOCALE',			TUIYO_PATH.DS.'locale' );
 define('TUIYO_LIBRARIES',		TUIYO_PATH.DS.'libraries' );
 define('TUIYO_MODELS' ,		TUIYO_PATH.DS.'models' );
 define('TUIYO_PLUGINS' ,		TUIYO_PATH.DS.'plugins' );
 define('TUIYO_CLIENT',			TUIYO_PATH.DS.'client' );
 define('TUIYO_VIEWS',			TUIYO_PATH.DS.'views' );
 define('TUIYO_WIDGETS',		TUIYO_PATH.DS.'widgets' );
 define('TUIYO_FILES' ,			TUIYO_PATH.DS.'files' ) ;  
 define('TUIYO_STYLES',			TUIYO_FILES.DS.'styles' ); 	
 define('TUIYO_CONFIG',			JPATH_COMPONENT_ADMINISTRATOR.DS.'config');
 define('TUIYO_MACROS', 		JPATH_COMPONENT_ADMINISTRATOR.DS.'macros');
 define('TUIYO_TABLES',			TUIYO_LIBRARIES.DS.TUIYO_LIB.DS.'database'.DS.'tables' );
 
 
/**
 * Define Codes
 */
 define('TUIYO_CONTINUE' , 		100 );
 define('TUIYO_SPROTOCOL',		101 );
 define('TUIYO_OK',				200	);
 define('TUIYO_CREATED',		201 );
 define('TUIYO_ACCEPTED',		202 );
 define('TUIYO_NAI', 			203 );
 define('TUIYO_NO_CONTENT',		204 );
 define('TUIYO_RESET',			206 );
 define('TUIYO_PARTIAL_CONTENT',206 );
 define('TUIYO_PREDIRECT',		301 );
 define('TUIYO_FOUND' , 		302 );
 define('TUIYO_SEE_OTHER',		303 );
 define('TUIYO_NOT_MODIFIED',   304 );
 define('TUIYO_USE_PROXY', 		305 );
 define('TUIYO_TREDIRECT', 		307 );
 define('TUIYO_BAD_REQUEST', 	400 );
 define('TUIYO_UNAUTHORISED',	401 );
 define('TUIYO_FEE_REQUIRED',	402 );
 define('TUIYO_FORBIDDEN',		403 );
 define('TUIYO_NOT_FOUND',		404 );
 define('TUIYO_NOT_ALLOWED',	405 );
 define('TUIYO_NOT_ACCEPTABLE',	406 );
 define('TUIYO_AUTH_REQUIRED', 	407 );
 define('TUIYO_REQUEST_TIMEOUT',408 );
 define('TUIYO_CONFLICT',		409 );
 define('TUIYO_GONE' , 			410 );
 define('TUIYO_LENGTH_REQUIRED',411	);
 define('TUIYO_PRE_FAIL',		412 );
 define('TUIYO_LARGE_REQUEST_E',413 );
 define('TUIYO_LARGE_REQUEST_U',414 );
 define('TUIYO_NO_MEDIA_TYPE',  415 );
 define('TUIYO_BAD_REQ_RANGE',	416 );
 define('TUIYO_EXPECT_FAIL',	417 );
 define('TUIYO_SERVER_ERROR', 	500 );
 define('TUIYO_NOT_IMPLEMENTED',501 );
 define('TUIYO_BAD_GATEWAY',	502 );
 define('TUIYO_UNAVAILABLE' ,   503 );
 define('TUIYO_GATEWAY_TIMEOUT',504 );
 

/**
 * Joomla Defines
 */ 
 define('TUIYO_INDEX',			'index.php?option=com_tuiyo' ); 
 define('TUIYO_SITE',			JPATH_COMPONENT_SITE );
 define('TUIYO_ADMINISTRATOR',	JPATH_COMPONENT_ADMINISTRATOR );
 define('TUIYO_PROFILE_INDEX',	TUIYO_INDEX.'&amp;view=profile');
 define('TUIYO_LIVE_PATH', 		JURI::root( ).'components/com_tuiyo' );
 define('TUIYO_STYLEDIR',		TUIYO_LIVE_PATH.'/client/default' );
 define('TUIYO_DESIGNS_LIVE', 		JURI::root( false ).'components/com_tuiyo/styles/' );

 /**
 *  File Includes
 */
 define('TUIYO_JS' , 			TUIYO_LIVE_PATH.'/libraries/'.strtolower(TUIYO_LIB).'/interface/javascript' );
 define('TUIYO_JQUERY',			TUIYO_JS.'/frameworks/jquery.js');

 define('TUIYO_SYSTEM_JS' , 	TUIYO_LIBRARIES.DS.TUIYO_LIB.DS.'interface'.DS.'javascript'.DS.'system');
 define('TUIYO_MOOTOOLS',		TUIYO_JS.'/frameworks/mootools.js');
 define('TUIYO_PROTOTYPE',		TUIYO_JS.'/frameworks/prototype.js');
 define('TUIYO_DOJO',			TUIYO_JS.'/frameworks/dojo.js');
 define('TUIYO_SIZZLE',			TUIYO_JS.'/frameworks/sizzle.js');
 define('TUIYO_FACEBOX' ,		TUIYO_JS.'/includes/facebox/facebox.js' );
 define('TUIYO_GZOOM' ,			TUIYO_JS.'/includes/gzoom/gzoom.js' ); 
 define('TUIYO_GETTEXT_JS', 	TUIYO_JS.'/includes/gettext/gettext.js');
 define('TUIYO_CAROUSEL', 		TUIYO_JS.'/includes/carousel/carousel.js');
 define('TUIYO_EASING_EFFECT', 	TUIYO_JS.'/includes/easing/easing.js');
 define('TUIYO_OEMBED', 		TUIYO_JS.'/includes/oembed/jquery.oembed.min.js');
 define('TUIYO_GROUP_LOGO',		TUIYO_LIVE_PATH.'/files/groupthumb70.jpg');

 define('TUIYO_FACEBOX_CSS' ,	TUIYO_JS.'/includes/facebox/facebox.css' );
 define('TUIYO_PURE' ,			TUIYO_JS.'/includes/pure/pure.js' );
 define('TUIYO_TOOLTIP' ,		TUIYO_JS.'/includes/simpletip/simpletip.js' );
 define('TUIYO_WIDGET' ,		TUIYO_JS.'/system/TuiyoWidgets.js' );
 define('TUIYO_STREAM' ,		TUIYO_JS.'/system/TuiyoStream.js' );
 define('TUIYO_APPENDDOM' ,		TUIYO_JS.'/includes/appenddom/appenddom.js' );
 define('TUIYO_CSS_COMMON',		TUIYO_LIVE_PATH.'/client/default/css/common.css');
 define('TUIYO_JS_COMMON',		TUIYO_LIVE_PATH.'/client/default/script/common.js');
 
 define('TUIYO_JQUERY_COMPAT' , 'var Tuiyo = jQuery.noConflict();');
 define('TUIYO_FACEBOX_INIT' ,	"jQuery(document).ready(function($){\n\t$('a[rel*=facebox]').facebox()\n});" );
/**
 *  Allow Execution of Component File
 */
 define('TUIYO_EXECUTE' , 		true );
 
 /**
  * Localization
  */
define('TUIYO_DEFAULT_LOCALE', 'en');
define('TUIYO_DEFAULT_ENCODING', 'UTF-8'); 

 