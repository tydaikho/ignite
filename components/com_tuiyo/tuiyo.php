<?php
/**
 * ******************************************************************
 * Tuiyo Application entry                                          *
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
defined('_JEXEC') || die('Restricted access');

/**
 * component path
 */
if(!defined('TUIYO_PATH')){
    define('TUIYO_PATH', dirname(__FILE__));
}

/**
 * Main Requires
 */
require_once (TUIYO_PATH.DS.'helpers'.DS.'defines.php' );
require_once (TUIYO_PATH.DS.'helpers'.DS.'loader.php' );
require_once (TUIYO_PATH.DS.'helpers'.DS.'timer.php' );
require_once (TUIYO_PATH.DS.'helpers'.DS.'initiate.php' );

/**
 * Initiate the Application Interface
 */
$START 			=& TuiyoInitiate::start();

/**
 * Add required Response Elements
 */
$document		= $GLOBALS['API']->get('document', true );
$document->startBuild();

/**
 * specific controller requested
 */
$redirect 		= JRequest::getVar( 'redirect' , null);
$view			= JRequest::getVar( 'view' , null );
$class			= JRequest::getVar( 'controller' );

$ctrller		= (!is_null($redirect) ) ? $redirect : $view;
$ctrller		= (is_null($ctrller)   ) ? $class : $ctrller  ;
$ctrller		= (is_null($ctrller)   ) ? "core": $ctrller  ;

/**
 * Proxy mode calls made here
 */
 

/**
 * Application/Core/System/Resource ?
 */
$isApp			= TuiyoLoader::controllerIsApp( $ctrller );	
		
if(!$isApp){
	$controller	= TuiyoLoader::controller( $ctrller , true);
}else{
	JRequest::setVar("app" , $ctrller );
	$controller = TuiyoLoader::controller( "apps" , true );
}

/**
 * Perform the Request task
 */				  
$controller->execute( JRequest::getVar('do' , null)  );

/**
 * Close the TuiYo pInterface
 */
TuiyoAPI::close();

//$loca = $GLOBALS["API"]->get("localization" , "en_GB");

/**
 * Redirect if specified
 */
$document->addJSDefines();
$document->finishBuild();
$controller->redirect();

