<?php
/**
 * ******************************************************************
 * Tuiyo application                     *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo    yyeSyV0kysKV2oqpLUES5                                           *
 * ******************************************************************
 */
 
/**
 * no direct access
 */
defined('TUIYO_EXECUTE') || die('Restricted access');


class TuiyoMacroSystemUpdate{
	
	public function __construct(){
		TuiyoEventLoader::preparePlugins( "macros" );
	}
	
	public function run(){
		
		//flush();
		global $mainframe;
		
		//Use Flush to out put
		$params = array(
			'template' 	=> $mainframe->getTemplate(),
			'file'		=> 'index.php',
			'directory'	=> JPATH_THEMES
		);

		$document 	=& JFactory::getDocument();
		$data 		= $document->render($mainframe->getCfg('caching'), $params );
		
		echo _("Welcome to the system update macro...")."\n";
		 
		 //ob_flush();
		 		
		
		echo _("Preparing to upgrade. reading information....")."\n";
		
		
		$url 		= "http://www.tuiyo.co.uk/version.ini";
		$updateUrl  = 'index.php?option=com_tuiyo&context=SystemTools&do=autoCenter&run=systemupdate' ; 
		//$vParams	= TuiyoAPI::getURL( $url );

	}
	
	/**
	 * TuiyoMacroSystemUpdate::getInstance()
	 * Creates an instance of the Plugin
	 * @param mixed $ifNotExist
	 * @return
	 */
	public function getInstance($ifNotExist = NULL )
	{
 		/** Creates new instance if none already exists ***/
        static $instance;

        if (is_object($instance) && !$ifNotExist) {
            return $instance;
        } else {
            $instance = new TuiyoMacroSystemUpdate();
        }
        return $instance;
	}
	
}