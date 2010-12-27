<?php
/**
 * ******************************************************************
 * TuiyoTableUsers  Class/Object for the Tuiyo platform                              *
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
if(!defined('TUIYO_ADMIN')){
    define('TUIYO_ADMIN_PATH'	, dirname(__FILE__));
    define('TUIYO_PATH' 		, JPATH_COMPONENT_SITE );
}
 
/**
 * Tuiyo Interface
 */
JRequest::setVar('tmpl' , 'component');

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
$START 			=& TuiyoInitiate::start() ;
$DOCU			=& $GLOBALS['API']->get('document');

// Require specific controller if requested
require_once TUIYO_ADMIN_PATH.DS.'controllers'.DS.'tuiyo.php';

/**
 * Prepare the controllers
 **/
$controller = JRequest::getWord('context', '');
$path       = TUIYO_ADMIN_PATH.DS.'controllers'.DS.strtolower($controller).'.php';
	
if (file_exists($path)) {
   require_once $path;
} else {
    $controller = 'Tuiyo';
}


// Create the controller
$classname	= 'TuiyoController'.ucfirst($controller);
$controller	= new $classname( );

// Add some Requirements
$DOCU->startBuild();
$DOCU->addJSDefines();

//Execule the task

$controller->execute( JRequest::getVar( 'do' ) );

// Redirect if set by the controller
$controller->redirect();